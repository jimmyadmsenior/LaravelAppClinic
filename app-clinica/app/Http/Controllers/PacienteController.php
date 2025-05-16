<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\PacientesExport;
use Illuminate\Support\Facades\Http;

class PacienteController extends Controller
{

    public function exportPdf()
    {
        $pacientes = Paciente::all();
        $pdf = Pdf::loadView('pacientes.pdf', compact('pacientes'));
        return $pdf->download('pacientes.pdf');
    }

    public function exportCsv()
    {
        $pacientes = \App\Models\Paciente::all();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="pacientes.csv"',
        ];

        $callback = function() use ($pacientes) {
            $handle = fopen('php://output', 'w');
            // Cabeçalho do CSV
            fputcsv($handle, ['ID', 'Nome', 'CPF', 'Email', 'Idade']);
            // Dados
            foreach ($pacientes as $p) {
                fputcsv($handle, [$p->id, $p->nome, $p->cpf, $p->email, $p->idade]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportJson()
    {
        $pacientes = Paciente::all();
        return response()->json($pacientes);
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

    public function sendToApi()
    {
        $pacientes = Paciente::all();

        $response = Http::post('http://127.0.0.1:8001/api/receber-dados', [
            'pacientes' => $pacientes->map(function($p) {
                return [
                    'nome_completo' => $p->nome,
                    'documento' => $p->cpf,
                    'contato' => $p->email,
                    'idade' => $p->idade,
                ];
            }),
        ]);

        if ($response->successful()) {
            return back()->with('success', 'Dados enviados para API com sucesso!');
        }

        return back()->with('error', 'Erro ao enviar dados para API.');
    }

    public function importFromApi()
    {
        $response = Http::get('https://url-da-api.com/dados-pacientes');

        if ($response->successful()) {
            $pacientes = $response->json();

            foreach ($pacientes as $dados) {
                Paciente::create([
                    'nome' => $dados['nome'],
                    'cpf' => $dados['cpf'],
                    'email' => $dados['email'],
                    'idade' => $dados['idade'],
                ]);
            }

            return back()->with('success', 'Pacientes importados com sucesso!');
        }

        return back()->with('error', 'Erro ao importar dados da API.');
    }
}