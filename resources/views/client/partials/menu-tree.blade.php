@php
    $it = $node['item'];
    $children = $node['children'] ?? [];

    // Resolve URL based on type
    $url = '#';
    if ($it->type === 'url' && $it->url) {
        $url = $it->url;
    }

    if ($it->type === 'page' && $it->page_id) {
        $page = \App\Models\Page::find($it->page_id);
        if ($page) {
            $url = route('client.pages.show', $page->slug);
        }
    }

    if ($it->type === 'post_category' && $it->post_category_id) {
        $cat = \App\Models\PostCategory::find($it->post_category_id);
        if ($cat) {
            $url = route('client.posts.category', $cat->slug);
        }
    }

    if ($it->type === 'route' && $it->route_name && \Illuminate\Support\Facades\Route::has($it->route_name)) {
        $url = route($it->route_name);
    }
@endphp

<li class="relative group">
    <a href="{{ $url }}" @if ($it->open_new_tab) target="_blank" @endif
        class="px-3 py-2 rounded hover:bg-white/10 inline-flex items-center gap-1">
        <span>{{ $it->label_bn }}</span>
        @if (count($children))
            <span class="text-xs opacity-80">▾</span>
        @endif
    </a>

    @if (count($children))
        <ul
            class="hidden group-hover:block absolute left-0 top-full mt-1 min-w-56 bg-white text-slate-800 rounded shadow border overflow-hidden z-20">
            @foreach ($children as $ch)
                @php $child = $ch['item']; @endphp
                <li class="border-b last:border-b-0">
                    @include('client.partials.menu-tree', ['node' => $ch])
                </li>
            @endforeach
        </ul>
    @endif
</li>
