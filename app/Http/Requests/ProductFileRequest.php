<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductFileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */

    public function rules(): array
    {
        return  [
            // ── Basic Info ────────────────────────────────────────────────
            'name'             => ['required', 'string', 'max:20'],
            'slug'             => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-z0-9\-]+$/',
                Rule::unique('products', 'slug')->ignore($this->route('product'))
            ],
            'description'      => ['nullable', 'string'],
            'short_desc'       => ['nullable', 'string', 'max:500'],
            'image_url'        => ['nullable', 'url', 'max:255'],

            // ── Pricing ───────────────────────────────────────────────────
            'base_price'       => ['required', 'numeric', 'min:0', 'decimal:0,2'],
            'compare_price'    => [
                'nullable',
                'numeric',
                'min:0',
                'decimal:0,2',
                // 'gt:base_price'
            ],   // sale price must be lower than base
            'cost_price'       => ['nullable', 'numeric', 'min:0', 'decimal:0,2'],
            'tax_rate'         => ['required', 'numeric', 'min:0', 'max:100', 'decimal:0,2'],
            'weight_grams'     => ['nullable', 'integer', 'min:0'],

            // ── Relations ─────────────────────────────────────────────────
            'category_id'      => ['required', 'array', 'min:1'],
            'category_id.*'    => ['required', 'string', 'exists:categories,id'],
            'brand_id'         => ['required', 'string', 'exists:brands,id'],

            // ── Variants ──────────────────────────────────────────────────
            'colors'           => ['nullable', 'array'],
            'colors.*'         => ['string', 'max:50'],
            'sizes'            => ['nullable', 'array'],
            'sizes.*'          => ['string', 'in:XS,S,M,L,XL,XXL'],

            // ── Images (file uploads) ─────────────────────────────────────
            'images'           => ['nullable', 'array', 'max:10'],
            // 'images.*'         => ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048'], // 2 MB each

            // ── Schedule ──────────────────────────────────────────────────
            // 'schedule'         => ['nullable', 'date', 'after_or_equal:today'],

            // ── Flags ─────────────────────────────────────────────────────
            'is_active'        => ['boolean'],
            'is_featured'      => ['boolean'],
            'is_organic'       => ['boolean'],

            // ── SEO ───────────────────────────────────────────────────────
            'meta_title'       => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.max'              => 'Product title must not exceed 20 characters.',
            'slug.unique'           => 'This slug is already taken. Please choose another.',
            'slug.regex'            => 'Slug may only contain lowercase letters, numbers, and hyphens.',
            'base_price.required'   => 'A base price is required.',
            'base_price.min'        => 'Base price cannot be negative.',
            'compare_price.gt'      => 'Sale price must be less than the base price.',
            'category_id.required'  => 'At least one category is required.',
            'category_id.*.exists'  => 'One or more selected categories are invalid.',
            'brand_id.exists'       => 'The selected brand does not exist.',
            'sizes.*.in'            => 'Accepted sizes are: XS, S, M, L, XL, XXL.',
            'images.*.image'        => 'Each file must be a valid image.',
            'images.*.max'          => 'Each image must not exceed 2 MB.',
            'schedule.after_or_equal' => 'The sale date must be today or a future date.',
        ];
    }

    public function attributes(): array
    {
        return [
            'name'             => 'product title',
            'slug'             => 'URL slug',
            'base_price'       => 'base price',
            'compare_price'    => 'sale price',
            'cost_price'       => 'cost price',
            'tax_rate'         => 'tax rate',
            'weight_grams'     => 'weight',
            'category_id'      => 'category',
            'category_id.*'    => 'category item',
            'brand_id'         => 'brand',
            'images.*'         => 'image file',
            'meta_title'       => 'meta title',
            'meta_description' => 'meta description',
            'is_active'        => 'active status',
            'is_featured'      => 'featured status',
            'is_organic'       => 'organic flag',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            // Auto-generate slug from name if not provided
            'slug' => $this->slug
                ?: str($this->name)->lower()->slug('-')->value(),

            // Cast boolean strings coming from multipart forms
            'is_active'   => filter_var($this->is_active,   FILTER_VALIDATE_BOOLEAN),
            'is_featured' => filter_var($this->is_featured, FILTER_VALIDATE_BOOLEAN),
            'is_organic'  => filter_var($this->is_organic,  FILTER_VALIDATE_BOOLEAN),

            // Empty strings → null for optional numeric fields
            'compare_price' => $this->compare_price ?: null,
            'cost_price'    => $this->cost_price    ?: null,
            'weight_grams'  => $this->weight_grams  ?: null,
            'schedule'      => $this->schedule      ?: null,
        ]);
    }
}
