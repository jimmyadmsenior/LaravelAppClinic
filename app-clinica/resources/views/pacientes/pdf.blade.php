<h1>Lista de Pacientes</h1>
<table width="100%" border="1" cellspacing="0" cellpadding="5">
    <thead>
        <tr>
            <th>Nome</th>
            <th>CPF</th>
            <th>Email</th>
            <th>Idade</th>
        </tr>
    </thead>
    <tbody>
        @foreach($pacientes as $paciente)
            <tr>
                <td>{{ $paciente->nome }}</td>
                <td>{{ $paciente->cpf }}</td>
                <td>{{ $paciente->email }}</td>
                <td>{{ $paciente->idade }}</td>
            </tr>
        @endforeach
    </tbody>
</table>