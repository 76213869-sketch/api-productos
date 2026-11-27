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
        Schema::table('productos', function (Blueprint $table) {

            // Nombre del producto
            $table->string('name', 150)->after('id');

            // Descripción opcional
            $table->text('description')->nullable()->after('name');

            // Precio del producto
            $table->decimal('price', 10, 2)->after('description');

            // Cantidad disponible en inventario
            $table->integer('stock')->default(0)->after('price');

            // Estado: activo o inactivo
            $table->boolean('status')->default(true)->after('stock');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->dropColumn([
                'name',
                'description',
                'price',
                'stock',
                'status'
            ]);
        });
    }
};
