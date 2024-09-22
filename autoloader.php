<?php
// Autoloader für Controller und andere Klassen
spl_autoload_register(function ($class) {
    // Der Pfad wird anhand des Namespaces der Klasse ermittelt
    $file = __DIR__ . '/' . str_replace('\\', '/', $class) . '.php';
    
    // Prüfen, ob die Datei existiert
    if (file_exists($file)) {
        require $file;
    } else {
        // Falls die Datei nicht gefunden wird, eine Exception werfen
        throw new Exception("Class $class not found in $file");
    }
});