@extends('layouts.app')
@section('title', 'Prompt Center')
@section('header', 'Prompt Center')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <p class="text-sm text-gray-500">{{ $categories->sum(fn($c) => $c->templates->count()) }} prompt(s) across {{ $categories->count() }} categories</p>
        <a href="{{ route('prompt-center.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">+ New Prompt</a>
    </div>

    @foreach($categories as $category)
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-lg font-semibold text-gray-800">{{ $category->name }}</h3>
                @if($category->description)
                    <p class="text-xs text-gray-400 mt-0.5">{{ $category->description }}</p>
                @endif
            </div>
            <a href="{{ route('prompt-center.create', ['category_id' => $category->id]) }}" class="text-xs text-blue-600 hover:text-blue-800">+ Add Prompt</a>
        </div>

        @if($category->templates->isEmpty())
            <p class="text-sm text-gray-400">No prompts in this category yet.</p>
        @else
            <div class="space-y-3">
                @foreach($category->templates as $template)
                <div class="border border-gray-200 rounded-lg p-4 hover:shadow-sm transition-shadow {{ $template->is_default ? 'ring-2 ring-amber-300 bg-amber-50' : '' }}">
                    <div class="flex items-start justify-between">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('prompt-center.edit', $template->id) }}" class="text-sm font-medium text-gray-800 hover:text-blue-600 break-words">{{ $template->name }}</a>
                                @if($template->is_default)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-amber-200 text-amber-800">Default</span>
                                @endif
                            </div>
                            <p class="text-[11px] font-mono text-gray-400 mt-1">{{ $template->slug ?: 'no-slug' }}</p>
                            <p class="text-xs text-gray-400 mt-1 break-words">{{ \Illuminate\Support\Str::limit($template->body, 150) }}</p>
                        </div>
                        <div class="flex items-center gap-2 flex-shrink-0 ml-3">
                            @if(!$template->is_default)
                            <form method="POST" action="{{ route('prompt-center.set-default', $template->id) }}" class="inline" x-data="{ setting: false }">
                                @csrf
                                <button type="submit" @click.prevent="setting = true; fetch('{{ route('prompt-center.set-default', $template->id) }}', { method: 'POST', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content, 'Accept': 'application/json' } }).then(r => r.json()).then(d => { if (d.success) location.reload(); }).catch(() => setting = false)" :disabled="setting" class="text-xs text-amber-600 hover:text-amber-800 disabled:opacity-50">Set Default</button>
                            </form>
                            @endif
                            <a href="{{ route('prompt-center.edit', $template->id) }}" class="text-gray-400 hover:text-blue-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>
    @endforeach
</div>
@endsection
