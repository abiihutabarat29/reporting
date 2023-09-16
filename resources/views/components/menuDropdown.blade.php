@props(['icon', 'label', 'active'])
<li class="has-sub {{ $active }}">
    <a href="#"><b class="caret pull-right"></b><i class="{{ $icon }}"></i>
        <span>{{ $label }}</span>
    </a>
    <ul class="sub-menu">
        {{ $slot }}
    </ul>
</li>
