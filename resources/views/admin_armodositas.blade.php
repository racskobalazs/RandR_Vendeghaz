<x-base>
    <x-slot name="anchor">
        adatmodositas
    </x-slot>

    <x-slot name="sectionHeading">
        Ár Módosítása
    </x-slot>

    <x-slot name='content'>

    @if($errors->any())
        <h4 style="color:red;">{{$errors->first()}}</h4>
        @endif

        @if(session('success'))
        <h6 style="color: green">{{session('success')}}</h6>
    @endif
        <form class="form" action="/dashboard/ar" method="POST">
            {{ csrf_field() }}

            <table>
                <tbody>
                    <tr>
                        <td>Jelenlegi alapár:</td>
                        <td><input type="text" name="ar" value="{{ $jelenar[0]["jelen"] }}" class="form-control"></td>
                    </tr>
                    <tr>
                        <td>Kedvezmény százalék1:</td>
                        <td><input type="text" name="sz1" value="{{ $jelenar[0]["szazalek1"] }}" class="form-control"></td>
                    </tr>
                    <tr>
                        <td>Kedvezmény százalék2:</td>
                        <td><input type="text" name="sz2" value="{{ $jelenar[0]["szazalek2"] }}" class="form-control"></td>
                    </tr>
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
