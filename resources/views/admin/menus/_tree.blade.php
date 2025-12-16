@php
    $pad = $level * 16;
@endphp

<div class="space-y-2">
    @foreach ($nodes as $n)
        <div class="border rounded-lg px-3 py-2" style="margin-left: {{ $pad }}px">
            <div class="flex items-start justify-between gap-3">
                <div class="min-w-0">
                    <div class="font-medium text-sm">
                        {{ $n->label_bn }}
                        <span class="text-xs text-gray-500 ml-2">({{ $n->type }})</span>
                    </div>
                    <div class="text-xs text-gray-500 mt-0.5">
                        pos: {{ $n->position }} |
                        @if ($n->type === 'url')
                            {{ $n->url }}
                        @elseif($n->type === 'page')
                            page_id: {{ $n->page_id }}
                        @elseif($n->type === 'post_category')
                            category_id: {{ $n->post_category_id }}
                        @else
                            route: {{ $n->route_name }}
                        @endif
                        @if ($n->open_new_tab)
                            <span class="ml-2 px-1.5 py-0.5 rounded bg-slate-100">new tab</span>
                        @endif
                    </div>
                </div>

                <div class="flex items-center gap-2 shrink-0">
                    <form method="POST" action="{{ route('admin.menu-items.up', $n) }}">
                        @csrf @method('PATCH')
                        <button class="px-2 py-1 border rounded text-xs">↑</button>
                    </form>
                    <form method="POST" action="{{ route('admin.menu-items.down', $n) }}">
                        @csrf @method('PATCH')
                        <button class="px-2 py-1 border rounded text-xs">↓</button>
                    </form>

                    <form method="POST" action="{{ route('admin.menu-items.toggle', $n) }}">
                        @csrf @method('PATCH')
                        <button class="px-2 py-1 border rounded text-xs">
                            {{ $n->status ? 'On' : 'Off' }}
                        </button>
                    </form>

                    <a href="{{ route('admin.menu-items.edit', $n) }}"
                        class="px-2 py-1 bg-indigo-600 text-white rounded text-xs">Edit</a>

                    <form method="POST" action="{{ route('admin.menu-items.destroy', $n) }}"
                        onsubmit="return confirm('Delete item?')">
                        @csrf @method('DELETE')
                        <button class="px-2 py-1 bg-rose-600 text-white rounded text-xs">Del</button>
                    </form>
                </div>
            </div>
        </div>

        @if (!empty($n->children_nodes) && count($n->children_nodes))
            @include('admin.menus._tree', ['nodes' => $n->children_nodes, 'level' => $level + 1])
        @endif
    @endforeach
</div>
