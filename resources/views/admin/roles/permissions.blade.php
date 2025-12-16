@extends('tailwind.layouts.admin')
@section('title', 'Assign Permissions')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-xl font-bold text-gray-800">Assign Permissions</h2>
        <div class="text-sm text-gray-500 mt-1">Role: <span class="font-semibold">{{ $role->name }}</span></div>
    </div>
    <a href="{{ route('admin.roles.index') }}" class="px-4 py-2 border rounded">Back</a>
</div>

<div class="bg-white rounded shadow p-5">
    <form method="POST" action="{{ route('admin.roles.permissions.update', $role) }}">
        @csrf
        @method('PUT')

        <div class="flex items-center justify-between gap-3 mb-4">
            <label class="flex items-center gap-2 text-sm font-medium">
                <input id="checkAll" type="checkbox" class="h-4 w-4">
                Select All
            </label>

            <button class="px-4 py-2 bg-indigo-600 text-white rounded">
                Save Permissions
            </button>
        </div>

        <div class="space-y-4">
            @foreach($grouped as $groupName => $items)
                <div class="border rounded-lg overflow-hidden">
                    <div class="bg-slate-50 px-4 py-3 flex items-center justify-between">
                        <div class="font-semibold">{{ $groupName }}</div>
                        <label class="flex items-center gap-2 text-sm">
                            <input type="checkbox" class="h-4 w-4 group-toggle" data-group="{{ \Illuminate\Support\Str::slug($groupName) }}">
                            Select Group
                        </label>
                    </div>

                    <div class="p-4 grid md:grid-cols-2 lg:grid-cols-3 gap-3">
                        @foreach($items as $p)
                            @php $g = \Illuminate\Support\Str::slug($groupName); @endphp
                            <label class="flex items-start gap-2 border rounded px-3 py-2">
                                <input
                                    type="checkbox"
                                    name="permission_ids[]"
                                    value="{{ $p->id }}"
                                    class="h-4 w-4 mt-0.5 perm-item"
                                    data-group="{{ $g }}"
                                    @checked(in_array($p->id, $assignedIds))
                                >
                                <span class="text-sm">
                                    <div class="font-medium">{{ $p->label }}</div>
                                    <div class="text-xs text-gray-500 font-mono">{{ $p->key }}</div>
                                    @if(!$p->status)
                                        <div class="text-xs text-rose-600 mt-1">Inactive permission</div>
                                    @endif
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            <button class="px-4 py-2 bg-indigo-600 text-white rounded">
                Save Permissions
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
(function(){
    const checkAll = document.getElementById('checkAll');
    const perms = Array.from(document.querySelectorAll('.perm-item'));
    const groupToggles = Array.from(document.querySelectorAll('.group-toggle'));

    function refreshCheckAll(){
        const allChecked = perms.length && perms.every(p => p.checked);
        const anyChecked = perms.some(p => p.checked);
        checkAll.indeterminate = anyChecked && !allChecked;
        checkAll.checked = allChecked;
    }

    function refreshGroupToggle(group){
        const items = perms.filter(p => p.dataset.group === group);
        const toggle = groupToggles.find(t => t.dataset.group === group);
        if(!toggle) return;
        const all = items.length && items.every(p => p.checked);
        const any = items.some(p => p.checked);
        toggle.indeterminate = any && !all;
        toggle.checked = all;
    }

    checkAll?.addEventListener('change', () => {
        perms.forEach(p => p.checked = checkAll.checked);
        groupToggles.forEach(t => { t.checked = checkAll.checked; t.indeterminate = false; });
    });

    perms.forEach(p => {
        p.addEventListener('change', () => {
            refreshCheckAll();
            refreshGroupToggle(p.dataset.group);
        });
    });

    groupToggles.forEach(t => {
        t.addEventListener('change', () => {
            const group = t.dataset.group;
            perms.filter(p => p.dataset.group === group).forEach(p => p.checked = t.checked);
            refreshCheckAll();
        });
    });

    // initial
    refreshCheckAll();
    groupToggles.forEach(t => refreshGroupToggle(t.dataset.group));
})();
</script>
@endpush
@endsection
