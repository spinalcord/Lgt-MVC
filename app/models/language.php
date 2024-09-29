<?php
namespace App\Models;

class Language
{
    
    public static $currentLanguage = 'en';  
    
    
    public static function setClientLanguage(string $language = null)
    {
        
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        
        if ($language) {
            $_SESSION['clientLanguage'] = $language;
        }
        
        
        if (!isset($_SESSION['clientLanguage'])) {
            $_SESSION['clientLanguage'] = DEFAULT_LANGUAGE;
        }

        
        self::$currentLanguage = $_SESSION['clientLanguage'];
    }

    public static function getTranslation(string $key, array $placeholders = []) : string
    {
        
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        
        self::setClientLanguage();

        
        $langArray = require LANGUAGE_FILES_PATH.trim(self::$currentLanguage).'.php';

        
        if (array_key_exists($key, $langArray)) {
            $translation = $langArray[$key];

            
            if (!empty($placeholders)) {
                $translation = vsprintf($translation, $placeholders);
            }

            
            $translation = str_replace('\n', "\n", $translation);

            return $translation;
        } else {
            return 'Missing Translation';
        }
    }
}
