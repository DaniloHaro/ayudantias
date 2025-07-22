<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SimulacionController;
use App\Http\Controllers\SolicitudController;
use App\Http\Controllers\Admin\UsuarioController;
use App\Http\Controllers\Admin\PermisoController;
use App\Http\Controllers\Admin\CarreraController;
use App\Http\Controllers\Admin\AsignaturaController;
use App\Http\Controllers\Admin\TipoCuentaController;
use App\Http\Controllers\Admin\ProcesoController;
use App\Http\Controllers\Admin\EtapaProcesoController;

Route::get('/', [LoginController::class, 'index']); 
Route::get('/login', [LoginController::class, 'login'])->name('login'); 
Route::get('/redirect-cas-logout/{motivo?}', [LoginController::class, 'redirectCasLogout'])->name('redirect.cas.logout');
Route::view('/home', 'home'); // Vista amigable de inicio (no redirige)

Route::middleware('auth')->group(function () {
    Route::get('/admin/dashboard', [LoginController::class, 'dashboard'])->name('dashboard');
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::post('/simular-usuario', [SimulacionController::class, 'simular'])->name('simular.usuario');
    Route::post('/volver-mi-usuario', [SimulacionController::class, 'volver'])->name('volver.usuario');
 
     
    Route::prefix('admin')->group(function () {
        Route::resource('usuarios', UsuarioController::class);
        Route::resource('carreras', CarreraController::class);
        Route::resource('permisos', PermisoController::class);
        Route::resource('asignaturas', AsignaturaController::class);
        Route::resource('solicitudes', SolicitudController::class);
        Route::resource('procesos', ProcesoController::class);
        Route::resource('etapas', ProcesoController::class);
        Route::get('/crear-solicitud', [SolicitudController::class, 'create'])->name('crear-solicitud'); 
        Route::get('/mis-solicitudes', [SolicitudController::class, 'mis_solicitudes'])->name('mis-solicitudes'); 
        Route::post('/solicitudes/aceptar', [SolicitudController::class, 'aceptar'])->name('solicitudes.aceptar'); 
        Route::post('/solicitudes/rechazar', [SolicitudController::class, 'rechazar'])->name('solicitudes.rechazar'); 
        Route::get('/solicitudes-docente', [SolicitudController::class, 'solicitudesDocente'])->name('solicitudes-docente'); 
         Route::get('/solicitudes-coordinador', [SolicitudController::class, 'solicitudesCoordinador'])->name('solicitudes-coordinador'); 
        Route::get('/aceptadas-rechazadas', [SolicitudController::class, 'solicitudesDocenteAceptaRechaza'])->name('aceptadas-rechazadas'); 
        Route::get('/detalle-solicitud/{id}', [SolicitudController::class, 'show'])->name('solicitudes.show');
        Route::get('/asignaturas-por-carrera/{id_carrera}', [SolicitudController::class, 'asignaturasPorCarrera']);
        Route::get('/asignaturas-info/{id}', [AsignaturaController::class, 'info'])->name('asignaturas.info');
        Route::get('/tipos-cuenta/{id}/', [TipoCuentaController::class, 'tiposPorBanco'])->name('tipocuenta.tiposPorBanco');
        Route::get('/perfil/datos', [UsuarioController::class, 'obtenerPerfil']);
        Route::put('/perfil/actualizar', [UsuarioController::class, 'actualizarPerfil'])->name('perfil.actualizar');
        Route::post('/solicitudes/seleccionar', [SolicitudController::class, 'seleccionar'])->name('solicitudes.seleccionar');

        Route::post('/solicitudes/seleccionar-docente', [SolicitudController::class, 'seleccionarDocente'])->name('solicitudes.seleccionar-docente');
        Route::post('/solicitudes/rechazar-docente', [SolicitudController::class, 'rechazarDocente'])->name('solicitudes.rechazar-docente');

    });

    Route::prefix('procesos/{proceso}')->name('procesos.')->group(function () {
        Route::resource('etapas', EtapaProcesoController::class);
    });

});