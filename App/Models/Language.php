<?php
namespace App\Models;

class Language
{
    // Standard ist Englisch
    public static $defaultLanguage = 'en';
    
    // Speichert die aktuelle Sprache
    public static $currentLanguage = 'en';  // Hier die Deklaration
    
    // Setzt die aktuelle Sprache basierend auf der Session oder Standard
    public static function setClientLanguage(string $language = null)
    {
        // Session starten, falls noch nicht gestartet
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Wenn eine Sprache übergeben wurde, speichere sie in der Session
        if ($language) {
            $_SESSION['clientLanguage'] = $language;
        }
        
        // Wenn keine Sprache gesetzt ist, setze die Standardsprache
        if (!isset($_SESSION['clientLanguage'])) {
            $_SESSION['clientLanguage'] = self::$defaultLanguage;
        }

        // Aktualisiere die aktuelle Sprache
        self::$currentLanguage = $_SESSION['clientLanguage'];
    }

    public static function getTranslation(string $key, array $placeholders = []) : string
    {
        // Sicherstellen, dass die aktuelle Sprache gesetzt ist
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Setze die aktuelle Sprache, falls nicht schon gesetzt
        self::setClientLanguage();

        // Lade das Sprach-Array
        $langArray = require 'App/Languages/'.trim(self::$currentLanguage).'.php';

        // Prüfe, ob der Schlüssel existiert
        if (array_key_exists($key, $langArray)) {
            $translation = $langArray[$key];

            // Ersetze die Platzhalter
            if (!empty($placeholders)) {
                $translation = vsprintf($translation, $placeholders);
            }

            // Ersetze \n durch ein echtes Zeilenumbruchzeichen
            $translation = str_replace('\n', "\n", $translation);

            return $translation;
        } else {
            return 'Missing Translation';
        }
    }
}
