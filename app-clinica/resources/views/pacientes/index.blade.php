<h1>Pacientes Cadastrados</h1>

@if(session('success'))
    <div style="color: green;">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div style="color: red;">{{ session('error') }}</div>
@endif

<a href="{{ route('pacientes.export.pdf') }}">Exportar PDF</a>
<a href="{{ route('pacientes.export.csv') }}">Exportar CSV</a>
<a href="{{ route('pacientes.create') }}">Novo Paciente</a>

<form action="{{ route('pacientes.send.api') }}" method="POST" style="display:inline">
    @csrf
    <button type="submit">Enviar dados para API</button>
</form>

<table border="1" cellpadding="5">
    <tr>
        <th>ID</th>
        <th>Nome</th>
        <th>CPF</th>
        <th>Email</th>
        <th>Idade</th>
        <th>Foto</th>
        <th>Ações</th>
    </tr>

    @foreach ($pacientes as $p)
        <tr>
            <td>{{ $p->id }}</td>
            <td>{{ $p->nome }}</td>
            <td>{{ $p->cpf }}</td>
            <td>{{ $p->email }}</td>
            <td>{{ $p->idade }}</td>
            <td>
                @if($p->foto)
                    <img src="{{ asset('storage/' . $p->foto) }}" width="60">
                @else
                    Sem foto
                @endif
            </td>
            <td>
                <a href="{{ route('pacientes.edit', $p) }}">Editar</a> |
                <form action="{{ route('pacientes.destroy', $p) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Tem certeza?')">Excluir</button>
                </form>
            </td>
        </tr>
    @endforeach
</table>