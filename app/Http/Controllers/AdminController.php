<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Arak;
use App\Models\User;
use Nette\Utils\Image;
use App\Models\UserData;
use App\Models\FooldalKepek;
use Illuminate\Http\Request;
use App\Models\FoglalasiTabla;
use App\Models\ProgramokTabla;
use App\Models\Vendegkonyv;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function foglalasok()
    {
        $foglalasok = FoglalasiTabla::all();
        return view('foglalasok')->with('foglalasok', $foglalasok);
    }

    public function export(Request $request)
    {
        $headers = [
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',   'Content-type'        => 'text/csv; charset:UTF-8',   'Content-Disposition' => 'attachment; filename=foglalasok.csv',   'Expires'             => '0',   'Pragma'              => 'public',   'Content-Encoding' => 'UTF-8',
            'delimiter'              => ';',
            'enclosure'              => '"',
            'line_ending'            => PHP_EOL,
            'use_bom'                => true,
            'include_separator_line' => false,
            'excel_compatibility'    => false,
        ];

        $list = FoglalasiTabla::all()->toArray();

        # add headers for each column in the CSV download
        array_unshift($list, array_keys($list[0]));

        $callback = function () use ($list) {
            $FH = fopen('php://output', 'w');
            foreach ($list as $row) {
                mb_convert_encoding($row, 'UTF-16LE', 'UTF-8');
                fputcsv($FH, $row);
            }
            fclose($FH);
        };


        return response()->stream($callback, 200, $headers);
    }

    public function foglalasok_modositas(Request $request)
    {

        $toChange = $request->all();

        if ($request["submit"] == "delete") {

            FoglalasiTabla::where('foglalas_id', $toChange["foglalas_id"])->delete();
            $foglalasok = FoglalasiTabla::all();

        } else {

            $validator = Validator::make($request->all(), [
                "nev" => "required",
                "mail" => "required|email",
                "telefon" => array("required", "regex:/\+[1-9]{1}[0-9]{6,14}$/"),
                "cim" => "required",
                "start" => "required|date",
                "end" => "required|date",
                "db" => "required|integer|between:1,14"
            ], [
                "nev.required" => "A név megadása kötelező",
                "mail.required" => "Az E-mail cím megadása kötelező",
                "mail.email" => "Az email cím formátuma nem megfelelő",
                "cim.required" => "A cím mező kötelező",
                "telefon.required" => "A telefonszám megadása kötelező, kapcsolattartás céljából",
                "telefon.regex" => "A telefonszám formátuma nem megfelelő. Formátum: +36201234567",
                "start.required" => "A kezdődátum kiválasztása kötelező",
                "start.date" => "A kezdődátumnak dátum formátumúnak kell lennie",
                "end.required" => "A végdátum kiválasztása kötelező",
                "end.date" => "A végdátumnak dátum formátumúnak kell lennie",
                "db.required" => "A vendégek számának megadása kötelező",
                "db.integer" => "A vendégek számának számnak kell legyen",
                "db.between" => "A vendégek száma 1 és 14 között kell legyen"

            ]);

            if ($validator->fails()) {
                return Redirect::back()->withErrors($validator);
            }
            
            $idToChange = array_key_last($toChange);

            $statusToChange = end($toChange);
            $foglalas = FoglalasiTabla::where('foglalas_id', $idToChange)->update([
                'foglalo_nev' => $toChange['nev'],
                'foglalo_cim' => $toChange['cim'],
                'foglalo_telefon' => $toChange['telefon'],
                'foglalo_mail' => $toChange['mail'],
                'foglalas_start' => $toChange['start'],
                'foglalas_end' => $toChange['end'],
                'foglalas_db' => $toChange['db'],
                'foglalas_megj' => $toChange['megj'],
                'foglalas_status' => $statusToChange
            ]);

            $foglalasok = FoglalasiTabla::all();
        }

        return view('foglalasok')->with('foglalasok', $foglalasok);
    }

    public function felhasznalok()
    {
        $query_result = DB::table('users')->join('user_data', 'users.email', '=', 'user_data.email')->get();
        $felhasznalok = json_decode($query_result, true);
        return view('felhasznalok')->with('felhasznalok', $felhasznalok);
    }

    public function felhasznalupdate(Request $request)
    {


        $validator = Validator::make($request->all(), [
            "nev" => "required",
            "email" => "required|email",
            "telefon" => array("nullable", "regex:/\+[1-9]{1}[0-9]{6,14}$/"),
        ], [
            "nev.required" => "A név megadása kötelező",
            "email.required" => "Az E-mail cím megadása kötelező",
            "email.email" => "Az email cím formátuma nem megfelelő",
            "telefon.regex" => "A telefonszám formátuma nem megfelelő. Formátum: +36201234567",
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        }

        $toChange = $request->all();


        if ($request["submit"] == "delete") {

            DB::table("users")->where('id', $toChange["id"])->delete();
            DB::table("user_data")->where('email', $toChange["email"])->delete();
        } else {

            $felhtoupdate = DB::table("users")->where('id', $toChange["id"])->get();
            DB::table("foglalasi_tabla")->join('users', 'email', '=', 'foglalo_mail')->where("foglalo_mail", $felhtoupdate[0]->email)->update([
                "foglalo_nev" => $toChange['nev'],
                "foglalo_mail" => $toChange['email']
            ]);

            DB::table('users')->join('user_data', 'users.email', '=', 'user_data.email')->where('users.id', $toChange['id'])->update([
                'users.name' => $toChange['nev'],
                'users.email' => $toChange['email'],
                'user_data.email' => $toChange['email'],
                'telefon' => $toChange['telefon'],
                'cim' => $toChange['cim']
            ]);
        }
        return Redirect::back()->withSuccess("Az adatok sikeresen módosítva.");
    }

    public function adminadatmodositas()
    {
        $query_result = DB::table('users')->join('user_data', 'users.email', '=', 'user_data.email')->where('users.email', '=', Auth::user()->email)->get();
        $felhasznalo = json_decode($query_result, true);
        return view('admin_adatmodositas')->with('felhasznalo', array_values($felhasznalo)[0]);
    }

    public function adminadatupdate(Request $request)
    {

        $validator = Validator::make($request->all(), [
            "nev" => "required",
            "email" => "required|email",
            "telefon" => array("nullable", "regex:/\+[1-9]{1}[0-9]{6,14}$/"),
        ], [
            "nev.required" => "A név megadása kötelező",
            "email.required" => "Az E-mail cím megadása kötelező",
            "email.email" => "Az email cím formátuma nem megfelelő",
            "telefon.regex" => "A telefonszám formátuma nem megfelelő. Formátum: +36201234567",
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
        return Redirect::back()->withSuccess("Az adatok sikeresen módosítva.");
    }

    public function arShow()
    {
        $ar = Arak::get();
        return view("admin_armodositas")->with("jelenar", $ar);
    }

    public function arakupdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "ar" => "integer",
            "sz1" => "integer|between:0,100",
            "sz2" => "integer|between:0,100"
        ], [
            "ar.integer" => "Az árnak számnak kell legyen.",
            "sz1.integer" => "Az kedvezmény1-nek számnak kell legyen.",
            "sz2.integer" => "Az kedvezmény2-nek számnak kell legyen.",
            "sz1.between" => "A kedvezmény1-nek 0 és 100 között kell legyen",
            "sz2.between" => "A kedvezmény2-nek 0 és 100 között kell legyen"
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withInput()->withErrors($validator);
        }

        $toChange = $request->all();

        DB::table('arak')->where('id', '=', '1')->update(
            [
                'jelen' => $toChange['ar'],
                'szazalek1' => $toChange['sz1'],
                'szazalek2' => $toChange['sz2'],
            ]
        );
        $ar = Arak::get();
        return view("admin_armodositas")->with("jelenar", $ar);
    }


    public function saveEditPages(Request $request)
    {
        $oldalak = array();

        $oldalak = ["arak.blade", "fooldal.blade", "kapcsolat.blade", "hazirend.blade", "adatvedelem.blade"];

        $prevShow = 0;

        if (File::get(base_path() . "/resources/views/current_mod") == "nothing") {
            $prevShow = null;
        } else {
            $prevShow = File::get(base_path() . "/resources/views/current_mod");
        }

        if ($prevShow == null) {
            $content = File::get(base_path() . "/resources/views/" . $oldalak[0] . ".php");
            File::replace(base_path() . "/resources/views/" . $oldalak[0] . ".php", $content);
            File::replace(base_path() . "/resources/views/current_mod", $oldalak[0]);
        } else {

            if ($request["content"] == null) {
            } else {
                File::copy((base_path() . "/resources/views/" . $prevShow . ".php"), (base_path() . "/resources/views/backup/" . $prevShow . Carbon::now()->format('YmdHis') . ".php"));
                File::replace(base_path() . "/resources/views/" . $prevShow . ".php", $request["content"]);
            }
        }

        $aktShow = $request->get("aktShow");

        $content = 0;

        if ($aktShow == null) {
            $content = File::get(base_path() . "/resources/views/" . $oldalak[0] . ".php");
            $prevShow = $oldalak[0];
            File::replace(base_path() . "/resources/views/current_mod", $oldalak[0]);
        } else {
            $content = File::get(base_path() . "/resources/views/" . $aktShow . ".php");
            $prevShow = $aktShow;
            File::replace(base_path() . "/resources/views/current_mod", $aktShow);
        }
        return view("oldalakSzerkesztese")->with("content", $content)->with("oldalak", $oldalak)->with("prevShow", $aktShow);
    }


    public function kepfeltoltes_get()
    {
        return view("kepfeltoltes");
    }

    public function kepfeltoltes(Request $request)
    {
        if ($request["radio_group"] == "kep") {
            $validator = Validator::make($request->all(), [
                "kep" => "required|mimes:jpg|max:10240",
                "kep_nev" => "required"
            ], [
                "kep.max" => "A kép kisebb kell legyen mint 10Mb",
                "kep.mimes" => "A kép JPG formátumban kell legyen",
                "kep.required" => "A kép kötelező.",
                "kep_nev.required" => "A kép nevének megadása kötelező"
            ]);

            if ($validator->fails()) {
                return Redirect::back()->withInput()->withErrors($validator);
            }
            $request->file('kep')->move(public_path() . "/kepek/", $request["kep_nev"] . ".jpg");
            File::copy(public_path() . "/kepek/" . $request["kep_nev"] . ".jpg", public_path() . "/kepek/indexkepek/" . $request["kep_nev"] . ".jpg");

            $db = FooldalKepek::all()->count();
            $uj_kep = new FooldalKepek();

            $uj_kep->indexkep_path = "kepek/indexkepek/" . $request["kep_nev"] . ".jpg";
            $uj_kep->fullkep_path = "kepek/" . $request["kep_nev"] . ".jpg";
            $uj_kep->kep_nev = $request["kep_nev"];
            $uj_kep->kep_pozicio = $db + 1;
            $uj_kep->save();
        } else {
            $validator = Validator::make($request->all(), [
                "kep" => "required|mimes:jpg|max:10240",
                "kep_nev" => "required",
                "program_link" => "required",
                "program_kategoria" => "required"
            ], [
                "kep.max" => "A kép kisebb kell legyen mint 10Mb",
                "kep.mimes" => "A kép JPG formátumban kell legyen",
                "kep.required" => "A kép kötelező.",
                "kep_nev.required" => "A program nevének megadása kötelező",
                "program_kategoria.required" => "A program kategóriája kötelező",
                "program_link.required" => "A program linkje kötelező",
            ]);

            if ($validator->fails()) {
                return Redirect::back()->withInput()->withErrors($validator);
            }
            $request->file('kep')->move(public_path() . "/kepek/programkepek/", $request["kep_nev"] . ".jpg");

            $uj_program = new ProgramokTabla();
            $uj_program->kep_path = "kepek/programkepek/" . $request["kep_nev"] . ".jpg";
            $uj_program->program_leiras = $request["kep_nev"];
            $uj_program->program_link = $request["program_link"];
            $uj_program->kategoria = $request["program_kategoria"];
            $uj_program->save();
        }

        return Redirect::back()->withErrors(['msg' => 'A feltöltés sikeres.']);
    }

    public function kepekListazasa()
    {
        $kepek = FooldalKepek::orderBy("kep_pozicio")->get();
        return view("kepek_szerkesztese")->with('kepek', $kepek);
    }

    public function kepekSzerkesztese(Request $request)
    {
        $kepekv = FooldalKepek::all();
        $kepekdbv = count($kepekv);
        $validator = Validator::make($request->all(), [
            "poz" => "required|integer|min:1|max:{$kepekdbv}",
        ], [
            "poz.min" => "A kép pozíciója minimum 1 kell legyen",
            "poz.max" => "A kép pozíciója maximum {$kepekdbv} kell legyen",
            "poz.required" => "A kép pozíciója kötelezően megadandó adat."
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withInput()->withErrors($validator);
        }

        if ($request["poz"] == null) {
            $eddigipoz = FooldalKepek::where("modal_id", $request["id"])->get();
            $request["poz"] = $eddigipoz[0]["kep_pozicio"];
        }
        if ($request["nev"] == null) {
            $eddiginev = FooldalKepek::where("modal_id", $request["id"])->get();
            $request["nev"] = $eddiginev[0]["kep_pozicio"];
        }
        if ($request["submit"] == "delete") {
            $kepek = FooldalKepek::all();
            $kepekdb = count($kepek);
            $kep = FooldalKepek::where("modal_id", $request["id"])->get();
            File::delete(public_path() . "/" . $kep[0]["fullkep_path"]);
            File::delete(public_path() . "/" . $kep[0]["indexkep_path"]);
            FooldalKepek::where("modal_id", $request["id"])->delete();



            for ($i = $request["poz"]; $i < $kepekdb; $i++) {
                FooldalKepek::where("kep_pozicio", ($i + 1))->update([
                    'kep_pozicio' => $i,
                ]);
            }
        } else {
            $eddigipoz = FooldalKepek::where("modal_id", $request["id"])->get();
            if ($request["poz"] < $eddigipoz[0]["kep_pozicio"]) {
                FooldalKepek::where("modal_id", $request["id"])->update([
                    'kep_nev' => $request["nev"],
                    'kep_pozicio' => 0
                ]);
                for ($i = $eddigipoz[0]["kep_pozicio"]; $i > $request["poz"]; $i--) {

                    FooldalKepek::where("kep_pozicio", ($i - 1))->update([
                        'kep_pozicio' => $i,
                    ]);
                }
                FooldalKepek::where("modal_id", $request["id"])->update([
                    'kep_nev' => $request["nev"],
                    'kep_pozicio' => $request["poz"]
                ]);
            } else if ($request["poz"] > $eddigipoz[0]["kep_pozicio"]) {

                FooldalKepek::where("modal_id", $request["id"])->update([
                    'kep_nev' => $request["nev"],
                    'kep_pozicio' => 0
                ]);
                for ($i = $eddigipoz[0]["kep_pozicio"]; $i < $request["poz"]; $i++) {
                    FooldalKepek::where("kep_pozicio", ($i + 1))->update([
                        'kep_pozicio' => $i,
                    ]);
                }
                FooldalKepek::where("modal_id", $request["id"])->update([
                    'kep_nev' => $request["nev"],
                    'kep_pozicio' => $request["poz"]
                ]);
            }

            FooldalKepek::where("modal_id", $request["id"])->update([
                'kep_nev' => $request["nev"],
                'kep_pozicio' => $request["poz"]
            ]);
        }

        $kepek = FooldalKepek::orderBy("kep_pozicio")->get();
        return view("kepek_szerkesztese")->with('kepek', $kepek);
    }

    public function programokListazasa()
    {
        $programok = ProgramokTabla::all();
        return view('programok_szerkesztese')->with('programok', $programok);
    }

    public function programokSzerkesztese(Request $request)
    {

        $validator = Validator::make($request->all(), [
            "link" => "required",
        ], [
            "link.required" => "A program linkje kötelezően megadandó adat."
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withInput()->withErrors($validator);
        }

        if ($request["leiras"] == null) {
            $program = ProgramokTabla::where("program_id", $request["button"])->get();
            File::delete(public_path() . "/" . $program[0]["kep_path"]);
            ProgramokTabla::where("program_id", $request["button"])->delete();
        } else {
            ProgramokTabla::where("program_id", $request["button"])->update([
                'program_leiras' => $request["leiras"],
                'program_link' => $request["link"],
                'kategoria' => $request["kategoria"]
            ]);
        }

        return Redirect::back()->withErrors(['msg' => 'A Módosítás sikeres']);
    }

    public function vendegkonyv()
    {
        $vendegkonyv = json_decode(DB::table('vendegkonyv')->join('users', 'uid', '=', 'users.id')->get(), true);
        return view("admin_vendegkonyv")->with("konyv", $vendegkonyv);
    }
}
