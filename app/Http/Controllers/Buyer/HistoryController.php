<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Review;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class HistoryController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();
        
        // Get or initialize the cache version for this user
        $version = Cache::rememberForever('user_history_v_' . $userId, fn() => time());

        // Build a cache key that includes the version, status, and search query
        $cacheKey = "user_history_{$userId}_v{$version}_" . md5(json_encode($request->only(['status', 'search'])));

        $orders = Cache::remember($cacheKey, 3600, function () use ($request, $userId) {
            $query = Order::where('user_id', $userId)
                ->with(['items.product:id,name,image,rating,review_count', 'review']);

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('search')) {
                $query->where('id', 'LIKE', '%' . $request->search . '%');
            }

            return $query->latest()->get();
        });

        // Ensure is_rated is attached to each order object
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

            foreach ($order->items as $item) {
                // Restore stock for each item
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

        $userId = Auth::id();
        $order = Order::where('id', $request->order_id)
            ->where('user_id', $userId)
            ->where('status', 'Completed')
            ->with('items.product')
            ->firstOrFail();

        // Check if a review already exists to prevent double submission
        if ($order->review()->exists()) {
            return back()->with('error', 'You have already rated this order.');
        }

        DB::transaction(function () use ($request, $order, $userId) {
            foreach ($order->items as $item) {
                $product = $item->product;

                Review::create([
                    'user_id'    => $userId,
                    'order_id'   => $order->id,
                    'product_id' => $product->id, 
                    'rating'     => $request->rating,
                    'comment'    => $request->comment,
                ]);

                // Update product average rating and count
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

        return back()->with('status', 'Thank you for your feedback!');
    }

   public function downloadReceipt(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        // Load relationships for the customer name check
        $order->load(['items.product', 'user.buyerDetail']);

        $data = [
            'order' => $order,
            'generatedAt' => Carbon::now()->timezone('Asia/Manila')->format('M d, Y h:i A'),
        ];

        $pdf = Pdf::loadView('buyer.receipt_pdf', $data);
        
        // Set paper to A4 or custom size if needed
        return $pdf->setPaper('a4')->download('Receipt_Order_' . $order->id . '.pdf');
    }

    /**
     * Cache-Busting logic: Update the version timestamp to invalidate all cached history views for the user.
     */
    private function clearHistoryCache()
    {
        Cache::forever('user_history_v_' . Auth::id(), time());
    }
}