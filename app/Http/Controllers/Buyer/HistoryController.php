<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class HistoryController extends Controller
{
    public function index(Request $request)
    {
        $cacheKey = 'user_history_' . Auth::id() . '_' . md5(json_encode($request->all()));

        $orders = Cache::remember($cacheKey, 3600, function () use ($request) {
            $query = Order::where('user_id', Auth::id())
                ->with(['items.product:id,name,image,rating,review_count', 'review']);

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('search')) {
                $query->where('id', 'LIKE', '%' . $request->search . '%');
            }

            return $query->latest()->get();
        });

        $orders->each(function ($order) {
            $order->is_rated = $order->review !== null;
        });

        return view('buyer.history', compact('orders'));
    }

    public function cancel(Order $order)
    {
        if ($order->user_id !== Auth::id() || $order->status !== 'Awaiting Shipping') {
            return back()->with('error', 'This order cannot be cancelled.');
        }

        DB::transaction(function () use ($order) {
            $order->update(['status' => 'Cancelled']);

            // Restore stock for each item in the cancelled order
            foreach ($order->items as $item) {
                $item->product->increment('stock', $item->quantity);
                Cache::forget("product_detail_{$item->product_id}");
            }

            $this->clearHistoryCache();
        });

        return back()->with('status', 'Order #' . $order->id . ' has been cancelled and stock has been restored.');
    }

    public function receive(Order $order)
    {
        if ($order->user_id !== Auth::id() || $order->status !== 'On Delivery') {
            return back()->with('error', 'Order receipt can only be confirmed for items currently on delivery.');
        }

        $order->update(['status' => 'Completed']);
        $this->clearHistoryCache();

        return back()->with('status', 'Order received! Thank you for confirming.');
    }

    public function storeReview(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'rating'   => 'required|integer|min:1|max:5',
            'comment'  => 'nullable|string|max:500',
        ]);

        $order = Order::where('id', $request->order_id)
            ->where('user_id', Auth::id())
            ->where('status', 'Completed')
            ->with('items.product')
            ->firstOrFail();

        if ($order->review()->exists()) {
            return back()->with('error', 'You have already rated this order.');
        }

        DB::transaction(function () use ($request, $order) {
            foreach ($order->items as $item) {
                $product = $item->product;

                Review::create([
                    'user_id'    => Auth::id(),
                    'order_id'   => $order->id,
                    'product_id' => $product->id, 
                    'rating'     => $request->rating,
                    'comment'    => $request->comment,
                ]);

                $oldCount = $product->review_count;
                $oldRating = $product->rating;
                $newCount = $oldCount + 1;
                $newRating = (($oldRating * $oldCount) + $request->rating) / $newCount;

                $product->update([
                    'rating'       => $newRating,
                    'review_count' => $newCount
                ]);

                Cache::forget("product_detail_{$product->id}");
            }

            $this->clearHistoryCache();
        });

        return back()->with('status', 'Thank you for your feedback! Your rating helps other buyers.');
    }

    /**
     * Helper to clear user history cache.
     */
    private function clearHistoryCache()
    {
        // Since we use a complex key with md5, flushing or specific tags are better, 
        // but for a simple fix, we flush related caches.
        Cache::flush(); 
    }
}