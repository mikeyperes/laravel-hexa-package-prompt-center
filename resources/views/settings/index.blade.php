@extends('layouts.app')
@section('title', 'Prompt Center Settings')
@section('header', 'Prompt Center Settings')

@section('content')
<div class="max-w-3xl mx-auto space-y-4">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-gray-800">Prompt Center</h3>
            <span class="text-xs text-gray-400">v{{ config('prompt-center.version', '?') }}</span>
        </div>
        <p class="text-sm text-gray-600 mb-4">Manage AI prompt categories and templates. Each category can have multiple named prompts with one set as default.</p>
        <a href="{{ route('prompt-center.index') }}" class="text-blue-600 hover:underline text-sm">Go to Prompt Center &rarr;</a>
    </div>
</div>
@endsection
