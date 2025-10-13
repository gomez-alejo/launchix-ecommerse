<?php

namespace App\Http\Requests\Api\V1\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class CreateProductRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:5000'],
            'price' => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'stock' => ['required', 'integer', 'min:0'],
            'entrepreneur_id' => ['required', 'integer', 'exists:entrepreneurs,id']
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'El nombre del producto es obligatorio',
            'name.max' => 'El nombre no puede exceder 255 caracteres',
            'category.required' => 'La categoría es obligatoria',
            'description.required' => 'La descripción es obligatoria',
            'description.max' => 'La descripción no puede exceder 5000 caracteres',
            'price.required' => 'El precio es obligatorio',
            'price.numeric' => 'El precio debe ser un número válido',
            'price.min' => 'El precio no puede ser negativo',
            'price.max' => 'El precio no puede exceder $999,999.99',
            'stock.required' => 'La cantidad en stock es obligatoria',
            'stock.integer' => 'El stock debe ser un número entero',
            'stock.min' => 'El stock no puede ser negativo',
            'entrepreneur_id.required' => 'El ID del emprendedor es obligatorio',
            'entrepreneur_id.exists' => 'El emprendedor seleccionado no existe',
            'status.in' => 'El estado debe ser: active, inactive o pending',
            'discount_percentage.numeric' => 'El descuento debe ser un número válido',
            'discount_percentage.min' => 'El descuento no puede ser negativo',
            'discount_percentage.max' => 'El descuento no puede exceder 100%',
            'gallery_images.array' => 'Las imágenes de galería deben ser un array',
            'gallery_images.max' => 'No se pueden subir más de 10 imágenes'
        ];
    }

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'message' => 'Los datos del producto son inválidos',
                'errors' => $validator->errors()
            ], 422)
        );
    }
}
