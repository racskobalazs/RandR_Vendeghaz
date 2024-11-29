<x-base>
    <x-slot name="anchor">
        kepfeltoltes
    </x-slot>

    <x-slot name="sectionHeading">
        Kép és Programfeltöltés
    </x-slot>

    <x-slot name="extrascripts">
        <script type="text/javascript">

            function yesnoCheck() {
                if (!document.getElementById('kepcheck').checked) {
                    document.getElementById('nev').placeholder="Program neve";
                    document.getElementById('program_link').style.display = 'block';
                    document.getElementById('program_kategoria').style.display = 'block';
                }
                else {
                    document.getElementById('nev').placeholder="Kép neve";
                    document.getElementById('program_link').style.display = 'none';
                    document.getElementById('program_kategoria').style.display = 'none';
                }

            }
            </script>
    </x-slot>

    <x-slot name='content'>
        Kép és program feltöltő aloldal

        @if ($errors->any())
        @foreach ($errors->all() as $hiba)
            <h6>{{ $hiba }}</h6>
        @endforeach
    @endif

        <form class="form" action="/dashboard/kepfeltoltes" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}

            <table>
                <tbody>
                    <tr>
                        <fieldset name="radio_group">
                        <td><input type="radio" onclick="yesnoCheck()" id="kepcheck" name="radio_group" value="kep" >
                        <label for="kepcheck">Kép</label></td>
                        <td><input type="radio" onclick="yesnoCheck()" id="programcheck" name="radio_group" value="program" checked>
                        <label for="programcheck">Program</label></td>
                        </fieldset>
                    </tr>
                    <tr>
                        <td><input type="text" name="kep_nev" id="nev" class="form-control" placeholder="Program neve" required></td>
                    </tr>
                    <tr>
                        <td>
                            <input type="text" name="program_link" id="program_link" class="form-control" placeholder="Program linkje">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <select name="program_kategoria" id="program_kategoria" class="form-select">
                                <option value="elmeny">Élmény</option>
                                <option value="etel">Gasztronómia</option>
                                <option value="nature">Természet</option>
                                <option value="strand">Strand</option>
                              </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Feltölteni kívánt kép:</td>
                        <td><input type="file"
                            id="kep" name="kep"
                            accept="image/jpeg" required>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <button type="submit" name="save" class="btn btn-primary">Mentés</button>
                        </td>

                    </tr>

                </tbody>
            </table>
        </form>


    </x-slot>

</x-base>
