<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAyudantiasFullSchema extends Migration
{
    public function up(): void
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->string('rut')->primary();
            $table->string('nombres', 500);
            $table->string('paterno', 500);
            $table->string('materno', 500)->nullable();
            $table->string('email', 500)->nullable();
            $table->string('sexo_registral', 150)->nullable();
            $table->string('genero', 150)->nullable();
            $table->string('cuenta_pasaporte', 150)->nullable();
            $table->integer('estado')->default(1);
        });

        Schema::create('carreras', function (Blueprint $table) {
            $table->id('id_carrera');
            $table->string('id_ucampus', 45)->nullable();
            $table->string('nombre', 150);
            $table->integer('estado');
        });

        Schema::create('permisos', function (Blueprint $table) {
            $table->id('id_permiso');
            $table->unsignedBigInteger('id_carrera');
            $table->string('rut');
            $table->string('tipo', 150);
            $table->string('estado', 45)->default('1');
            $table->foreign('rut')->references('rut')->on('usuarios');
            $table->foreign('id_carrera')->references('id_carrera')->on('carreras');
        });

        Schema::create('banco', function (Blueprint $table) {
            $table->id();
            $table->string('banco', 150);
            $table->integer('estado')->default(1);
        });

        Schema::create('tipo_cuenta', function (Blueprint $table) {
            $table->id();
            $table->string('tipo_cuenta', 150);
            $table->unsignedBigInteger('banco_id');
            $table->integer('estado')->default(1);
            $table->foreign('banco_id')->references('id')->on('banco');
        });

        Schema::create('datos_banco', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tipo_cuenta_id');
            $table->string('rut');
            $table->string('num_cuenta', 150);
            $table->integer('estado')->default(1);
            $table->foreign('tipo_cuenta_id')->references('id')->on('tipo_cuenta');
            $table->foreign('rut')->references('rut')->on('usuarios');
        });

        Schema::create('asignatura', function (Blueprint $table) {
            $table->id('id_asigantura');
            $table->string('nombre', 150);
            $table->unsignedBigInteger('id_carrera');
            $table->string('rut');
            $table->string('seccion', 150)->nullable();
            $table->string('bloque_1', 150)->nullable();
            $table->string('bloque_2', 150)->nullable();
            $table->string('bloque_3', 150)->nullable();
            $table->integer('cupos')->nullable();
            $table->integer('estado')->default(1);
            $table->foreign('id_carrera')->references('id_carrera')->on('carreras');
            $table->foreign('rut')->references('rut')->on('usuarios');
        });

        Schema::create('solicitud', function (Blueprint $table) {
            $table->id('id_solicitud');
            $table->string('n_matricula', 150)->nullable();
            $table->unsignedBigInteger('id_asigantura');
            $table->unsignedBigInteger('id_carrera_estudiante');
            $table->unsignedBigInteger('datos_banco_id');
            $table->string('pers_dig', 45);
            $table->dateTime('fecha_dig')->useCurrent();
            $table->integer('estado')->default(1);
            $table->foreign('id_asigantura')->references('id_asigantura')->on('asignatura');
            $table->foreign('id_carrera_estudiante')->references('id_carrera')->on('carreras');
            $table->foreign('datos_banco_id')->references('id')->on('datos_banco');
        });

        Schema::create('historial_solicitud', function (Blueprint $table) {
            $table->id('id_historial');
            $table->unsignedBigInteger('id_solicitud');
            $table->string('etapa', 150);
            $table->string('estado_solicitud', 150);
            $table->string('pers_dig', 45);
            $table->dateTime('fecha_dig')->useCurrent();
            $table->integer('estado')->default(1);
            $table->foreign('id_solicitud')->references('id_solicitud')->on('solicitud');
            $table->unique(['id_historial', 'pers_dig']);
        });

        Schema::create('bitacora', function (Blueprint $table) {
            $table->id('id_bitacora');
            $table->string('accion', 150);
            $table->string('pers_dig', 45);
            $table->dateTime('fecha')->useCurrent();
            $table->integer('estado')->default(1);
        });

        Schema::create('proceso', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 45);
            $table->integer('estado')->default(1);
        });

        Schema::create('etapa_proceso', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('proceso_id');
            $table->string('etapa_proceso', 150);
            $table->dateTime('fecha_inicio')->useCurrent();
            $table->dateTime('fecha_fin')->useCurrent();
            $table->string('descripcion', 150);
            $table->integer('estado')->default(1);
            $table->foreign('proceso_id')->references('id')->on('proceso');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('etapa_proceso');
        Schema::dropIfExists('proceso');
        Schema::dropIfExists('bitacora');
        Schema::dropIfExists('historial_solicitud');
        Schema::dropIfExists('solicitud');
        Schema::dropIfExists('asignatura');
        Schema::dropIfExists('datos_banco');
        Schema::dropIfExists('tipo_cuenta');
        Schema::dropIfExists('banco');
        Schema::dropIfExists('permisos');
        Schema::dropIfExists('carreras');
        Schema::dropIfExists('usuarios');
    }
}
