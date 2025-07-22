<?php

namespace App\Providers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Models\Banco;
use App\Models\DatoBanco;
use App\Models\TipoCuenta;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);
        \Carbon\Carbon::setLocale('es');
        Validator::extend('rut_valido', function ($attribute, $value, $parameters, $validator) {
            return validarRut($value);
        }, 'El RUT ingresado no es válido.');
        // Validator::extend('rut_valido', function ($attribute, $value, $parameters, $validator) {
        //     return validarRut($value);
        // });

        View::composer('*', function ($view) {
            if (Auth::check()) {
                $bancosPerfil = Banco::all();

                $datosBancoPerfil = DatoBanco::with('tipoCuenta.banco')
                    ->where('rut', Auth::user()->rut)
                    ->orderByDesc('id')
                    ->first();

                $tiposCuentaPerfil = TipoCuenta::with('banco')->get();
                
                $view->with('bancosPerfil', $bancosPerfil);
                $view->with('datosBancoPerfil', $datosBancoPerfil);
                $view->with('tiposCuentaPerfil', $tiposCuentaPerfil);
            }
        });

        $etapas_activas = DB::table('etapa_proceso')
                ->join('proceso', 'etapa_proceso.proceso_id', '=', 'proceso.id')
                ->where('etapa_proceso.estado', 1)
                ->where('proceso.estado', 1)
                ->select(
                    'etapa_proceso.*',
                    'proceso.nombre as nombre_proceso'
                )
                ->orderBy('etapa_proceso.tipo', 'asc')
                ->get();

            View::share('etapas_activas', $etapas_activas);
       
    }
}
