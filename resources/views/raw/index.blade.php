@extends('layouts.app')
@section('title', 'Raw — Prompt Center')
@section('header', 'Raw — Prompt Center')

@section('content')
<div class="space-y-6">
    <div class="bg-gray-900 rounded-xl p-6 text-sm font-mono">
        <h2 class="text-white font-semibold mb-3">Prompt Center Functions</h2>
        <table class="w-full text-left">
            <thead>
                <tr class="text-gray-400 border-b border-gray-700">
                    <th class="py-1.5 px-2">Function</th>
                    <th class="py-1.5 px-2">Method</th>
                    <th class="py-1.5 px-2">Route</th>
                    <th class="py-1.5 px-2">Status</th>
                </tr>
            </thead>
            <tbody class="text-gray-300">
                <tr class="border-b border-gray-800"><td class="py-1.5 px-2">List all</td><td class="py-1.5 px-2 text-blue-400">index()</td><td class="py-1.5 px-2 text-green-400">GET /prompt-center</td><td class="py-1.5 px-2 text-green-400">LIVE</td></tr>
                <tr class="border-b border-gray-800"><td class="py-1.5 px-2">Create</td><td class="py-1.5 px-2 text-blue-400">create()</td><td class="py-1.5 px-2 text-green-400">GET /prompt-center/create</td><td class="py-1.5 px-2 text-green-400">LIVE</td></tr>
                <tr class="border-b border-gray-800"><td class="py-1.5 px-2">Store</td><td class="py-1.5 px-2 text-blue-400">store()</td><td class="py-1.5 px-2 text-green-400">POST /prompt-center</td><td class="py-1.5 px-2 text-green-400">LIVE</td></tr>
                <tr class="border-b border-gray-800"><td class="py-1.5 px-2">Edit</td><td class="py-1.5 px-2 text-blue-400">edit()</td><td class="py-1.5 px-2 text-green-400">GET /prompt-center/{id}/edit</td><td class="py-1.5 px-2 text-green-400">LIVE</td></tr>
                <tr class="border-b border-gray-800"><td class="py-1.5 px-2">Update</td><td class="py-1.5 px-2 text-blue-400">update()</td><td class="py-1.5 px-2 text-green-400">PUT /prompt-center/{id}</td><td class="py-1.5 px-2 text-green-400">LIVE</td></tr>
                <tr class="border-b border-gray-800"><td class="py-1.5 px-2">Delete</td><td class="py-1.5 px-2 text-blue-400">destroy()</td><td class="py-1.5 px-2 text-green-400">DELETE /prompt-center/{id}</td><td class="py-1.5 px-2 text-green-400">LIVE</td></tr>
                <tr class="border-b border-gray-800"><td class="py-1.5 px-2">Set Default</td><td class="py-1.5 px-2 text-blue-400">setDefault()</td><td class="py-1.5 px-2 text-green-400">POST /prompt-center/{id}/set-default</td><td class="py-1.5 px-2 text-green-400">LIVE</td></tr>
                <tr class="border-b border-gray-800"><td class="py-1.5 px-2">Get Default (API)</td><td class="py-1.5 px-2 text-blue-400">PromptService::getDefault()</td><td class="py-1.5 px-2 text-yellow-400">PHP only</td><td class="py-1.5 px-2 text-green-400">LIVE</td></tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
