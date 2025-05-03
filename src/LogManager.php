<?php

namespace App;

class LogManager
{
    public function getLogsByDateAndLevel(string $level, string $date): string {
        $filePath = dirname(__DIR__, 1) . "/views/Logs/" . strtolower($level) .".log";
        if (!file_exists($filePath)) return "Aucun log pour ce niveau.";

        $lines = file($filePath);
        $filtered = array_filter($lines, fn($line) => str_starts_with($line, "[$date"));

        return implode("", $filtered);
    }

    public function clearLogsFile(string $selectedLevel):void {
        $filePath = dirname(__DIR__, 1) . "/views/Logs/". strtolower($selectedLevel) .".log";
        if (file_exists($filePath)) {
            file_put_contents($filePath, '');
        }
    }
}