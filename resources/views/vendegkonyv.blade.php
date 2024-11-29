<x-base>


    <x-slot name="anchor">
        vendegkonyv
    </x-slot>

    <x-slot name="sectionHeading">
        Vendégkönyv
    </x-slot>

    <x-slot name='content'>

    @if($errors->any())
        <h4>{{$errors->first()}}</h4>
        @endif

        @if(session('success'))
        <h6 style="color: green">{{session('success')}}</h6>
    @endif

    <form class="form" action="/dashboard/vendegkonyv" method="POST">
            {{ csrf_field() }}

            <table>
                <tbody>
                    <tr>
                        <td>Üzenet:</td>
                        <td><input type="text" maxlength="2048" size="400" name="uzenet" required placeholder="Ide írhatja a vendégházunknak szánt üzenetét." value="" class="form-control"></td>
                    </tr>
                    <tr> <td>
                        <button type="submit" name="save" class="btn btn-primary">Küldés</button>
                        </td>
                    </tr>

                </tbody>
            </table>
        </form>
    </x-slot>

</x-base>