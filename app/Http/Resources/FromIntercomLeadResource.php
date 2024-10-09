<?php

namespace App\Http\Resources;

use Carbon\Carbon;

class FromIntercomLeadResource
{
    const CHATBOT = 'ChatBot';

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request): array
    {
        $key = $request->input('data.item.user') ? 'user' : 'contact';
        $continent_code = $request->input("data.item.{$key}.location_data.continent_code");
        $custom_attributes = $request->input("data.item.{$key}.custom_attributes");

        if (isset($continent_code) && in_array($continent_code, ['EU', 'NA', 'OC'])) {
            $vip = 'YES';
        } else {
            $vip = 'NO';
        }

        if (isset($continent_code) && array_key_exists($continent_code, config('continents'))) {
            $continent = config("continents.{$continent_code}");
        }

        if (isset($custom_attributes)) {
            if (isset($custom_attributes['Last name']) && $custom_attributes['Last name'] !== null) {
                $lastName = $custom_attributes['Last name'];
            }
            if (isset($custom_attributes['Start time']) && array_key_exists($custom_attributes['Start time'], config('start_time'))) {
                $start_time = config("start_time.{$custom_attributes['Start time']}");
            }
            if (isset($custom_attributes['Study Degree'])) {
                $programme_level = $custom_attributes['Study Degree'];
            }
        }

        $name = $request->input("data.item.{$key}.name");
        if($request->input("data.item.{$key}.name") !== null){
            if(mb_strpos($request->input("data.item.{$key}.name"), " ") !== false) {
                $arr_name = explode(" ", $request->input("data.item.{$key}.name"));
                $arr_name = array_filter($arr_name);
                $name = $arr_name[0];
            }
        }

        return [
            'user_id' => $request->input("data.item.{$key}.id") ?? '', // User Intercom
            'vip' => $vip ?? '',
            'start_time' => $start_time ?? '',
            'programme_level' => $programme_level ?? '',
            'program' =>  $custom_attributes['Programme'] ?? '',
            'name' => $name ?? '',
            'last_name' => $lastName ?? '',
            'email' => $request->input("data.item.{$key}.email") ?? '',
            'phone' => str_replace([' ', ' 0'], '', $request->input("data.item.{$key}.phone")) ?? '',
            'budget' => $custom_attributes['Budget'] ?? '',
            'started_application' => '',
            'nationality' => $custom_attributes['Nationality'] ?? '',
            'applied_unis' => '',
            'date' => Carbon::now()->format('d/m/Y H:i:s') ?? '',
            'grade' => 'C',
            'continent' => $continent ?? '',
            'country' => $request->input("data.item.{$key}.location_data.country_name") ?? '',
            'city' => $request->input("data.item.{$key}.location_data.city_name") ?? '',
            'university' => '',
            'discipline' => '',
            'speciality' => '',
            'uni_location' => $custom_attributes['Locations'] ?? '',
            'education_level' => '',
            'funding' => '',
            'social_profile_url' => '',
            'questions' => '',
            'more_info' => '',
            'tracking' => self::CHATBOT,
            'form_type' => 'General',
            'form_id' => '',
            'form_name' => '',
            'edu_notes' => '',
            'points' => 0,
            'pt_country' => 0,
            'pt_level' => 0,
            'pt_program' => 0,
            'pt_time' => 0,
            'pt_funded' => 0,
            'pt_url' => 0,
            'pt_quest' => 0,
            'pt_grade' => '',
            'pt_total' => 0,
            'representative' => '',
            'affiliate_visit_id' => '',
            'landing_page' => '',
            'conversion_page' => '/',
            'ga_client_id' => '',
            'referrer' => $request->input("data.item.{$key}.referrer") ?? '',
            'self_funded' => '',
            'mail_subject' => '',
            'suitability' => '',
            'educational_level' => 'Other',
            'lead_type' => '',
            'cta_clicked' => self::CHATBOT,
            'owner_id' => 0,
            'old_budget' => 0,
            'old_nationality' => $request->input("data.item.{$key}.location_data.country_name") ?? '',
            'qualifier' => '',

        ];
    }
}
