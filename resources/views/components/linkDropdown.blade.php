@props(['link', 'label', 'active'])
<li class="{{ request()->segment(1) == $active ? 'active' : '' }}">
    <a href="{{ $link }}">{{ $label }}</a>
</li>
