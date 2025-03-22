<?php

namespace SlashTrace;

class Level
{
    const DEBUG = "debug";
    const INFO = "info";
    const WARNING = "warning";
    const ERROR = "error";

    /**
     * @param $level
     * @return bool
     */
    public static function isFatal($level)
    {
        return in_array($level, [
            E_ERROR,
            E_PARSE,
            E_CORE_ERROR,
            E_COMPILE_ERROR,
            E_CORE_WARNING,
            E_COMPILE_WARNING
        ]);
    }

    /**
     * Converts PHP error constants to custom log level
     *
     * @param $severity
     * @return string
     */
    public static function severityToLevel($severity)
    {
        return match ($severity) {
            E_USER_NOTICE, E_NOTICE, E_WARNING, E_CORE_WARNING, E_COMPILE_WARNING, E_USER_WARNING, E_DEPRECATED, E_USER_DEPRECATED => self::WARNING,
            default => self::ERROR,
        };
    }
}
