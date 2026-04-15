<?php

namespace hexa_package_prompt_center\Prompts\Templates\Models;

use hexa_package_prompt_center\Prompts\Categories\Models\PromptCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PromptTemplate extends Model
{
    protected $table = 'prompt_templates';

    protected $fillable = [
        'prompt_category_id',
        'name',
        'slug',
        'body',
        'notes',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    /**
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(PromptCategory::class, 'prompt_category_id');
    }
}
