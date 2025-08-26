<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('datos_usuarios', function (Blueprint $table) {
            // Campo para el hash público (32 caracteres)
            $table->string('hash_id', 64)->unique()->nullable()->after('id');
        });

        // Backfill: generar hash para filas existentes
        DB::table('datos_usuarios')->whereNull('hash_id')->orderBy('id')->chunkById(200, function ($rows) {
            foreach ($rows as $row) {
                DB::table('datos_usuarios')
                    ->where('id', $row->id)
                    ->update(['hash_id' => Str::random(32)]);
            }
        });

        // NOTA: dejamos la columna nullable por compatibilidad. Si quieres forzar NOT NULL después,
        // hazlo con otra migración e instala doctrine/dbal si tu DB lo requiere.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('datos_usuarios', function (Blueprint $table) {
            $table->dropColumn('hash_id');
        });
    }
};
