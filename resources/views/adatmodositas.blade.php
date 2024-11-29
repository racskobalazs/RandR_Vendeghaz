<x-base>
    <x-slot name="anchor">
        adatmodositas
    </x-slot>

    <x-slot name="sectionHeading">
        Adatok Módosítása
    </x-slot>

    <x-slot name='content'>
        @if($errors->any())
        <h4>{{$errors->first()}}</h4>
        @endif
        <form class="form" action="/dashboard/adatmodositas" method="POST">
            {{ csrf_field() }}

            <table>
                <tbody>
                    <tr>
                        <td>Név:</td>
                        <td><input type="text" name="nev" value="{{ $felhasznalo['name'] }}" class="form-control"></td>
                    </tr>
                    <tr>
                        <td>E-mail cím::</td>
                        <td><input type="text" name="email" value="{{ $felhasznalo['email'] }}" class="form-control"></td>
                    </tr>
                    <tr>
                        <td>Cím:</td>
                        <td>
                            <textarea rows="2" cols="20" name="cim" class="form-control">{{ $felhasznalo['cim'] }}</textarea>
                        </td>
                    </tr>
                    <tr>
                        <td>Telefonszám:</td>
                        <td><input type="text" name="telefon" value="{{ $felhasznalo['telefon'] }}" class="form-control">
                        </td>
                    </tr>
                    <tr> <td>
                        <button type="submit" name="save" class="btn btn-primary">Mentés</button>
                        </td>

                    </tr>

                </tbody>
            </table>
        </form>
    </x-slot>

</x-base>
