<?php
namespace App\Models;

class Language
{
    // Stores the current language
    public static $currentLanguage = 'en';  // Here is the declaration
    
    // Sets the current language based on the session or default
    public static function setClientLanguage(string $language = null)
    {
        // Start the session if it hasn't been started yet
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // If a language was passed, store it in the session
        if ($language) {
            $_SESSION['clientLanguage'] = $language;
        }
        
        // If no language is set, set the default language
        if (!isset($_SESSION['clientLanguage'])) {
            $_SESSION['clientLanguage'] = $GLOBALS['defaultLanguage'];
        }

        // Update the current language
        self::$currentLanguage = $_SESSION['clientLanguage'];
    }

    public static function getTranslation(string $key, array $placeholders = []) : string
    {
        // Ensure that the current language is set
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Set the current language if it hasn't been set already
        self::setClientLanguage();

        // Load the language array
        $langArray = require 'App/Languages/'.trim(self::$currentLanguage).'.php';

        // Check if the key exists
        if (array_key_exists($key, $langArray)) {
            $translation = $langArray[$key];

            // Replace the placeholders
            if (!empty($placeholders)) {
                $translation = vsprintf($translation, $placeholders);
            }

            // Replace \n with an actual newline character
            $translation = str_replace('\n', "\n", $translation);

            return $translation;
        } else {
            return 'Missing Translation';
        }
    }
}
