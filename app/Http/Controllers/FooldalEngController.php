<?php

namespace App\Http\Controllers;

use App\Models\FooldalKepek;

class FooldalEngController extends Controller
{
    public function index()
    {
        $fooldal_kepek=FooldalKepek::orderBY('kep_pozicio', 'asc')->get();
        return view('fooldal_eng')->with("kepek",$fooldal_kepek);

    }
}
