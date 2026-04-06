@extends('layouts.app')
@section('title', 'New Prompt')
@section('header', 'New Prompt')

@section('content')
<div class="max-w-4xl mx-auto" x-data="promptForm()">
    <div class="mb-4">
        <a href="{{ route('prompt-center.index') }}" class="text-sm text-gray-500 hover:text-gray-700 inline-flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to Prompt Center
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-5">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Category <span class="text-red-500">*</span></label>
                <select x-model="form.prompt_category_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    <option value="">Select category...</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ $selectedCategoryId == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Prompt Name <span class="text-red-500">*</span></label>
                <input type="text" x-model="form.name" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" placeholder="e.g. Standard Editorial Spin v2">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Prompt Body <span class="text-red-500">*</span></label>
            <textarea x-model="form.body" @input="$el.style.height = 'auto'; $el.style.height = $el.scrollHeight + 'px'" class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm font-mono leading-relaxed whitespace-pre-wrap" style="min-height: 300px; overflow-y: hidden; resize: none;" placeholder="Enter your prompt here..."></textarea>
        </div>

        <label class="inline-flex items-center gap-2 cursor-pointer">
            <input type="checkbox" x-model="form.is_default" class="rounded border-gray-300 text-amber-600">
            <span class="text-sm text-gray-600">Set as default for this category</span>
        </label>

        <div class="flex items-center gap-3">
            <button @click="save()" :disabled="saving" class="bg-blue-600 text-white px-5 py-2 rounded-lg text-sm hover:bg-blue-700 disabled:opacity-50 inline-flex items-center gap-2">
                <svg x-show="saving" x-cloak class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                <span x-text="saving ? 'Creating...' : 'Create Prompt'"></span>
            </button>
            <a href="{{ route('prompt-center.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Cancel</a>
        </div>

        <div x-show="result" x-cloak class="rounded-lg px-4 py-3 text-sm" :class="success ? 'bg-green-50 border border-green-200 text-green-700' : 'bg-red-50 border border-red-200 text-red-700'" x-text="result"></div>
    </div>
</div>

@push('scripts')
<script>
function promptForm() {
    return {
        form: {
            prompt_category_id: @json($selectedCategoryId ?? ''),
            name: '',
            body: '',
            is_default: false,
        },
        saving: false, result: '', success: false,
        async save() {
            this.saving = true; this.result = '';
            try {
                const r = await fetch('{{ route("prompt-center.store") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' },
                    body: JSON.stringify(this.form)
                });
                const d = await r.json();
                this.success = !!d.success;
                this.result = d.message || (d.success ? 'Created.' : 'Failed.');
                if (d.redirect) setTimeout(() => window.location.href = d.redirect, 600);
            } catch (e) { this.success = false; this.result = 'Error: ' + e.message; }
            this.saving = false;
        }
    };
}
</script>
@endpush
@endsection
