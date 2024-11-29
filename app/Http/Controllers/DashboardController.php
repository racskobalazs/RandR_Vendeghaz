<?php

namespace App\Http\Controllers;

use LDAP\Result;
use App\Models\User;
use App\Models\UserData;
use Illuminate\Http\Request;
use App\Models\FoglalasiTabla;
use App\Models\Vendegkonyv;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\ViewErrorBag;
use Illuminate\Support\Facades\Validator;

class DashboardController extends Controller
{

    public function myprofile()
    {
        $foglalasok = FoglalasiTabla::where("foglalo_mail", Auth::user()->email)->get();
        return view('myprofile')->with('foglalasok', $foglalasok);
    }

    public function adatmodositas()
    {
        $query_result = DB::table('users')->join('user_data', 'users.email', '=', 'user_data.email')->where('users.email', '=', Auth::user()->email)->get();
        $felhasznalo = json_decode($query_result, true);
        return view('adatmodositas')->with('felhasznalo', array_values($felhasznalo)[0]);
    }

    public function adatupdate(Request $request)
    {

        $validator = Validator::make($request->all(), [
            "nev" => "required",
            "email"=>"required|email",
            "telefon"=>array("nullable","regex:/\+[1-9]{1}[0-9]{6,14}$/"),
        ], [
            "nev.required"=>"A név megadása kötelező",
            "email.required"=>"Az E-mail cím megadása kötelező",
            "mail.email"=>"Az email cím formátuma nem megfelelő",
            "telefon.regex"=>"A telefonszám formátuma nem megfelelő. Formátum: +36201234567",
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        }

        $toChange = $request->all();

        FoglalasiTabla::where('foglalo_mail', Auth::user()->email)->update([
            'foglalo_mail' => $toChange['email'],
        ]);
        UserData::where('email', Auth::user()->email)->update([
            'email' => $toChange['email'],
            'telefon' => $toChange['telefon'],
            'cim' => $toChange['cim']
        ]);
        User::where('id', Auth::user()->id)->update([
            'name' => $toChange['nev'],
            'email' => $toChange['email'],
        ]);
        

        $query_result = DB::table('users')->join('user_data', 'users.email', '=', 'user_data.email')->where('users.email', '=', Auth::user()->email)->get();
        $felhasznalo = json_decode($query_result, true);
        return Redirect::back();
    }

    public function vendegkonyv()
    {
        return view("vendegkonyv");
    }

    public function vendegkonyvsave(Request $request)
    {
        if ($request["uzenet"] == null) {
            return Redirect::back()->withErrors(['msg' => 'Az üzenet mező nem lehet üres.']);
        }
        $ujUzenet = new Vendegkonyv();
        $ujUzenet->uid = Auth::user()->id;
        $ujUzenet->uzenet = $request["uzenet"];
        $ujUzenet->mikor = Carbon::now();
        $ujUzenet->save();
        return Redirect::back()->withSuccess("Az üzenetét megkaptuk, köszönjük.");
    }
}
