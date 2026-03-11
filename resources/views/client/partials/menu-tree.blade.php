<li class="relative group">
    @php
        $hasChildren = !empty($node['children']) && count($node['children']);
        $url = $node['url'] ?? '#';
        $title = $node['title'] ?? 'Menu';
    @endphp

    @if ($hasChildren)
        <button type="button" onclick="toggleMenu(this)"
            class="inline-flex items-center gap-1 px-3 py-2 rounded hover:bg-white/10">
            <span>{{ $title }}</span>
            <span class="text-xs">▼</span>
        </button>

        <ul
            class="submenu hidden absolute left-0 top-full z-50 min-w-[220px] bg-white text-slate-800 border border-slate-300 shadow-lg">
            @foreach ($node['children'] as $child)
                <li class="border-b border-slate-200 last:border-b-0">
                    <a href="{{ $child['url'] ?? '#' }}" class="block px-4 py-2 text-sm hover:bg-slate-50">
                        {{ $child['title'] ?? 'Submenu' }}
                    </a>
                </li>
            @endforeach
        </ul>
    @else
        <a href="{{ $url }}" class="inline-flex items-center px-3 py-2 rounded hover:bg-white/10">
            {{ $title }}
        </a>
    @endif
</li>
