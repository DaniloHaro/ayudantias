<?php

if (!function_exists('formatearRut')) {
    function formatearRut($rut) {
        // Elimina cualquier caracter no numérico
        $rut = preg_replace('/[^0-9]/', '', $rut);

        // Si el RUT está vacío o no es numérico, retorna vacío
        if (empty($rut) || !is_numeric($rut)) {
            return '';
        }

        // Calcula el dígito verificador
        $reversed = strrev($rut);
        $suma = 0;
        $multiplo = 2;

        for ($i = 0; $i < strlen($reversed); $i++) {
            $suma += intval($reversed[$i]) * $multiplo;
            $multiplo = $multiplo == 7 ? 2 : $multiplo + 1;
        }

        $resto = $suma % 11;
        $dv = 11 - $resto;

        if ($dv == 11) {
            $dv = '0';
        } elseif ($dv == 10) {
            $dv = 'K';
        }

        // Formatea el número con puntos
        $formateado = number_format($rut, 0, '', '.');
        return $formateado . '-' . $dv;
    }
}

if (!function_exists('validarRut')) {
    function validarRut($rut) {
        $rut = preg_replace('/[^0-9kK]/', '', $rut);
        $rut = strtoupper($rut);

        if (strlen($rut) < 2) return false;

        $cuerpo = substr($rut, 0, -1);
        $dv = substr($rut, -1);

        $suma = 0;
        $multiplo = 2;

        for ($i = strlen($cuerpo) - 1; $i >= 0; $i--) {
            $suma += intval($cuerpo[$i]) * $multiplo;
            $multiplo = ($multiplo == 7) ? 2 : $multiplo + 1;
        }

        $resto = $suma % 11;
        $dvEsperado = 11 - $resto;

        if ($dvEsperado == 11) $dvEsperado = '0';
        elseif ($dvEsperado == 10) $dvEsperado = 'K';
        else $dvEsperado = (string) $dvEsperado;

        return $dv === $dvEsperado;
    }
}
