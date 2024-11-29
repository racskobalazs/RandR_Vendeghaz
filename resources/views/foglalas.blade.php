<x-base>


    <x-slot name="anchor">
        naptar
    </x-slot>

    <x-slot name="sectionHeading">
        Foglalási Naptár
    </x-slot>

    <x-slot name="extracss">
        <link href="css/foglalas.css" rel="stylesheet" />
        <link href="css/jquery-ui.css" rel="stylesheet" />
    </x-slot>

    <x-slot name="content">

        <div class="row" id="calendar">

            @foreach ($year as $month)
            <div class="col-xs-12 col-md-6 col-lg-4">
                <h2 class="sub-header text-center">{{ $honapok[$loop->index] }}</h2>
                <div class="table-responsive">
                    <?php
                    echo $month;
                    ?>
                </div>
            </div>
            @endforeach

        </div>

        <div class="center">
            <h4>Foglalási szándékát az alábbi űrlap kitöltésével jelezheti: </h4>
            <p></p>
            <h6>A foglalás nem automatikus, így mindenképpen várja meg a kapcsolatfelvételt!</h6>
        </div>

        @if ($errors->any())
        @foreach ($errors->all() as $hiba)
        <h6 style="color: red">{{ $hiba }}</h6>
        @endforeach
        @endif



        <div class="container" id="foglalasi_form">
            <form method="POST" autocomplete="off" action="{{ url('foglalas') }}">
                {{ csrf_field() }}

                <div class="row form-group">

                    <div class="col">
                        @if (Auth::check())
                        <input type="text" required name="vnev" class="form-control" placeholder="Vezetéknév" value="{{ explode(' ', Auth::user()->name, 2)[0] }}">
                    </div>
                    <div class="col">
                        <input type="text" required name="knev" class="form-control" placeholder="Keresztnév" value="{{ explode(' ', Auth::user()->name, 2)[1] }}">
                    </div>
                    @endif
                    @if (!Auth::check())
                    <input type="text" required name="vnev" class="form-control" placeholder="Vezetéknév" value="{{ old("vnev") }}">
                </div>
                <div class="col">
                    <input type="text" required name="knev" class="form-control" placeholder="Keresztnév" value="{{ old("knev") }}">
                </div>
                @endif
        </div>
        </br>
        <div class="row form-group">
            <div class="col">
                @if (Auth::check())
                <input type="text" required name="email" class="form-control" placeholder="E-mail cím (minta@email.hu)" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" title="E-mail cím a következő formátumban: minta@email.hu" value="{{ Auth::user()->email }}">
                @endif

                @if (!Auth::check())
                <input type="text" required name="email" class="form-control" placeholder="E-mail cím (minta@email.hu)" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" title="E-mail cím a következő formátumban: minta@email.hu" value=" {{ old("email") }}">
                @endif

            </div>

            <div class="col">

                @if (Auth::check())
                <input type="text" required name="telefon" class="form-control" placeholder="Telefonszám (+362012345678)" pattern="^\+[1-9]{1}[0-9]{6,14}$" title="Telefonszám a következő formátumban: +362012345678" value=@if (old("telefon")!=null) "{{old("telefon")}}" @else "{{$user[0]['telefon']}}" @endif>
                @endif

                @if (!Auth::check())
                <input type="text" required name="telefon" class="form-control" placeholder="Telefonszám (+362012345678)" pattern="^\+[1-9]{1}[0-9]{6,14}$" title="Telefonszám a következő formátumban: +362012345678" value="{{ old("telefon") }}">
                @endif



            </div>
        </div>

        </br>
        @if (Auth::check())
        <input type="text" required name="cim" class="form-control" placeholder="Lakcím" value=@if (old("cim")!=null) "{{old("cim")}}" @else "{{$user[0]['cim']}}" @endif>
        @endif

        @if (!Auth::check())
        <input type="text" required name="cim" class="form-control" placeholder="Lakcím" value="{{ old("cim") }} ">
        @endif


        </br>
        <div class="row">

            <div class="col">
                <label for="kerkezes">Érkezés:</label>
                <input type="text" required name="erkezes" class="form-control" id="erkezesdatum" placeholder="Érkezés (ÉV-HÓ-NAP)" value="{{ old("erkezes") }}">
            </div>

            <div class="col">
                <label for="ktavozas">Távozás:</label>
                <input type="text" required name="tavozas" class="form-control" id="tavozasdatum" placeholder="Távozás (ÉV-HÓ-NAP)" value="{{ old("tavozas") }}">
            </div>

            <div class="col">
                <label for="kdarab">Érkező vendégek száma:</label>
                <input type="number" required name="foglalodb" class="form-control" placeholder="Érkező vendégek száma (Fő)" value="{{ old("foglalodb") }}">
            </div>

        </div>
        </br>
        <input type="text" name="megjegyzes" class="form-control" placeholder="Egyéb megjegyzés" value="{{ old("megjegyzes") }}">

        <input type="checkbox" required form-check-input name="foglalasi_feltetelekcb" title="Mindhárom checkbox elfogadása kötelező!">
        <label for="foglalasi_feltetelekcb"><a href="arak.html" target="new-tab">Elolvastam és elfogadom az
                "Árak/Feltételek" menüpontban található foglalási feltételeket.</a></label></br>

        <input type="checkbox" required form-check-input name="hazirendcb" title="Mindhárom checkbox elfogadása kötelező!">
        <label for="hazirendcb"><a href="hazirend.html" target="new-tab"> Elolvastam és elfogadom a
                vendégház
                házirendjét.</a></label></br>


        <input type="checkbox" required form-check-input name="adatkezelescb" title="Mindhárom checkbox elfogadása kötelező!">
        <label for="adatkezelescb"><a href="adatvedelem.html" target="new-tab">Elolvastam és elfogadom az
                adatkezelési tájékoztatót</a></label></br>

        <button type="submit" class="btn btn-primary" name="send_mail">Küldés</button>

        </form>

        <div id="error">
            <p id="error_message" style="display:none;"></p>
        </div>

        </div>

    </x-slot>

    <x-slot name="extrascripts">

        <script src="js/jquery-3.6.0.js"></script>
        <script src="js/jquery-ui.js"></script>

        <script>
            let foglaltnapok = document.getElementsByClassName('dayres');
            let nemfoglalhatonapok = document.getElementsByClassName('dayemp');
            let foglaltnapdatumok = [];
            for (let index = 0; index < foglaltnapok.length; index++) {
                foglaltnapdatumok.push(foglaltnapok[index].attributes.rel.value)
            }
            for (let index = 0; index < nemfoglalhatonapok.length; index++) {
                foglaltnapdatumok.push(nemfoglalhatonapok[index].attributes.rel.value)
            }

            $(document).ready(function() {
                $("#erkezesdatum").datepicker({
                    minDate: 1,
                    numberOfMonths: 2,
                    dateFormat: 'yy-mm-dd',
                    firstDay: 1,
                    onSelect: function(selected) {
                        $("#tavozasdatum").datepicker("option", "minDate", selected)
                    },
                    beforeShowDay: function(date) {
                        var string = jQuery.datepicker.formatDate('yy-mm-dd', date);
                        return [foglaltnapdatumok.indexOf(string) == -1]
                    }
                });
                $("#tavozasdatum").datepicker({
                    numberOfMonths: 2,
                    minDate: 1,
                    dateFormat: 'yy-mm-dd',
                    firstDay: 1,
                    onSelect: function(selected) {
                        $("#erkezesdatum").datepicker("option", "maxDate", selected)
                    },
                    beforeShowDay: function(date) {
                        var string = jQuery.datepicker.formatDate('yy-mm-dd', date);
                        return [foglaltnapdatumok.indexOf(string) == -1]
                    }
                });
            });
        </script>

    </x-slot>

</x-base>