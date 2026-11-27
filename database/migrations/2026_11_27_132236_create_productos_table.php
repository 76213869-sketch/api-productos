<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {

            // Identificador único del producto
            $table->id();

            // Nombre del producto
            $table->string('name', 150);

            // Descripción del producto (campo opcional)
            $table->text('description')->nullable();

            // Precio del producto con dos decimales
            $table->decimal('price', 10, 2);

            // Cantidad disponible en inventario
            $table->integer('stock')->default(0);

            // Estado del producto: activo o inactivo
            $table->boolean('status')->default(true);

            // Fecha de creación y actualización
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
