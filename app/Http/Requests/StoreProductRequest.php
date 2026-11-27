<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    // Autoriza la ejecución de esta solicitud
    public function authorize(): bool
    {
        return true;
    }

    // Define las reglas de validación para registrar productos
    public function rules(): array
    {
        return [
            // El nombre es obligatorio y admite hasta 150 caracteres
            'name' => 'required|string|max:150',

            // La descripción es opcional y debe ser texto
            'description' => 'nullable|string|max:1000',

            // El precio es obligatorio y debe ser numérico y mayor que cero
            'price' => 'required|numeric|gt:0',

            // El stock es obligatorio y debe ser un número entero igual o mayor que cero
            'stock' => 'required|integer|min:0',

            // El estado es obligatorio y debe ser verdadero o falso
            'status' => 'required|boolean',
        ];
    }

    // Mensajes personalizados para los errores de validación
    public function messages(): array
    {
        return [
            'name.required' => 'El nombre del producto es obligatorio.',
            'name.max' => 'El nombre no puede superar los 150 caracteres.',
            'description.string' => 'La descripción debe ser un texto.',
            'price.required' => 'El precio del producto es obligatorio.',
            'price.numeric' => 'El precio debe ser un valor numérico.',
            'price.gt' => 'El precio debe ser mayor que cero.',
            'stock.required' => 'El stock es obligatorio.',
            'stock.integer' => 'El stock debe ser un número entero.',
            'stock.min' => 'El stock no puede ser negativo.',
            'status.required' => 'El estado del producto es obligatorio.',
            'status.boolean' => 'El estado debe ser verdadero o falso.',
            'description.max' => 'La descripción no puede superar los 1000 caracteres.',
        ];
    }
}
