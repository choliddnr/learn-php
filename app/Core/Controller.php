<?php

namespace App\Core;

class Controller
{
    public function view($view, $data = [])
    {
        if (count($data)) {
            extract($data);
        }
        require_once __DIR__ . "/../View/" . $view . ".php";
    }

    public function redirect($url)
    {

        header("Location: " . $url);
    }

    public function setFlashData($key, $value)
    {
        if (!session_id())
            session_start();
        $_SESSION['_flash'][$key] = $value;
    }

    public function getFlashData($key)
    {
        if (!session_id())
            session_start();
        $value = $_SESSION['_flash'][$key] ?? null;
        unset($_SESSION['_flash'][$key]);
        return $value;
    }
    public function clearFlashData()
    {
        if (!session_id())
            session_start();
        unset($_SESSION['_flash']);
    }

}