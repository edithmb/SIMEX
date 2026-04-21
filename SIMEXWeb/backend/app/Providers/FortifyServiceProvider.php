<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Laravel\Fortify\Features;
use Laravel\Fortify\Fortify;

/**
 * Service provider que integra Laravel Fortify con el frontend Inertia.
 *
 * Asigna las acciones personalizadas de creación/reset de usuarios, enlaza
 * las vistas Inertia de los flujos de autenticación (login, registro,
 * recuperación, 2FA…) y define los rate limiters `login` y `two-factor`.
 */
class FortifyServiceProvider extends ServiceProvider
{
    /**
     * No se registran bindings propios en el container.
     *
     * @return void
     */
    public function register(): void
    {
        //
    }

    /**
     * Arranca la configuración de Fortify.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->configureActions();
        $this->configureViews();
        $this->configureRateLimiting();
    }

    /**
     * Sustituye las acciones por defecto de Fortify por las personalizadas.
     *
     * @return void
     */
    private function configureActions(): void
    {
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);
        Fortify::createUsersUsing(CreateNewUser::class);
    }

    /**
     * Enlaza cada pantalla de autenticación de Fortify con su componente
     * Inertia correspondiente (login, reset, forgot, verify, register, 2FA,
     * confirm-password), propagando al frontend el estado de sesión y los
     * flags de features habilitadas.
     *
     * @return void
     */
    private function configureViews(): void
    {
        Fortify::loginView(fn (Request $request) => Inertia::render('auth/Login', [
            'canResetPassword' => Features::enabled(Features::resetPasswords()),
            'canRegister' => Features::enabled(Features::registration()),
            'status' => $request->session()->get('status'),
        ]));

        Fortify::resetPasswordView(fn (Request $request) => Inertia::render('auth/ResetPassword', [
            'email' => $request->email,
            'token' => $request->route('token'),
        ]));

        Fortify::requestPasswordResetLinkView(fn (Request $request) => Inertia::render('auth/ForgotPassword', [
            'status' => $request->session()->get('status'),
        ]));

        Fortify::verifyEmailView(fn (Request $request) => Inertia::render('auth/VerifyEmail', [
            'status' => $request->session()->get('status'),
        ]));

        Fortify::registerView(fn () => Inertia::render('auth/Register'));

        Fortify::twoFactorChallengeView(fn () => Inertia::render('auth/TwoFactorChallenge'));

        Fortify::confirmPasswordView(fn () => Inertia::render('auth/ConfirmPassword'));
    }

    /**
     * Define rate limiters para login y 2FA: 5 intentos/min.
     *
     * - `login`: clave compuesta por email (en minúsculas, transliterado)
     *   + IP; impide probar muchas contraseñas contra el mismo usuario y
     *   también distribuir el ataque.
     * - `two-factor`: clave por `login.id` almacenado en sesión.
     *
     * @return void
     */
    private function configureRateLimiting(): void
    {
        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });
    }
}
