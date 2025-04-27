<?php

namespace App\Middlewares;

require_once __DIR__ . '/../core/Middleware.php';

use App\Core\Middleware;
use App\Services\SessionService;


class AuthMiddleware implements Middleware
{
    /**
     * This method is called before the controller action is executed.
     * It checks if the user is authenticated and redirects to the login page if not.
     */
    private SessionService $sessionService;
    public function __construct()
    {
        $this->sessionService = new SessionService();
    }
    public function before()
    {
        $session = $this->sessionService->current();
        if (!$session) {
            header('Location: /login');
            exit;
        }
    }

}