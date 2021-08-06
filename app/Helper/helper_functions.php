<?php

if (!function_exists('getTelegramBotLink')) {
    function getTelegramBotLink(): string
    {
        return sprintf('%s%s', config('telegram.base_uri'), config('telegram.names.bot'));
    }
}

if (!function_exists('getTelegramGroupLink')) {
    function getTelegramGroupLink(): string
    {
        return sprintf('%s%s', config('telegram.base_uri'), config('telegram.names.support_group'));
    }
}

if (!function_exists('str_truncate_middle')) {
    function str_truncate_middle(string $text, int $maxChars = 25, string $filler = '...'): string
    {
        $length = strlen($text);
        $fillerLength = strlen($filler);

        return ($length > $maxChars)
            ? substr_replace($text, $filler, ($maxChars - $fillerLength) / 2, $length - $maxChars + $fillerLength)
            : $text;
    }
}

if (!function_exists('set_language')) {
    function set_language(string $language = 'en'): void
    {
        app()->setLocale(in_array($language, ['en', 'de']) ? $language : 'en');
    }
}
