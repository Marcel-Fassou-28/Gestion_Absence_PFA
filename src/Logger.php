<?php

namespace App;

class Logger {

    // Fonction pour obtenir le chemin du fichier de log
    private static function getLogFile(string $block, int $level): string {
        $fileName = strtolower($block) . '.log';
        return dirname(__DIR__, $level) . DIRECTORY_SEPARATOR . "views" . DIRECTORY_SEPARATOR . "Logs" . DIRECTORY_SEPARATOR . $fileName;
    }

    // Fonction pour loguer les messages
    public static function log(string $message, int $level = 1, string $block = 'info', ?string $userId = null): void {
        $dateL = new \DateTime('now', new \DateTimeZone('Africa/Casablanca'));
        $date = $dateL->format('Y-m-d H:i:s');
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'CLI';
        $agent = $_SERVER['HTTP_USER_AGENT'] ?? 'CLI';
        $uid = $userId ?? 'guest';

        $logLine = "[$date][$block][user:$uid][$ip] $message (UA: $agent)" . PHP_EOL;
        file_put_contents(self::getLogFile($block, $level), $logLine, FILE_APPEND);
    }

    // Initialisation des gestionnaires d'erreurs et exceptions
    public static function initErrorHandlers()
    {
        // Gérer les erreurs PHP
        set_error_handler(function ($severity, $message, $file, $line) {
            self::log($message . ' in ' . $file . ' on ' . $line, 1, 'ERROR');
        });

        // Gérer les exceptions non capturées
        set_exception_handler(function ($exception) {
            self::log($exception->getMessage() . ' in ' . $exception->getFile() . ' on line ' . $exception->getLine(), 1, 'INFO');
        });

        // Gérer les erreurs fatales
        register_shutdown_function(function () {
            $error = error_get_last();
            if ($error && in_array($error['type'], [E_ERROR, E_PARSE])) {
                self::log( 'FATAL ERROR: ' .$error['message'] . ' in ' . $error['file'] . ' on line ' . $error['line'] . ' of type ' . $error['type'], 1, 'ERROR');
            }
        });
    }
}
