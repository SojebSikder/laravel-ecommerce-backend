<div class="sidebar-wrapper sidebar-theme">

    {{-- @if (auth()->user()->type == 'admin') --}}
    @if (in_array(auth()->user()->type, ['admin', 'su_admin']))
        @php
            $prefix = Request::route()->getPrefix();
            $route = Route::current()->getName();
        @endphp

    @endif
</div>
