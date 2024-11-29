<x-base>
    <x-slot name="anchor">
        adatmodositas
    </x-slot>

    <x-slot name="sectionHeading">
        Képek Megtekintése és módosítása
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
                        null,
                        {
                            orderDataType: 'dom-text',
                            type: 'string'
                        },
                        {
                            orderDataType: 'dom-text',
                            type: 'string'
                        },
                        null, null,
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
                                <th>Kép</th>
                                <th>Név</th>
                                <th>Pozíció</th>
                            </thead>

                            @foreach ($kepek as $kep)
                                <tr>
                                    <form action="/dashboard/kepmodositas" method="POST">
                                    {{ csrf_field() }}
                                    <input type="hidden" id="custId" name="id"
                                        value="{{ $kep['modal_id'] }}" class="form-control">
                                    <td><img src="{{'/'.$kep['indexkep_path']}}" width="50px" height="50px"></td>
                                        <td><input type="text" name='nev' value="{{ $kep['kep_nev'] }}"
                                                class="form-control"></td>
                                        <td><input type="text" name='poz'
                                                value="{{ $kep['kep_pozicio'] }}" class="form-control"></td>

                                        <td><button type="submit" value="{{$kep['modal_id']}}" name="submit"
                                                class="btn btn-primary" >Mentés</button></td>
                                                <td><button type="submit" value="delete" name="submit"
                                                    class="btn btn-primary">Törlés</button></td>
                                    </form>
                                </tr>
                            @endforeach
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </x-slot>

</x-base>
