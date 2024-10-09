<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;

class LocationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = \Http::get('https://gist.githubusercontent.com/jstneti01/6ff783eb01dd890f95c8303909a5925e/raw/7e2e7249dd7200e048d82ec077b46c37077b1569/country_continent_match.json')->throw()->object();

        foreach ($json as $location) {
            Location::create([
                'name' => $location->countryName,
                'continent' => $location->continentName,
            ]);
        }
    }
}
