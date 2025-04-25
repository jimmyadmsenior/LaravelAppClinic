<h1>Editar Paciente</h1>
@include('pacientes._form', [
    'action' => route('pacientes.update', $paciente),
    'method' => 'PUT',
    'paciente' => $paciente,
    'buttonText' => 'Atualizar',
])
<a href="{{ route('pacientes.index') }}">Voltar</a>