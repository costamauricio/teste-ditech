<?php

namespace App\Models;

use \App\Models\Model;

class Reserva extends Model
{
    protected $table = "reservas";

    public function usuario()
    {
        return $this->belongsTo("App\Models\Usuario")->first();
    }

    public function sala()
    {
        return $this->belongsTo("App\Models\Sala")->first();
    }
}
