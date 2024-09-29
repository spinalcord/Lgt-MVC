<?php

spl_autoload_register(function ($class) {
    
    $directory = __DIR__ . '/';
    
    
    $relativePath = str_replace('\\', '/', $class) . '.php';
    
    
    $findFileCaseInsensitive = function($directory, $relativePath) {
        $parts = explode('/', $relativePath);
        foreach ($parts as $part) {
            
            $found = false;
            $items = scandir($directory);
            foreach ($items as $item) {
                if (strcasecmp($item, $part) === 0) {
                    
                    $directory .= $item . '/';
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                return false; 
            }
        }
        return rtrim($directory, '/'); 
    };

    
    $filePath = $findFileCaseInsensitive($directory, $relativePath);
    
    
    if ($filePath && file_exists($filePath)) {
        require $filePath;
    } else {
        
        throw new Exception("Class $class not found");
    }
});

