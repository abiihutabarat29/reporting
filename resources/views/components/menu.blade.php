@props(['link', 'icon', 'label', 'active'])
<li class="{{ request()->segment(1) == $active ? 'active' : '' }}">
    <a href="{{ $link }}"><i class="{{ $icon }}"></i><span>{{ $label }}</span>
    </a>
</li>
