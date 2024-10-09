<?php

namespace App\Services;

use Http;

/**
 * Http client to connect with Pipedrive
 *
 * @package App\Services
 */
class Pipedrive
{
    private string $path;

    private array $startParams = [];
    private array $params = [];

    /* Deals */

    public function deals(array $params = []): Pipedrive
    {
        $this->path = config('pipedrive.api_url') . "/deals";
        $this->startParams = $params;

        return $this;
    }

    public function deal(int $id, array $params = []): Pipedrive
    {
        $this->path = config('pipedrive.api_url') . "/deals/{$id}";
        $this->startParams = $params;

        return $this;
    }

    public function dealFollowers(int $id): Pipedrive
    {
        $this->path = config('pipedrive.api_url') . "/deals/{$id}/followers";

        return $this;
    }

    public function dealFields(array $params = []): Pipedrive
    {
        $this->path = config('pipedrive.api_url') . "/dealFields";
        $this->startParams = $params;

        return $this;
    }

    /* Notes */

    public function notes($params = []): Pipedrive
    {
        $this->path = config('pipedrive.api_url') . "/notes";
        $this->startParams = $params;

        return $this;
    }

    /* People */
    public function addPeople(): Pipedrive
    {
        $this->path = config('pipedrive.api_url') . '/persons/';
        $this->startParams = [];

        return $this;
    }


    public function people(string $suffix = '', array $params = []): Pipedrive
    {
        $this->path = config('pipedrive.api_url') . '/persons/' . trim($suffix, '/');
        $this->startParams = $params;

        return $this;
    }

    public function person(int $id, array $params = []): Pipedrive
    {
        $this->path = config('pipedrive.api_url') . "/persons/{$id}";
        $this->startParams = $params;

        return $this;
    }

    public function personFollowers(int $id): Pipedrive
    {
        $this->path = config('pipedrive.api_url') . "/persons/{$id}/followers";

        return $this;
    }

    public function personDeals(int $id): Pipedrive
    {
        $this->path = config('pipedrive.api_url') . "/persons/{$id}/deals";

        return $this;
    }

    public function personFields(array $params = []): Pipedrive
    {
        $this->path = config('pipedrive.api_url') . '/personFields';
        $this->startParams = $params;

        return $this;
    }

    /* Stages */

    public function stages(array $params = []): Pipedrive
    {
        $this->path = config('pipedrive.api_url') . "/stages";
        $this->startParams = $params;

        return $this;
    }

    /* Users */

    public function users(array $params = []): Pipedrive
    {
        $this->path = config('pipedrive.api_url') . "/users";
        $this->startParams = $params;

        return $this;
    }

    /** Http client */

    public function get()
    {
        $params = $this->startParams + ['api_token' => config('pipedrive.api_key')];
        return Http::get($this->path, $params)->throw()->object();
    }

    public function post($params = [])
    {
        $this->params = $this->startParams + $params;
        return Http::post($this->path . '?api_token=' . config('pipedrive.api_key'), $this->params)->throw()->object();
    }

    public function put($params = [])
    {
        $this->params = $this->startParams + $params;
        return Http::put($this->path . '?api_token=' . config('pipedrive.api_key'), $this->params)->throw()->object();
    }

    public function followersDelete($params = []) // TODO  Именно так,чтобы не удалиль случайно саму сделку  в процессе разработки!!!!!
    {
        $this->params = $this->startParams + $params;
        return Http::delete($this->path . '?api_token=' . config('pipedrive.api_key'), $this->params)->throw()->object();
    }
}
