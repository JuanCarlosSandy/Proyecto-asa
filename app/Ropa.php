<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ropa extends Model
{
    protected $fillable =[
        'nombre_ropa','cantidad','sexo','idTalla','idEstacion'
    ];

    public function categoria(){
        return $this->belongsTo('App\CategoriaRopa');
    }
    
    public function tallas(){
        return $this->belongsTo('App\TallaRopa');
    }
}