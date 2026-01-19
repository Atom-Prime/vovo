<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'category_id' => 'required|integer|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'in_stock' => 'required|boolean',
            'rating' => 'nullable|numeric|min:1|max:5',
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.exists' => 'Категория не найдена',
            'category_id.required' => 'Категория обязательна',
        ];
    }
}
