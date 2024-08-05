<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubmitProductRequest extends FormRequest
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
        return [
            'products' => 'required|array|min:1|max:10',
            'products.*.name' => 'required|string',
            'products.*.length' => 'required|numeric|min:1',
            'products.*.width' => 'required|numeric|min:1',
            'products.*.height' => 'required|numeric|min:1',
            'products.*.weight' => 'required|numeric|min:1',
            'products.*.quantity' => 'required|numeric|min:1',
        ];
    }
}
