
<x-base>
    <x-slot name="anchor">
        adatmodositas
    </x-slot>

    <x-slot name="sectionHeading">
        Saját foglalások megtekintése
    </x-slot>

    <x-slot name="extrascripts">
        <script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI="
            crossorigin="anonymous"></script>
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.js"></script>
        <script>
            $(document).ready( function () {
  var table = $('#myTable').DataTable({
    "language": {
      "url": "//cdn.datatables.net/plug-ins/1.12.1/i18n/hu.json"
     },
  });
} );
            </script>
    </x-slot>

    <x-slot name="extracss">
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.css">
    </x-slot>

    <x-slot name='content'>
Saját foglalások:
                    <table class="table display" id="myTable">
                        <thead>
                            <th>Start</th>
                            <th>End</th>
                            <th>Fő</th>
                            <th>Megjegyzes</th>
                            <th>Státusz</th>
                        </thead>

                        @foreach ($foglalasok as $foglalas)
                            <tr>
                                <form>
                                    <td>{{ $foglalas['foglalas_start'] }}</td>
                                    <td>{{ $foglalas['foglalas_end'] }}</td>
                                    <td>{{ $foglalas['foglalas_db'] }}</td>
                                    <td>{{ $foglalas['foglalas_megj'] }}</td>
                                    <td>{{ $foglalas['foglalas_status'] }}</td>
                                </form>
                            </tr>
                        @endforeach
                    </table>
    </x-slot>

</x-base>
