<?php

use App\Http\Controllers\PacienteController;

Route::resource('pacientes', PacienteController::class);

Route::get('/pacientes/export/pdf', [PacienteController::class, 'exportPdf'])->name('pacientes.export.pdf');