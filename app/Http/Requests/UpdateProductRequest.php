<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateProductRequest extends FormRequest
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
            'product_number' => 'nullable|string|max:255|unique:products,product_number,' . $this->route('id'),
            'regular_price' => 'nullable|numeric|gt:0|lt:999999.99|' . ($this->input('sale_price') ? 'gt:sale_price' : ''),
            'sale_price' => 'nullable|numeric|' . ($this->input('regular_price') ? 'lt:regular_price' : ''),
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'department_id' => 'nullable|exists:departments,id',
            'manufacturer_id' => 'nullable|exists:manufacturers,id',
            'upc' => 'nullable|string|max:255|unique:products,upc',
            'sku' => 'nullable|string|max:255|unique:products,sku',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.*
     * @return array
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors(),
        ], 422));
    }

}
