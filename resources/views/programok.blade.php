<x-base>


    <x-slot name="anchor">
        programok
    </x-slot>

    <x-slot name="sectionHeading">
        Programlehetőségek a közelben:
    </x-slot>

    <x-slot name="extracss">
        <link href="css/programok.css" rel="stylesheet" />
    </x-slot>

    <x-slot name="content">

        <div class="container">
            <div id="myBtnContainer">
                <button class="btn active" onclick="filterSelection('all')"> Összes Program</button>
                <button class="btn" onclick="filterSelection('nature')"> Természet</button>
                <button class="btn" onclick="filterSelection('etel')"> Gasztronómia</button>
                <button class="btn" onclick="filterSelection('elmeny')"> Élmény</button>
                <button class="btn" onclick="filterSelection('strand')"> Strandok</button>
            </div>
            <br>

            <div class="row">
                @foreach ($programok as $program)
                    <div class="column {{ $program['kategoria'] }} col-xs-12 col-md-6 col-lg-3">
                        <div class="content">
                            <img src={{ $program['kep_path'] }} alt={{ $program['program_leiras'] }}
                                style="width:100%">
                            <h4>{{ $program['program_leiras'] }}</h4>
                            <p> <a href={{ $program['program_link'] }}>Bővebb információ</a></p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>



    </x-slot>

    <x-slot name="extrascripts">
        <script src="js/programok.js"></script>
    </x-slot>



</x-base>
