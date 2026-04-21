<?php

namespace App\Providers;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

/**
 * Service provider principal de la aplicación.
 *
 * - Registra un driver `dblib` propio para conectar a SQL Server vía FreeTDS
 *   (necesario en este despliegue Linux donde `sqlsrv` no está disponible).
 * - Fija defaults globales al arrancar: Carbon inmutable, prevención de
 *   lazy loading fuera de producción, prohibición de comandos destructivos
 *   en producción y política de contraseñas.
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Registra el driver `dblib` de SQL Server basado en PDO + FreeTDS.
     *
     * Se extiende la factoría de conexiones de Laravel para devolver una
     * `SqlServerConnection` usando la cadena PDO `dblib:` cuando una
     * conexión configurada usa `driver=dblib`.
     *
     * @return void
     */
    public function register(): void
    {
        // Registrar driver dblib para SQL Server via FreeTDS
        DB::extend('dblib', function($config) {
            return new \Illuminate\Database\SqlServerConnection(
                new \PDO(
                    'dblib:host=' . $config['host'] . ':' . ($config['port'] ?? 1433) . ';dbname=' . $config['database'],
                    $config['username'] ?? '',
                    $config['password'] ?? '',
                    [
                        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                        \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                    ]
                ),
                $config['database'] ?? 'test',
                $config['prefix'] ?? '',
                $config
            );
        });
    }

    /**
     * Bootstrap de la aplicación: aplica la configuración por defecto.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->configureDefaults();
    }

    /**
     * Aplica defaults globales dependientes del entorno.
     *
     * - Usa `CarbonImmutable` para todas las fechas creadas por Laravel.
     * - Previene lazy loading Eloquent en entornos no productivos (arroja
     *   `LazyLoadingViolationException` cuando se detectan N+1).
     * - En producción prohíbe comandos destructivos (`migrate:fresh`, etc.).
     * - Define la política de contraseñas: sólo restrictiva en producción.
     *
     * @return void
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        Model::preventLazyLoading(! app()->isProduction());

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }
}
