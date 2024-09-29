<?php



function db() {
    return App\Models\Db::class;
} db()::connect();

function language() {
    return App\Models\Language::class;
}

function set($key, $value) {
    App\View::set($key, $value);
}

function render($template) {
    App\View::render($template);
}

function reroute($url) {
    Router::reroute($url);
}

function generateUniqueId($length = 12)
{
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $uniqueId = '';
    for ($i = 0; $i < $length; $i++) {
        $index = random_int(0, $charactersLength - 1);
        $uniqueId .= $characters[$index];
    }
    $uniqueId .= dechex(time());
    return $uniqueId;
}
