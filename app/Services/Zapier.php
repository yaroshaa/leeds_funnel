<?php

namespace App\Services;

class Zapier
{

    public function __construct()
    {
    }

    /**
     * @param array $data
     * @return object
     */
    public function triggerZapier(array $data = []): object
    {
        $ch = curl_init();
        $dataJson = json_encode($data, JSON_UNESCAPED_UNICODE);
        curl_setopt($ch, CURLOPT_URL, 'https://hooks.zapier.com/hooks/catch/2027976/onfic6l');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
        curl_setopt($ch, CURLOPT_POSTFIELDS , $dataJson);

        $response = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        $response = $err ? "Error :" . $err : json_decode($response);

        return $response;
    }

}
