<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $blueprint) {
            // Campos básicos y de contacto
            $blueprint->string('phone')->nullable();
            $blueprint->string('role')->default('user'); // user o admin
            $blueprint->boolean('verified')->default(false);
            $blueprint->integer('totalCars')->default(0);
            
            // Campos de perfil avanzado
            $blueprint->string('location')->nullable();
            $blueprint->text('bio')->nullable();
            $blueprint->string('curp', 18)->nullable();
            $blueprint->string('rfc', 13)->nullable();
            $blueprint->string('domicile')->nullable();
            $blueprint->string('ine')->nullable();

            // Token personalizado para tu sistema de Auth
            $blueprint->string('api_token', 80)->unique()->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone', 'role', 'verified', 'totalCars', 
                'location', 'bio', 'curp', 'rfc', 
                'domicile', 'ine', 'api_token'
            ]);
        });
    }
};
