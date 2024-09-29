<?php
// Autoloader für Controller und andere Klassen
spl_autoload_register(function ($class) {
    // Basisverzeichnis festlegen (das Verzeichnis, in dem der Autoloader liegt)
    $directory = __DIR__ . '/';
    
    // Den Klassennamen in einen relativen Dateipfad umwandeln
    $relativePath = str_replace('\\', '/', $class) . '.php';
    
    // Funktion, die rekursiv nach Dateien sucht, unabhängig von der Groß-/Kleinschreibung
    $findFileCaseInsensitive = function($directory, $relativePath) {
        $parts = explode('/', $relativePath);
        foreach ($parts as $part) {
            // Alle Dateien/Ordner im aktuellen Verzeichnis auflisten (case-insensitive)
            $found = false;
            $items = scandir($directory);
            foreach ($items as $item) {
                if (strcasecmp($item, $part) === 0) {
                    // Wenn ein passendes Verzeichnis oder Datei gefunden wird
                    $directory .= $item . '/';
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                return false; // Datei oder Verzeichnis nicht gefunden
            }
        }
        return rtrim($directory, '/'); // Vollständigen Pfad zurückgeben
    };

    // Den richtigen Pfad finden, unabhängig von der Groß-/Kleinschreibung
    $filePath = $findFileCaseInsensitive($directory, $relativePath);
    
    // Prüfen, ob die Datei existiert
    if ($filePath && file_exists($filePath)) {
        require $filePath;
    } else {
        // Falls die Datei nicht gefunden wird, eine Exception werfen
        throw new Exception("Class $class not found");
    }
});

