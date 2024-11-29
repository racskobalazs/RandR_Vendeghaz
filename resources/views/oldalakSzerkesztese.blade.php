<x-base>


    <x-slot name="sectionHeading">
        Oldalak szerkesztése
    </x-slot>

    <x-slot name="anchor">
        szerkeszto
    </x-slot>

    <x-slot name="content">

        <form class="form" action="/dashboard/oldalakSzerkesztese" method="POST" onsubmit="return confirm('Biztos menteni szeretné?');">
            {{ csrf_field() }}

            <table class="table">
                <tbody>
                    <tr>
                        <td>Oldal:</td>
                        <td>
                            <select name="aktShow" onchange="this.form.submit()" id="select">
                                @foreach ($oldalak as $oldal)
                                    @if ($prevShow == $oldal)
                                        <option value="{{ $oldal }}" selected>{{ $oldal }}</option>
                                    @else
                                        <option value="{{ $oldal }}">{{ $oldal }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Oldal tartalma:</td>
                        <td>
                            <textarea cols="75" rows="10" name="content">{{ $content }}</textarea>
                        </td>
                    </tr>
                    <tr>
                        <td><input type="submit" value="Mentés" class="btn btn-primary"></td>
                    </tr>

                </tbody>
            </table>
        </form>


        </div>

    </x-slot>

</x-base>
