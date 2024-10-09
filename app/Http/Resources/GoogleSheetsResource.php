<?php

namespace App\Http\Resources;

use Illuminate\Support\Carbon;

class GoogleSheetsResource
{
    /**
     * Transform the resource into an array.
     *
     * @param array
     * @return array
     */
    public function toArray(array $data)
    {
        return [
            'date' => $data['date'],
            'name' => $data['name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'continent' => $data['continent'], // Continent
            'country' => $data['country'],
            'city' => $data['city'], // City
            'university' => $data['university'], // University
            'discipline' => $data['discipline'], // Discipline
            'speciality' => $data['speciality'], // Speciality
            'program' => $data['program'], // Programme
            'uni_location' => $data['uni_location'], // Uni location
            'education_level' => $data['education_level'], // Education level
            'start_time' => $data['start_time'], // Program Start Time
            'funding' => $data['funding'], // Funding
            'social_profile_url' => $data['social_profile_url'], // Social Profile URL
            'questions' => $data['questions'], // Questions
            'more_info' => $data['more_info'], // More Info
            'tracking' => $data['tracking'], // Tracking
            'form_type' => $data['form_type'], // Form Type
            'edu_notes' => $data['edu_notes'], // EDU Notes
            'grade' => $data['grade'], // Grade
            'points' => $data['points'], // Points
            'pt_country' => $data['pt_country'], // Pt-country
            'pt_level' => $data['pt_level'], // Pt-level
            'pt_program' => $data['pt_program'], // Pt-program
            'pt_time' => $data['pt_time'], // Pt-time
            'pt_funded' => $data['pt_funded'], // Pt-funded
            'pt_url' => $data['pt_url'], // Pt-url
            'pt_quest' => $data['pt_quest'], // Pt-quest
            'representative' => $data['representative'], // Representative
            'affiliate_visit_id' => $data['affiliate_visit_id'], // Affiliate Visit ID
            'landing_page' => $data['landing_page'], // Landing Page
            'conversion_page' => $data['conversion_page'], // Conversion Page
            'ga_client_id' => $data['ga_client_id'], // GA Client_id
        ];
    }
}
