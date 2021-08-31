<?php

use App\Models\TelegramUser;
use Carbon\Carbon;
use Carbon\CarbonInterface;

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
        app()->setLocale(get_language($language));
    }
}

if (!function_exists('get_language')) {
    function get_language(string $language = 'en'): string
    {
        return in_array($language, ['en', 'de']) ? $language : 'en';
    }
}

if (!function_exists('time_diff_humanreadable')) {
    function time_diff_humanreadable(Carbon $a, Carbon $b, string $userLanguage, int $parts = 3): string
    {
        return $a
            ->locale(get_language($userLanguage))
            ->diffForHumans(
                $b,
                [
                    'options' => Carbon::TWO_DAY_WORDS | Carbon::ONE_DAY_WORDS,
                    'syntax'  => CarbonInterface::DIFF_ABSOLUTE,
                    'parts'   => $parts,
                    'join'    => true,
                    'aUnit'   => true,
                ]
            );
    }
}

if (!function_exists('progress_bar')) {
    function progress_bar(float $progress): string
    {
        $progress = round($progress, 2);
        return sprintf(
            '[%s%s] %s',
            str_repeat('=', $progress/4),
            str_repeat(' ', (100-$progress)/4),
            $progress . '%'
        );
    }
}
