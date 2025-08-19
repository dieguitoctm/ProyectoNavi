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
        Schema::create('menores', function (Blueprint $table) {
            $table->id();
            $table->foreignid('usuario_id')->constrained('datos_usuarios')->onDelete('cascade');
            $table->string('nombres');
            $table->string('ap_paterno');
            $table->string('ap_materno');
            $table->string('rut')->unique();
            $table->date('fecha_nacimiento');
            $table->string('genero');
            $table->integer('edad');
            $table->string('carnet_control_sano');
            $table->string('certificado_nacimiento');
            $table->timestamps();

           
          
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menores');
    }
};
