
<x-base>
    <x-slot name="anchor">
        adatmodositas
    </x-slot>

    <x-slot name="sectionHeading">
        Felhasználói Adatok Módosítása
    </x-slot>

    <x-slot name="extracss">
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.css">
    </x-slot>
    <x-slot name="extrascripts">
        <script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI="
            crossorigin="anonymous"></script>
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
                    columns: [
                        {
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
                            orderDataType: 'dom-text',
                            type: 'string'
                        },
                        {
                            orderDataType: 'text',
                            type: 'string'
                        },
                        null,
                        null,
                    ],
                });
            });
        </script>

    </x-slot>

    <x-slot name='content'>
    <div class="py-12">
        <div class="max-w-min mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    Itt láthatóak az eddig regisztrált felhasználók elérhetőségei.
                    @if($errors->any())
        <h4 style="color: red">{{$errors->first()}}</h4>
        @endif

        @if(session('success'))
        <h6 style="color: green">{{session('success')}}</h6>
    @endif
                    <table class="table display" id="myTable">
                        <thead>
                            <th>Név</th>
                            <th>E-mail</th>
                            <th>Telefonszám</th>
                            <th>Cím</th>
                            <th>Regisztráció Időpontja</th>
                        </thead>

                        @foreach ($felhasznalok as $felhasznalo)
                            <form method="POST" action="/dashboard/felhasznalok">
                                {{ csrf_field() }}
                                <tr>
                                    <input type="hidden" id="custId" name="id"
                                        value="{{ $felhasznalo['id'] }}" class="form-control">
                                    <td><input type="text" name="nev" id=""
                                            value="{{ $felhasznalo['name'] }}" class="form-control"></td>
                                    <td><input type="text" name="email" id=""
                                            value="{{ $felhasznalo['email'] }}" class="form-control"></td>
                                    <td><input type="text" name="telefon" id=""
                                            value="{{ $felhasznalo['telefon'] }}" class="form-control"></td>
                                    <td>
                                        <input type="text" name="cim" id=""
                                            value="{{ $felhasznalo['cim'] }}" class="form-control">
                                    </td>
                                    <td>
                                        {{ $felhasznalo['created_at'] }}
                                    </td>
                                    <td><button type="submit" name="submit" value="save" class="btn btn-primary">Mentés</button></td>
                                    <td><button type="submit" name="submit" value="delete"
                                        class="btn btn-primary">Törlés</button></td>
                                </tr>
                            </form>
                        @endforeach

                    </table>
                </div>
            </div>
        </div>
    </div>
    </x-slot>

</x-base>
