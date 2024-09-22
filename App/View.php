<?php
namespace App;

class View {
    private static $data = [];

    public static function set($key, $value) {
        self::$data[$key] = $value;
    }

    public static function render($template) {
        $file = __DIR__ . "/Views/$template.html";
        if (file_exists($file)) {
            extract(self::$data);
            ob_start();
            require $file;
            $content = ob_get_clean();
            
            echo $content;
        } else {
            echo "Template $template not found";
        }
    }
}

