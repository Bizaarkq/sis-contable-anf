<?php

namespace App\Helpers;

class RatiosCuenta
{
    const EFECTIVO = 1;
    const CUENTAS_COBRO = 2;
    const INVENTARIOS = 8;
    const PROVEEDORES = 17;
    const ARREGLO = [
        "EFECTIVO" => 1,
        "CUENTAS_COBRO" => 2,
        "INVENTARIOS" => 8,
        "PROVEEDORES" => 17
    ];

    const CONFIGURACION = [
        "BALANCE" => [
            33,34,36,41,43,44
        ],
        "ESTADO_RESULTADOS" => [
            1,2,8,17
        ]
    ];
}
