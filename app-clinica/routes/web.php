<?php

use App\Http\Controllers\PacienteController;

Route::get('/pacientes/export/pdf', [PacienteController::class, 'exportPdf'])->name('pacientes.export.pdf');