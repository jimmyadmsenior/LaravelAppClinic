<?php

use App\Http\Controllers\PacienteController;

Route::resource('pacientes', PacienteController::class);

Route::get('/pacientes/export/pdf', [PacienteController::class, 'exportPdf'])->name('pacientes.export.pdf');

Route::get('/pacientes/export/csv', [PacienteController::class, 'exportCsv'])->name('pacientes.export.csv');

Route::get('/pacientes/json', [PacienteController::class, 'exportJson'])->name('pacientes.export.json');

Route::post('/pacientes/send-api', [PacienteController::class, 'sendToApi'])->name('pacientes.send.api');

Route::get('/pacientes/importar-api', [PacienteController::class, 'importFromApi'])->name('pacientes.import.api');