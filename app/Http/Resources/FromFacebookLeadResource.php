<?php

namespace App\Http\Resources;

use Carbon\Carbon;

class FromFacebookLeadResource
{
    const FACEBOOK = 'Facebook';

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'user_id' => null,
            'vip' => 'NO',
            'start_time' => '',
            'programme_level' => '',
            'program' => $request->input("what's_the_programme_you_want_to_study?") ?? '',
            'name' => $request->input('first_name') ?? '',
            'last_name' => $request->input('last_name') ?? '',
            'email' => $request->input('email') ?? '',
            'phone' => $request->input('phone') ?? '',
            'budget' => '',
            'started_application' => '',
            'nationality' => '',
            'applied_unis' => '',
            'date' => Carbon::now()->format('d/m/Y H:i:s') ?? '',
            'grade' => 'C',
            'continent' => '',
            'country' => $request->input('country') ?? '',
            'city' => '',
            'university' => '',
            'discipline' => '',
            'speciality' => '',
            'uni_location' => '',
            'education_level' => '',
            'funding' => '',
            'social_profile_url' => '',
            'questions' => '',
            'more_info' => '',
            'tracking' => self::FACEBOOK,
            'form_type' => 'General',
            'form_id' => null,
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
            'landing_page' => self::FACEBOOK,
            'conversion_page' => self::FACEBOOK,
            'ga_client_id' => '',
            'referrer' => '',
            'self_funded' => '',
            'mail_subject' => '',
            'suitability' => '',
            'educational_level' => '',
            'lead_type' => '',
            'cta_clicked' => self::FACEBOOK,
            'owner_id' => 0,
            'old_budget' => null,
            'old_nationality' => '',
            'qualifier' => '',
        ];
    }
}
