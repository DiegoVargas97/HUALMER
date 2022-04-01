<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comercio;

class ComercioController extends Controller
{
    
    public function create(Request $res){

        $validated = $res->validate([
            'nombre' => 'required',
            'rut' => 'required',
            'email' => 'required'
        ]);

        $com = new Comercio;

            $com->name = $res->nombre;
            $com->rut = $res->rut;
            $com->email = $res->email;

            $com->save();

            return response($com,201);
        
    }

    public function deactvate(){
        
    }
}
