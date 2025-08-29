<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\Embarazada;
use App\Models\Menor;

class DatosUsuario extends Model
{
    use HasFactory;

    protected $table = 'datos_usuarios';

    protected $fillable = [
        'nombres',
        'ap_paterno',
        'ap_materno',
        'telefono',
        'direccion',
        'rut',
        'registro_social',
        'hash_id'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->hash_id)) {
                $model->hash_id = Str::random(32);
            }
        });
    }

    // Relaciones
    public function embarazada()
    {
        return $this->hasOne(Embarazada::class, 'usuario_id');
    }

    public function menores()
    {
        return $this->hasMany(Menor::class, 'usuario_id');
    }
    
}
