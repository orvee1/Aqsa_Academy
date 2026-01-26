@php
    $it = $node['item'];
    $children = $node['children'] ?? [];
    $url = $it->resolved_url ?? '#';
@endphp

<li class="relative">
    <div class="flex items-center justify-between">
        {{-- Parent link (always clickable) --}}
        <a href="{{ $url }}" @if ($it->open_new_tab) target="_blank" @endif
            class="px-3 py-2 hover:bg-white/10 rounded flex-1">
            {{ $it->label_bn }}
        </a>

        {{-- Dropdown toggle --}}
        @if (count($children))
            <button type="button" class="px-2 py-2 text-xs opacity-70 hover:opacity-100"
                onclick="toggleMenu(this); event.stopPropagation();">
                ▾
            </button>
        @endif
    </div>

    {{-- Submenu --}}
    @if (count($children))
        <ul class="submenu hidden absolute left-0 top-full mt-1 min-w-56 bg-white text-slate-800 rounded shadow z-50">
            @foreach ($children as $ch)
                <li class="border-b last:border-b-0">
                    @include('client.partials.menu-tree', ['node' => $ch])
                </li>
            @endforeach
        </ul>
    @endif
</li>
