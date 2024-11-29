<x-base>
    <x-slot name="anchor">
        adatmodositas
    </x-slot>

    <x-slot name="sectionHeading">
        Foglalások Megtekintése és Módosítása
    </x-slot>

    <x-slot name="extracss">
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.css">
    </x-slot>
    <x-slot name="extrascripts">
        <script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI=" crossorigin="anonymous"></script>
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.js"></script>
        <script>
            /* Create an array with the values of all the input boxes in a column */
            $.fn.dataTable.ext.order['dom-text'] = function(settings, col) {
                return this.api()
                    .column(col, {
                        order: 'index'
                    })
                    .nodes()
                    .map(function(td, i) {
                        return $('input', td).val();
                    });
            };

            /* Create an array with the values of all the input boxes in a column, parsed as numbers */
            $.fn.dataTable.ext.order['dom-text-numeric'] = function(settings, col) {
                return this.api()
                    .column(col, {
                        order: 'index'
                    })
                    .nodes()
                    .map(function(td, i) {
                        return $('input', td).val() * 1;
                    });
            };

            /* Create an array with the values of all the select options in a column */
            $.fn.dataTable.ext.order['dom-select'] = function(settings, col) {
                return this.api()
                    .column(col, {
                        order: 'index'
                    })
                    .nodes()
                    .map(function(td, i) {
                        return $('select', td).val();
                    });
            };

            /* Create an array with the values of all the checkboxes in a column */
            $.fn.dataTable.ext.order['dom-checkbox'] = function(settings, col) {
                return this.api()
                    .column(col, {
                        order: 'index'
                    })
                    .nodes()
                    .map(function(td, i) {
                        return $('input', td).prop('checked') ? '1' : '0';
                    });
            };

            /* Initialise the table with the required column ordering data types */
            $(document).ready(function() {
                $('#myTable').DataTable({
                    bDeferRender: false,
                    "language": {
                        "url": "//cdn.datatables.net/plug-ins/1.12.1/i18n/hu.json"
                    },
                    columns: [{
                            orderDataType: 'dom-text',
                            type: 'string'
                        },
                        {
                            orderDataType: 'dom-text',
                            type: 'string'
                        },
                        {
                            orderDataType: 'dom-text',
                            type: 'string'
                        },
                        {
                            orderDataType: 'dom-select'
                        },
                        null,
                        null
                    ],
                });
            });
        </script>

    </x-slot>

    <x-slot name='content'>

        @if($errors->any())
        <h4 style="color:red;">{{$errors->first()}}</h4>
        @endif

        @if(session('success'))
        <h6 style="color: green">{{session('success')}}</h6>
        @endif

        <div class="py-12">
            <div class="max-w-min mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        Itt láthatóak az eddig küldött foglalások:
                        <table class="table display" id="myTable">
                            <thead>
                                <th>Név</th>
                                <th>Mikortól</th>
                                <th>Meddig</th>
                                <th>Státusz</th>
                            </thead>

                            @foreach ($foglalasok as $foglalas)
                            <tr @switch($foglalas['foglalas_status']) @case('elutasitva') style="background-color: rgba(231, 74, 22, 0.5)" @break @case('nem foglalhato') style="background-color: rgba(165, 158, 156, 0.5)" @break @case('elfogadva') style="background-color: rgba(185, 235, 47, 0.473)" @break @case('fizetve') style="background-color: rgba(51, 233, 15, 0.5)" @break @default @endswitch>
                                <form action="/dashboard/foglalasok" method="POST">
                                    <td style="display: none;"><input type="text" name="foglalas_id" class="form-control" value="{{ $foglalas['foglalas_id'] }}"></td>
                                    <td><input type="text" name='nev' value="{{ $foglalas['foglalo_nev'] }}" class="form-control"></td>
                                    <td><input type="text" name='start' value="{{ $foglalas['foglalas_start'] }}" class="form-control"></td>
                                    <td><input type="text" name='end' value="{{ $foglalas['foglalas_end'] }}" class="form-control"></td>

                                    <td>
                                        {{ csrf_field() }}
                                        <select name="{{ $foglalas['foglalas_id'] }}" class="form-control">
                                            @switch($foglalas['foglalas_status'])
                                            @case('nem foglalhato')
                                            <option selected value="nem foglalhato">Nem foglalható</option>
                                            <option value="elutasitva">Elutasítva</option>
                                            <option value="elfogadva">Elfogadva</option>
                                            <option value="fizetve">Fizetve</option>
                                            <option value="elofoglalas">Előfoglalás</option>
                                            @break

                                            @case('elutasitva')
                                            <option value="nem foglalhato">Nem foglalható</option>
                                            <option selected value="elutasitva">Elutasítva</option>
                                            <option value="elfogadva">Elfogadva</option>
                                            <option value="fizetve">Fizetve</option>
                                            <option value="elofoglalas">Előfoglalás</option>
                                            @break

                                            @case('fizetve')
                                            <option value="nem foglalhato">Nem foglalható</option>
                                            <option value="elutasitva">Elutasítva</option>
                                            <option value="elfogadva">Elfogadva</option>
                                            <option selected value="fizetve">Fizetve</option>
                                            <option value="elofoglalas">Előfoglalás</option>
                                            @break

                                            @case('elofoglalas')
                                            <option value="nem foglalhato">Nem foglalható</option>
                                            <option value="elutasitva">Elutasítva</option>
                                            <option value="elfogadva">Elfogadva</option>
                                            <option value="fizetve">Fizetve</option>
                                            <option selected value="elofoglalas">Előfoglalás</option>
                                            @break

                                            @case('elfogadva')
                                            <option value="nem foglalhato">Nem foglalható</option>
                                            <option value="elutasitva">Elutasítva</option>
                                            <option selected value="elfogadva">Elfogadva</option>
                                            <option value="fizetve">Fizetve</option>
                                            <option value="elofoglalas">Előfoglalás</option>
                                            @break

                                            @default
                                            @endswitch
                                        </select>
                                    </td>
                                    <td><button type="submit" value="save" class="btn btn-primary">Mentés</button></td>
                                    <td><button type="submit" name="submit" value="delete" class="btn btn-primary">Törlés</button></td>
                                </form>
                            </tr>
                            @endforeach
                        </table>

                    </div>
                    <form action="/dashboard/export" method="POST">
                        {{ csrf_field() }}
                        <button type="submit" value="export" class="btn btn-primary">Exportálás</button>
                    </form>
                </div>
            </div>
        </div>
    </x-slot>

</x-base>