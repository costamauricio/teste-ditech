<?php

namespace App\Controllers;

use \App\Controllers\Controller;
use \App\Models\Sala;

class SalaController extends Controller
{
    public function getSalas($request, $response)
    {
        return $response->withJson(Sala::all());
    }
}
