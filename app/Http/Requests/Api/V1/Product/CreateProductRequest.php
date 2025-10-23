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
            'category' => ['required', 'string', 'max:150'],
            'description' => ['required', 'string', 'max:5000'],
            'price' => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'stock' => ['required', 'integer', 'min:0'],
            'entrepreneur_id' => ['prohibited'],
            'main_image' => ['nullable', 'image', 'max:2048'],
            'gallery_images' => ['nullable', 'array', 'max:10'],
            'gallery_images.*' => ['image', 'max:2048'],
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
            'category.string' => 'La categoría debe ser texto',
            'category.max' => 'La categoría no puede exceder 150 caracteres',
            'description.required' => 'La descripción es obligatoria',
            'description.max' => 'La descripción no puede exceder 5000 caracteres',
            'price.required' => 'El precio es obligatorio',
            'price.numeric' => 'El precio debe ser un número válido',
            'price.min' => 'El precio no puede ser negativo',
            'price.max' => 'El precio no puede exceder $999,999.99',
            'stock.required' => 'La cantidad en stock es obligatoria',
            'stock.integer' => 'El stock debe ser un número entero',
            'stock.min' => 'El stock no puede ser negativo',
            'entrepreneur_id.prohibited' => 'El ID del emprendedor se asigna automáticamente y no debe enviarse',
            'main_image.image' => 'El archivo principal debe ser una imagen',
            'main_image.max' => 'La imagen principal no debe superar los 2MB',
            'status.in' => 'El estado debe ser: active, inactive o pending',
            'discount_percentage.numeric' => 'El descuento debe ser un número válido',
            'discount_percentage.min' => 'El descuento no puede ser negativo',
            'discount_percentage.max' => 'El descuento no puede exceder 100%',
            'gallery_images.array' => 'Las imágenes de galería deben ser un array',
            'gallery_images.max' => 'No se pueden subir más de 10 imágenes',
            'gallery_images.*.image' => 'Cada elemento de la galería debe ser una imagen',
            'gallery_images.*.max' => 'Cada imagen de la galería no debe superar los 2MB'
        ];
    }

    /**
     * Normaliza entradas antes de validar (mapear 'categoria' -> 'category').
     */
    protected function prepareForValidation(): void
    {
        $category = $this->input('category');
        if ($category === null) {
            $category = $this->input('categoria');
        }
        if (is_string($category)) {
            $category = trim($category);
        }
        if ($category !== null && $category !== '') {
            $this->merge([
                'category' => $category,
            ]);
        }
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
