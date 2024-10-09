<?php

namespace App\Services;

class Navigation
{
    public static function main(): array
    {
        return [
            [
                'name' => __('From pipedrive'),
                'route' => route('leads.pipedrive'),
                'match' => app('router')->is(['leads.pipedrive']),
            ],
            [
                'name' => __('Other channels'),
                'route' => route('leads.channels'),
                'match' => app('router')->is(['leads.channels']),
            ]
        ];
    }
}
