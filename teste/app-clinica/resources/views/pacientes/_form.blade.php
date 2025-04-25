<form action="{{ $action }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if($method === 'PUT')
        @method('PUT')
    @endif

    <label>Nome:</label>
    <input type="text" name="nome" value="{{ old('nome', $paciente->nome ?? '') }}" required><br>

    <label>CPF:</label>
    <input type="text" name="cpf" value="{{ old('cpf', $paciente->cpf ?? '') }}" required><br>

    <label>Email:</label>
    <input type="email" name="email" value="{{ old('email', $paciente->email ?? '') }}" required><br>

    <label>Idade:</label>
    <input type="number" name="idade" value="{{ old('idade', $paciente->idade ?? '') }}" required><br>

    <label>Foto:</label>
    <input type="file" name="foto"><br>

    @if(isset($paciente) && $paciente->foto)
        <img src="{{ asset('storage/' . $paciente->foto) }}" width="100" alt="Foto do paciente"><br>
    @endif

    <button type="submit">{{ $buttonText }}</button>
</form>