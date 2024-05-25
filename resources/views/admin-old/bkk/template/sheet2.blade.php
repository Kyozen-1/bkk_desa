<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Partai</th>
        </tr>
    </thead>
    <tbody>
        @php
            $a = 1;
        @endphp
        @foreach ($fraksis as $fraksi)
            <tr>
                <td>{{$a++}}</td>
                <td>{{$fraksi->nama}}</td>
            </tr>
        @endforeach
    </tbody>
    <thead>
        <tr>
            <th>No</th>
            <th>Partai</th>
            <th>Aspirator</th>
        </tr>
    </thead>
    <tbody>
        @php
            $b = 1;
        @endphp
        @foreach ($aspirators as $aspirator)
            <tr>
                <td>{{$b++}}</td>
                <td>{{$aspirator->master_fraksi->nama}}</td>
                <td>{{$aspirator->nama}}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Partai</th>
            <th>Aspirator</th>
        </tr>
    </thead>
    <tbody>
        @php
            $b = 1;
        @endphp
        @foreach ($aspirators as $aspirator)
            <tr>
                <td>{{$b++}}</td>
                <td>{{$aspirator->master_fraksi->nama}}</td>
                <td>{{$aspirator->nama}}</td>
            </tr>
        @endforeach
    </tbody>
</table>
