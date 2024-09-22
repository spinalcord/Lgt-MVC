<?php
namespace App\Models;

class Language
{
    public static $currentLanguage = 'en';
    public static function getTranslation(string $key, array $placeholders = []) : string
    {
        $langArray = require 'App/Languages/'.trim(self::$currentLanguage).'.php';

        if (array_key_exists($key, $langArray)) {
            $translation = $langArray[$key];

            // Ersetze die Platzhalter im Format %s
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