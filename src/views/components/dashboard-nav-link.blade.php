{{--
A customisable link of navigation for the dashboard.

Need to provide :
- $iconName : The name of the icon to display (e.g. 'feather-home')
- $targetRouteName : The name of the route to link to (e.g. 'dashboard.index')
--}}

@php
        @endphp

<li>
    <a
            href="{{ url($targetRouteName) }}"
            @if($currentRouteName === $targetRouteName)
                class="active"
            @endif
    >
        <i class="{{ $iconName }}"></i>
        <span>
            {!! $slot !!}
        </span>
    </a>
</li>
