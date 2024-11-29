<x-base>


    <x-slot name="anchor">
        vendegkonyv
    </x-slot>

    <x-slot name="sectionHeading">
        Vendégkönyv
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
                            type: 'string'
                        },
                        {
                            type: 'string'
                        },
                        {
                            type: 'string'
                        },
                        {
                            type: 'string'
                        },
                    ],
                });
            });
        </script>

    </x-slot>


    <x-slot name='content'>

        <table class="table display" id="myTable">
            <thead>
                <td>Felhasználó</td>
                <td>Email</td>
                <td>Üzenet</td>
                <td>Dátum</td>
            </thead>
            <tbody>

                @foreach($konyv as $uzenet)

                <tr>
                    <td>{{$uzenet["name"]}}</td>
                    <td>{{$uzenet["email"]}}</td>
                    <td>{{$uzenet["uzenet"]}}</td>
                    <td>{{$uzenet["mikor"]}}</td>
                </tr>

                @endforeach
            </tbody>
        </table>
    </x-slot>

</x-base>