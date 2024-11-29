<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\UserData;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use App\Models\FoglalasiTabla;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Database\Eloquent\Collection;


class FoglalasController extends Controller
{
    public function index()
    {
        $year = array();

        $honapok_nevei=["Január","Február","Március","Április","Május","Június","Július","Augusztus","Szeptember","Október","November","December"];

        $honapok_return=array();
        $j=0;

        $today = Carbon::now();

        for ($i = $today->month; $i <= 12; $i++) {
            $dt = Carbon::createFromDate($today->year, $i, 01);
            $year[$j] = $this->calendar($dt);
            $honapok_return[$j]=$today->year." ".$honapok_nevei[$i-1];
            $j++;
        }

        for ($i = 1; $i < $today->month; $i++) {
            $dt = Carbon::createFromDate(($today->year + 1), $i, 01);
            $year[$j] = $this->calendar($dt);
            $honapok_return[$j]=($today->year+1)." ".$honapok_nevei[$i-1];
            $j++;
        }

        $user=0;
        if(Auth::check())
        {
            $user=UserData::where("email",Auth::user()->email)->get();
        }

        return view('foglalas')->with("year", $year)->with("honapok", $honapok_return)->with("user",$user);
    }

    public function sendmail(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'email'=>'required|email',
            'vnev'=>'required',
            'knev'=>'required',
            'telefon'=>'required',
            'cim'=>'required',
            'erkezes'=>'required|date',
            'tavozas'=>'required|date',
            'foglalodb'=>'required|integer|between:1,14',
            'foglalasi_feltetelekcb' => 'required',
            'hazirendcb' => 'required',
            'adatkezelescb' => 'required'
        ],[
            "vnev.required"=>"A vezetéknév kitöltése kötelező!",
            "knev.required"=>"A keresztnév kitöltése kötelező!",
            "telefon.required"=>"A telefonszám megadása kötelező, kapcsolattartás céljából.",
            "cim.required"=>"A cím kitöltése kötelező!",
            "erkezes.required"=>"Az érkezési dátum megadása  kötelező!",
            "tavozas.required"=>"A távozási dátum megadása kötelező!",
            "tavozas.date"=>"A távozási dátum nem dátumformátum",
            "erkezes.date"=>"A érkezési dátum nem dátumformátum",
            'email.email'=>"Adjon meg megfelelő formátumú email címet.",
            "foglalodb.integer"=>"Kérem számot adjon meg.",
            "foglalodb.between"=>"Kérem 1 és 14 közötti számot adjon meg az érkező személyek számának.",
            'foglalasi_feltetelekcb.required'=>"A foglalási feltételek elfogadása kötelező",
            'hazirendcb.required'=>"A Házirend elfogadása kötelező",
            'adatkezelescb.required'=>"Az Adatkezelés elfogadása kötelező"
        ]);

        if($validator->fails())
        {
            
            if(Auth::check())
            {
                $user=UserData::where("email",Auth::user()->email)->get();
                return Redirect::back()->with("user",$user)->withInput()->withErrors($validator);
            }
            else
            {
                return Redirect::back()->withInput()->withErrors($validator);
            }

        }

        $foglalasi_szandek = CarbonPeriod::create($request->erkezes, $request->tavozas);
        $foglalasok=FoglalasiTabla::all();
        $hiba=false;
        foreach($foglalasi_szandek as $date)
        {
            $akt_datum=collect(["datum" => $date->format('Y-m-d')]);
            foreach($foglalasok as $foglalas)
            {
                if($foglalas["foglalas_status"]=="elutasítva")
                {
                    continue;
                }
                else
                {
                $akt_db_foglalas=CarbonPeriod::create($foglalas["foglalas_start"], $foglalas["foglalas_end"]);
                if($request->tavozas==$foglalas["foglalas_start"] || $request->erkezes==$foglalas["foglalas_end"] )
                {
                    continue;
                }
                else
                if($akt_db_foglalas->contains($akt_datum["datum"]))
                {
                    $hiba=true;

                }
            }
            }
        }


        if($hiba)
        {
            if(Auth::check())
            {
                $user=UserData::where("email",Auth::user()->email)->get();
                return Redirect::back()->with("user",$user)->withInput()->withErrors("A választott dátum nem megfelelő.");
            }
            else
            {
                return Redirect::back()->withInput()->withErrors("A választott dátum nem megfelelő.");
            }
        }

        

        $data = array(
            'nev' => $request->vnev . " " . $request->knev,
            'email' => $request->email,
            'telefon' => $request->telefon,
            'cim' => $request->cim,
            'erkezes' => $request->erkezes,
            'tavozas' => $request->tavozas,
            'megjegyzes' => $request->megjegyzes,
            'foglalodb' => $request->foglalodb
        );

        Mail::send('emails.foglalas', $data, function ($message) use ($data) {
            $message->from($data['email']);
            $message->to('info@randrvendeghaz.hu');
            $message->subject('Új bejövő foglalás');
        });

        Mail::send('emails.foglalas_kifele', $data, function ($message) use ($data) {
            $message->from($address = 'info@randrvendeghaz.hu', $name = "R&R Vendégház");
            $message->to($data['email']);
            $message->subject('Foglalás rögzítés ' . $data['nev'] . ' számára');
        });

        $uj_foglalas = new FoglalasiTabla();

        $uj_foglalas['foglalo_nev'] = $request->vnev . " " . $request->knev;
        $uj_foglalas['foglalo_cim'] = $request->cim;
        $uj_foglalas['foglalo_telefon'] = $request->telefon;
        $uj_foglalas['foglalo_mail'] = $request->email;
        $uj_foglalas['foglalas_start'] = $request->erkezes;
        $uj_foglalas['foglalas_end'] = $request->tavozas;
        $uj_foglalas['foglalas_db'] = $request->foglalodb;
        $uj_foglalas['foglalas_megj'] = $request->megjegyzes;
        $uj_foglalas['foglalas_status'] = "elofoglalas";

        $uj_foglalas->save();

        return view("thankyou");
    
    }

    public function calendar($dt)
    {

        $foglalasok = FoglalasiTabla::all();
        $foglalas_and_type = "";
        $dates = array();
        $nem_foglalhato_coll = new Collection();
        $foglalt_coll = new Collection();
        $elso_napok = new Collection();
        $utolso_napok = new Collection();

        foreach ($foglalasok as $foglalas_sor) {
            if ($foglalas_sor["foglalas_status"] == "fizetve" || $foglalas_sor['foglalas_status'] == "elofoglalas") {
                $elso_napok->push(collect(["datum" => $foglalas_sor["foglalas_start"]]));
                $utolso_napok->push(collect(["datum" => $foglalas_sor["foglalas_end"]]));
            }
            $period = CarbonPeriod::create($foglalas_sor["foglalas_start"], $foglalas_sor["foglalas_end"]);
            foreach ($period as $date) {
                switch ($foglalas_sor["foglalas_status"]) {
                    case ("nem foglalhato"):
                        $nem_foglalhato_coll->push(collect(["datum" => $date->format('Y-m-d')]));
                        break;
                    case ("elofoglalas"):
                        $foglalt_coll->push(collect(["datum" => $date->format('Y-m-d')]));
                        break;
                    case ("elfogadva"):
                        $foglalt_coll->push(collect(["datum" => $date->format('Y-m-d')]));
                        break;
                    case ("fizetve"):
                        $foglalt_coll->push(collect(["datum" => $date->format('Y-m-d')]));
                        break;
                }
            }
        }

        // Make sure to start at the beginning of the month
        $dt->startOfMonth();

        // Set the headings (weeknumber + weekdays)
        $headings = ['H', 'K', 'Sze', 'Csü', 'Pé', 'Szo', 'Vas'];

        // Create the table
        $calendar = '<div class="table-responsive"><table class="table table-bordered">';
        //$calendar .= '<caption>'.$dt->format('F Y').'</caption>';
        $calendar .= '<tr>';

        // Create the calendar headings
        foreach ($headings as $heading) {
            $calendar .= '<th class="header text-center">' . $heading . '</th>';
        }

        // Create the rest of the calendar and insert the weeknumber
        $calendar .= '</tr><tr>';
        //$calendar .= '<td>'.$dt->weekOfYear.'</td>';

        // Day of week isn't monday, add empty preceding column(s)
        if ($dt->format('N') != 1) {
            $calendar .= '<td colspan="' . ($dt->format('N') - 1) . '">&nbsp;</td>';
        }

        // Get the total days in month
        $daysInMonth = $dt->daysInMonth;

        // Go over each day in the month
        for ($i = 1; $i <= $daysInMonth; $i++) {
            // Monday has been reached, start a new row
            if ($dt->format('N') == 1) {
                $calendar .= '</tr><tr>';
                //$calendar .= '<td>'.$dt->weekOfYear.'</td>';
            }
            // Append the column

            if ($nem_foglalhato_coll->contains("datum", $dt->format('Y-m-d'))) {
                $calendar .= '<td class="dayemp text-center" rel="' . $dt->format('Y-m-d')  . '">' . $dt->day . '</td>';
            } else if ($foglalt_coll->contains("datum", $dt->format('Y-m-d'))) {
                if ($elso_napok->contains("datum", $dt->format('Y-m-d')) && $utolso_napok->contains("datum", $dt->format('Y-m-d'))) {
                    $calendar .= '<td class="dayres text-center" rel="' . $dt->format('Y-m-d')  . '">' . $dt->day . '</td>';
                } else if ($elso_napok->contains("datum", $dt->format('Y-m-d'))) {
                    $calendar .= '<td class="dayhalfe text-center" rel="' . $dt->format('Y-m-d')  . '">' . $dt->day . '</td>';
                } else if ($utolso_napok->contains("datum", $dt->format('Y-m-d'))) {
                    $calendar .= '<td class="dayhalff text-center" rel="' . $dt->format('Y-m-d')  . '">' . $dt->day . '</td>';
                } else {
                    $calendar .= '<td class="dayres text-center" rel="' . $dt->format('Y-m-d')  . '">' . $dt->day . '</td>';
                }
            } else {
                $calendar .= '<td class="day text-center" rel="' . $dt->format('Y-m-d')  . '">' . $dt->day . '</td>';
            }

            // Increment the date with one day
            $dt->addDay();
        }

        // Last date isn't sunday, append empty column(s)
        if ($dt->format('N') != 7) {
            $calendar .= '<td colspan="' . (8 - $dt->format('N')) . '">&nbsp;</td>';
        }

        // Close table
        $calendar .= '</tr>';
        $calendar .= '</table></div>';

        // Return calendar html
        return $calendar;
    }
}
