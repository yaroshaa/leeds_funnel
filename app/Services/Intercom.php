<?php

namespace App\Services;

use Http;
use Illuminate\Http\Client\PendingRequest;

class Intercom
{
    private PendingRequest $client;

    public function __construct()
    {
        $this->client = Http::withToken(config('intercom.access_token'))->withHeaders(['Accept' => 'application/json']);
    }

    public function conversation(int $id, array $query = [])
    {
        return $this->client->get(config('intercom.api_uri') . "/conversations/{$id}", $query)
            ->throw()->object();
    }

    public function findConversations($query): object
    {
        return $this->client->post(
            config('intercom.api_uri') . '/conversations/search',
            ['query' => $query]
        )->throw()->object();
    }

    public function createContact(array $data = [])
    {
        return $this->client->post(config('intercom.api_uri') . "/contacts/", $data)
            ->throw()
            ->object();
    }

    public function updateContact(string $id, array $data = [])
    {
        return $this->client->put(config('intercom.api_uri') . "/contacts/{$id}", $data)
            ->throw()
            ->object();
    }

    public function findContacts($query): object
    {
        return $this->client->post(
            config('intercom.api_uri') . '/contacts/search',
            ['query' => $query]
        )->throw()->object();
    }
}
