<?php

namespace App\Http\Controllers;

use App\Models\ProgramokTabla;

class ProgramokController extends Controller
{
    public function index()
    {
        $programok= ProgramokTabla::all();
        return view("programok")->with('programok',$programok);
    }
}
