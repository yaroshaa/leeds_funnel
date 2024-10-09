<?php

namespace App\Services;

class Clearout
{
    /**
     * @var false|resource
     */
    private $curl;

    public function __construct()
    {
        $this->curl = curl_init();
        curl_setopt_array($this->curl, array(
            CURLOPT_URL => 'https://api.clearout.io/v2/email_verify/instant',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "Authorization: Bearer:" . config('clearout.access_token')
            )
        ));
    }

    /**
     * @param string $email
     * @return object
     */
    public function email(string $email): object
    {
        curl_setopt_array($this->curl, array(
            CURLOPT_POSTFIELDS => '{"email": "' . $email . '"}'
        ));

        $response = curl_exec($this->curl);
        $err = curl_error($this->curl);
        curl_close($this->curl);
        $response = $err ? "Error :" . $err : json_decode($response);

        return $response;
    }
}
