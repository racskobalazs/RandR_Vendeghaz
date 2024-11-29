<?php

namespace App\Http\Controllers;

use App\Models\Arak;
use Illuminate\Support\Facades\File;

class ArakEngController extends Controller
{
    public function index()
    {
        $ar=Arak::get();

        $file_content=File::get(base_path()."/resources/views/arak.blade.php");

        $arak=array();

        $arak['foszezon']=$ar[0]["jelen"];
        $arak['eloszezon']=(int)$ar[0]["jelen"]*$ar[0]["szazalek1"]/100;
        $arak['utoszezon']=(int)$ar[0]["jelen"]*$ar[0]["szazalek1"]/100;
        $arak['szezononkivul']=(int)$ar[0]["jelen"]*$ar[0]["szazalek2"]/100;

        return view('arak_eng')->with('arak',$arak);
    }
}
