<h1>Cadastrar Paciente</h1>
@include('pacientes._form', [
    'action' => route('pacientes.store'),
    'method' => 'POST',
    'buttonText' => 'Cadastrar',
])
<a href="{{ route('pacientes.index') }}">Voltar</a>