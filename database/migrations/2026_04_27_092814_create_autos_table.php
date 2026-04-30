<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->id();

            // Relación con el usuario dueño
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Datos del vehículo
            $table->string('brand');
            $table->string('model');
            $table->year('year');
            $table->integer('price');
            $table->integer('mileage');
            $table->string('transmission');
            $table->string('fuelType');
            $table->string('color');

            // Ubicación y teléfono de contacto
            $table->string('location');
            $table->string('phone');

            // Descripción
            $table->text('description');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
