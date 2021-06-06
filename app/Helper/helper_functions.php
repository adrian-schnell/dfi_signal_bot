<?php

if (!function_exists('getTelegramBotLink')) {
    function getTelegramBotLink(): string
    {
        return sprintf('%s%s', config('telegram.base_uri'), config('telegram.names.bot'));
    }
}

if (!function_exists('getTelegramAdrianLink')) {
    function getTelegramAdrianLink(): string
    {
        return sprintf('%s%s', config('telegram.base_uri'), config('telegram.names.adrian'));
    }
}
