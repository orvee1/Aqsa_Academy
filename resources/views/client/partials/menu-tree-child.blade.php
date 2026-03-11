@php
    $hasChildren = !empty($node['children']) && count($node['children']);
    $url = $node['url'] ?? '#';
    $title = $node['title'] ?? 'Submenu';
    $target = $node['target'] ?? '_self';
@endphp

<li class="relative group border-b border-slate-200 last:border-b-0">
    @if ($hasChildren)
        <button type="button" onclick="toggleMenu(this)"
            class="w-full flex items-center justify-between px-4 py-2 text-sm hover:bg-slate-50">
            <span>{{ $title }}</span>
            <span>›</span>
        </button>

        <ul
            class="submenu hidden absolute left-full top-0 z-50 min-w-[220px] bg-white text-slate-800 border border-slate-300 shadow-lg">
            @foreach ($node['children'] as $child)
                @include('client.partials.menu-tree-child', ['node' => $child])
            @endforeach
        </ul>
    @else
        <a href="{{ $url }}" target="{{ $target }}" @if ($target === '_blank') rel="noopener" @endif
            class="block px-4 py-2 text-sm hover:bg-slate-50">
            {{ $title }}
        </a>
    @endif
</li>
