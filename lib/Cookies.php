<?php
class Cookies {

    static function deleteCookies()
    {
        setcookie("token", "", time() - 3600);
        setcookie("id", "", time() - 3600);
    }
    static function redirectPage($page = '')
    {
        if (!empty($_COOKIE["token"]) || !empty($_COOKIE["id"])) {
            header('Location: /' . $page, true, 307);
            die();
        }
    }
    static function emptyCookie()
    {
        if (empty($_COOKIE["token"]) || empty($_COOKIE["id"])) {
            Cookies::deleteCookies();
            header('Location: /', true, 307);
            die();
        }
    }
}