<?php


namespace App\Services;


use Http;

class ApiLayer
{
    public function validate(string $phone): object
    {
        return Http::get(config('apilayer.api_uri'), [
            'access_key' => config('apilayer.api_key'),
            'number' => $phone,
        ])->throw()->object();
    }
}
