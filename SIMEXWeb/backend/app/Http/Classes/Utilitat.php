<?php

namespace App\Http\Classes;

use Illuminate\Database\QueryException;

class Utilitat
{
    public static function queryExceptionResponse(QueryException $e): array
    {
        $errorInfo = $e->errorInfo ?? null;
        $sqlstate = is_array($errorInfo) && isset($errorInfo[0]) ? $errorInfo[0] : null;
        $driverCode = is_array($errorInfo) && isset($errorInfo[1]) ? (int) $errorInfo[1] : null;

        $message = 'Error SQL';

        if ($driverCode !== null) {
            switch ($driverCode) {
                case 1062: // MySQL duplicate entry
                case 2601: // SQL Server unique index violation
                case 2627: // SQL Server unique constraint violation
                case 20018: // SQLSRV wrapped duplicate key error
                    $message = 'Valor duplicado (restriccion UNIQUE)';
                    break;
                case 547: // SQL Server foreign key violation
                    $message = 'Registro con elementos relacionados';
                    break;
                default:
                    $rawMessage = strtolower((string) $e->getMessage());
                    if (
                        str_contains($rawMessage, 'duplicate key')
                        || str_contains($rawMessage, 'cannot insert duplicate key row')
                        || str_contains($rawMessage, 'unique index')
                        || str_contains($rawMessage, 'unique constraint')
                    ) {
                        $message = 'Valor duplicado (restriccion UNIQUE)';
                    } else {
                        $message = $e->getMessage();
                    }
                    break;
            }
        }

        return [
            'message' => $message,
            'errors' => ['code' => [$message]],
            'sqlstate' => $sqlstate,
            'driver_code' => $driverCode,
        ];
    }
}
