<?php

namespace hexa_package_prompt_center\Prompts\Categories\Http\Controllers;

use hexa_package_prompt_center\Prompts\Categories\Models\PromptCategory;
use hexa_package_prompt_center\Prompts\Categories\Services\PromptService;
use hexa_package_prompt_center\Prompts\Templates\Models\PromptTemplate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PromptCenterController extends Controller
{
    public function __construct(
        private PromptService $promptService
    ) {}

    /**
     * Prompt Center — all categories with their templates.
     *
     * @return View
     */
    public function index(): View
    {
        $categories = $this->promptService->getAllCategories();

        return view('prompt-center::index', [
            'categories' => $categories,
        ]);
    }

    /**
     * Create new prompt form.
     *
     * @param Request $request
     * @return View
     */
    public function create(Request $request): View
    {
        $categories = PromptCategory::orderBy('sort_order')->get();

        return view('prompt-center::create', [
            'categories' => $categories,
            'selectedCategoryId' => $request->input('category_id'),
        ]);
    }

    /**
     * Store new prompt.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'prompt_category_id' => 'required|exists:prompt_categories,id',
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:prompt_templates,slug',
            'body' => 'required|string',
            'notes' => 'nullable|string',
            'is_default' => 'nullable|boolean',
        ]);

        if (!empty($validated['is_default'])) {
            PromptTemplate::where('prompt_category_id', $validated['prompt_category_id'])
                ->update(['is_default' => false]);
        }

        $validated['slug'] = $this->resolveSlug(
            $validated['slug'] ?? null,
            $validated['name'],
            null
        );

        $template = PromptTemplate::create($validated);

        if (function_exists('hexaLog')) {
            hexaLog('prompt-center', 'prompt_created', "Prompt created: {$template->name}");
        }

        return response()->json([
            'success' => true,
            'message' => "Prompt '{$template->name}' created.",
            'redirect' => route('prompt-center.edit', $template->id),
        ]);
    }

    /**
     * Edit prompt.
     *
     * @param int $id
     * @return View
     */
    public function edit(int $id): View
    {
        $template = PromptTemplate::with('category')->findOrFail($id);
        $categories = PromptCategory::orderBy('sort_order')->get();

        return view('prompt-center::edit', [
            'template' => $template,
            'categories' => $categories,
        ]);
    }

    /**
     * Update prompt.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $template = PromptTemplate::findOrFail($id);

        $validated = $request->validate([
            'prompt_category_id' => 'required|exists:prompt_categories,id',
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:prompt_templates,slug,' . $template->id,
            'body' => 'required|string',
            'notes' => 'nullable|string',
            'is_default' => 'nullable|boolean',
        ]);

        if (!empty($validated['is_default'])) {
            PromptTemplate::where('prompt_category_id', $validated['prompt_category_id'])
                ->where('id', '!=', $template->id)
                ->update(['is_default' => false]);
        }

        $validated['slug'] = $this->resolveSlug(
            $validated['slug'] ?? null,
            $validated['name'],
            $template->id
        );

        $template->update($validated);

        if (function_exists('hexaLog')) {
            hexaLog('prompt-center', 'prompt_updated', "Prompt updated: {$template->name}");
        }

        return response()->json([
            'success' => true,
            'message' => "Prompt '{$template->name}' updated.",
        ]);
    }

    /**
     * Delete prompt.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $template = PromptTemplate::findOrFail($id);
        $name = $template->name;
        $template->delete();

        if (function_exists('hexaLog')) {
            hexaLog('prompt-center', 'prompt_deleted', "Prompt deleted: {$name}");
        }

        return response()->json(['success' => true, 'message' => "Prompt '{$name}' deleted."]);
    }

    /**
     * Set a prompt as default for its category.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function setDefault(int $id): JsonResponse
    {
        $this->promptService->setDefault($id);
        $template = PromptTemplate::find($id);

        return response()->json([
            'success' => true,
            'message' => "'{$template->name}' is now the default.",
        ]);
    }

    private function resolveSlug(?string $slug, string $name, ?int $ignoreId): string
    {
        $base = Str::slug(trim($slug ?: $name));
        $base = $base !== '' ? $base : 'prompt-template';
        $candidate = $base;
        $suffix = 2;

        while (PromptTemplate::query()
            ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
            ->where('slug', $candidate)
            ->exists()) {
            $candidate = $base . '-' . $suffix;
            $suffix++;
        }

        return $candidate;
    }
}
