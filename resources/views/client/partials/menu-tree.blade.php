<li class="relative">
    @php
        $hasChildren = !empty($node['children']) && count($node['children']);
        $url = $node['url'] ?? '#';
        $title = $node['title'] ?? 'Menu';
        $target = $node['target'] ?? '_self';
    @endphp

    @if ($hasChildren)
        <button type="button" onclick="toggleMenu(this)"
            class="inline-flex items-center gap-1 px-3 py-2 rounded hover:bg-white/10">
            <span>{{ $title }}</span>
            <span class="text-[10px]">▼</span>
        </button>

        <ul
            class="submenu hidden absolute left-0 top-full z-50 min-w-[240px] bg-white text-slate-800 border border-slate-300 shadow-lg">
            @foreach ($node['children'] as $child)
                @include('client.partials.menu-tree-child', ['node' => $child])
            @endforeach
        </ul>
    @else
        <a href="{{ $url }}" target="{{ $target }}" @if ($target === '_blank') rel="noopener" @endif
            class="inline-flex items-center px-3 py-2 rounded hover:bg-white/10">
            {{ $title }}
        </a>
    @endif
</li>
