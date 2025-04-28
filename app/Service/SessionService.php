<?php

namespace App\Service;

use App\Domain\Session;
use App\Domain\User;
use App\Repository\SessionRepository;


class SessionService
{
    private SessionRepository $sessionRepository;
    public static string $cookie_name = 'X-TSURAYYA-SESSION-ID';

    public static int $user_id;
    public function __construct()
    {
        $this->sessionRepository = new SessionRepository();
    }
    public function create($userId): Session
    {
        // Generate a unique session ID (this is just an example, use a more secure method in production)
        $sessionId = bin2hex(random_bytes(16));

        // Store the session ID and user ID in the database or session storage
        // $_SESSION['id'] = $sessionId;
        // $_SESSION['user_id'] = $userId;

        $session = new Session();
        $session->id = $sessionId;
        $session->user_id = $userId;
        $this->sessionRepository->save($session);

        setcookie(self::$cookie_name, $sessionId, time() + (60 * 60 * 24 * 30), "/"); // 86400 = 1 day


        return $session;
    }

    public function current(): Session|null
    {
        $session_id = $_COOKIE[self::$cookie_name] ?? null;
        if (!$session_id)
            return null;
        $session = $this->sessionRepository->find($session_id);
        if (!$session) {
            return null;
        }
        self::$user_id = $session->user_id;
        return $session;

    }

    public function destroy(): void
    {
        $sessionid = $_COOKIE[self::$cookie_name] ?? null;
        if ($sessionid) {
            // Delete the session from the database
            $this->sessionRepository->delete($sessionid);
            setcookie(self::$cookie_name, '', time() - 3600, "/"); // Expire the cookie
            unset($_COOKIE[self::$cookie_name]); // Remove the cookie from the $_COOKIE superglobal
        }
    }
}