<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    // Autoriza la ejecución de esta solicitud
    public function authorize(): bool
    {
        return true;
    }

    // Define las reglas de validación para actualizar productos
    public function rules(): array
    {
        return [
            // Todos los campos son opcionales al actualizar
            'name' => 'sometimes|required|string|max:150',
            'description' => 'sometimes|nullable|string',
            'price' => 'sometimes|required|numeric|gt:0',
            'stock' => 'sometimes|required|integer|min:0',
            'status' => 'sometimes|required|boolean',
        ];
    }

    // Mensajes personalizados
    public function messages(): array
    {
        return [
            'name.required' => 'El nombre del producto es obligatorio.',
            'name.max' => 'El nombre no puede superar los 150 caracteres.',
            'description.string' => 'La descripción debe ser un texto.',
            'price.required' => 'El precio es obligatorio.',
            'price.numeric' => 'El precio debe ser numérico.',
            'price.gt' => 'El precio debe ser mayor que cero.',
            'stock.required' => 'El stock es obligatorio.',
            'stock.integer' => 'El stock debe ser un número entero.',
            'stock.min' => 'El stock no puede ser negativo.',
            'status.required' => 'El estado es obligatorio.',
            'status.boolean' => 'El estado debe ser verdadero o falso.',
        ];
    }
}
