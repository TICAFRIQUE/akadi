<?php

namespace App\Exceptions;

use Exception;
use Throwable;
use Illuminate\Http\Request;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        // $this->reportable(function (Throwable $e) {
        //     //
        // });

        $this->renderable(function (Exception $e) {
            // Redirection pour les erreurs de token CSRF expiré
            if ($e->getPrevious() instanceof TokenMismatchException) {
               return redirect()->route('auth.login')->with('error', 'Session Expired');
            }
        });
    }

    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $exception)
    {
        // Redirection pour les erreurs d'autorisation (403)
        if ($exception instanceof AuthorizationException) {
            // Vérifier si c'est une requête web (pas API)
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Vous n\'avez pas la permission d\'accéder à cette ressource.'
                ], 403);
            }
            
            return redirect()->route('admin-no-permission')
                ->with('error', 'Vous n\'avez pas la permission d\'accéder à cette ressource.');
        }

        return parent::render($request, $exception);
    }
}
