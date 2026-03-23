@component('mail::layout')
    {{-- Header Slot (Outside the Card) --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            <span style="color: #6D4C41; font-size: 32px; font-weight: 900; letter-spacing: -0.02em;">Coco</span><span style="color: #738D56; font-size: 32px; font-weight: 900; letter-spacing: -0.02em;">Hub</span>
            <div style="font-size: 10px; color: #9CA3AF; text-transform: uppercase; letter-spacing: 0.3em; margin-top: 4px; font-weight: bold;">LUMIERE</div>
        @endcomponent
    @endslot

    {{-- Body Content (Inside the Card) --}}
    {!! $slot !!}

    {{-- Footer Slot (Outside the Card) --}}
    @slot('footer')
        @component('mail::footer')
            © {{ date('Y') }} COCOHUB | LUMIERE. ALL RIGHTS RESERVED.
            <br>
            <span style="color: #8E8E8E; font-size: 11px;">For educational purposes only, and no copyright infringement is intended.</span>
        @endcomponent
    @endslot
@endcomponent