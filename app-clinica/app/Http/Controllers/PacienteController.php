<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\PacientesExport;

class PacienteController extends Controller
{

    public function exportPdf()
    {
        $pacientes = Paciente::all();
        $pdf = Pdf::loadView('pacientes.pdf', compact('pacientes'));
        return $pdf->download('pacientes.pdf');
    }
    
    
    public function index()
    {
        $pacientes = Paciente::all();
        return view('pacientes.index', compact('pacientes'));
    }

    public function create()
    {
        return view('pacientes.create');
    }

	   public function store(Request $request)
		{
    $validated = $request->validate([
        'nome' => 'required|string|max:100',
        'cpf' => 'required|string|max:14',
        'email' => 'required|email|max:100',
        'idade' => 'required|integer|min:0',
        'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    if ($request->hasFile('foto')) {
        $fotoPath = $request->file('foto')->store('fotos', 'public');
        $validated['foto'] = $fotoPath;
    }

    Paciente::create($validated);

    return redirect()->route('pacientes.index')->with('success', 'Paciente cadastrado com sucesso!');
		}

    public function edit(Paciente $paciente)
    {
        return view('pacientes.edit', compact('paciente'));
    }

    public function update(Request $request, Paciente $paciente)
    {
        $request->validate([
            'nome' => 'required|string|max:100',
            'cpf' => 'required|string|max:14|unique:pacientes,cpf,' . $paciente->id,
            'email' => 'required|email|max:100|unique:pacientes,email,' . $paciente->id,
            'idade' => 'required|integer|min:0',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        if ($request->hasFile('foto')) {
            if ($paciente->foto) {
                Storage::disk('public')->delete($paciente->foto);
            }
            $paciente->foto = $request->file('foto')->store('pacientes', 'public');
        }

        $paciente->update([
            'nome' => $request->nome,
            'cpf' => $request->cpf,
            'email' => $request->email,
            'idade' => $request->idade,
            'foto' => $paciente->foto,
        ]);

        return redirect()->route('pacientes.index')->with('success', 'Paciente atualizado!');
    }

    public function destroy(Paciente $paciente)
    {
        if ($paciente->foto) {
            Storage::disk('public')->delete($paciente->foto);
        }
        $paciente->delete();
        return redirect()->route('pacientes.index')->with('success', 'Paciente excluído!');
    }
}