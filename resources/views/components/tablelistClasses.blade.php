<div class="table-responsive col-sm-8" style="overflow-x: visible; overflow-y: visible;">
    <table class='table table-striped' id="tabela">
        <thead>
            <tr style="text-align: center">
                @foreach ($header as $item)
                    <th>{{ $item }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach (json_decode($data, true) as $item)
                <tr id="{{ $item['id'] }}line" style="text-align: center">
                    <td style="display: none">{{ $item['id'] }}</td>
                    <td>{{ $item['name'] }}</td>
                    <td id="{{ $item['id'] }}selectsStudents" >
                        <select class="form-control">
                            @foreach ($item['student'] as $a)
                                <option>{{$a['name']}}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                    <a nohref style="cursor: pointer" onclick="edit('{{ $item['id'] }}')"><img src="{{ asset('img/icons/edit.svg') }}"></a>
                    <a nohref style="cursor: pointer" onclick="deleteClassroom('{{ $item['id'] }}')"><img style='width: 20px; height: 20px' src="{{ asset('img/icons/close.png') }}"></a>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>


