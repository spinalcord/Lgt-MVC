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