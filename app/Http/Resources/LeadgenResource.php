<?php

namespace App\Http\Resources;

use Carbon\Carbon;

class LeadgenResource
{
    const LEADGEN = 'Leadgen';

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray(\Illuminate\Http\Request $request)
    {
        return [
            'user_id' => null,
            'vip' => $request->input('vip') ?? '',
            'start_time' => $request->input('programTime') ?? '',
            'programme_level' => '',
            'program' => $request->input('program') ?? '',
            'name' => $request->input('firstName') ?? '',
            'last_name' => $request->input('lastName') ?? '',
            'email' => $request->input('emailAddress') ?? '',
            'phone' => str_replace([' ', ' 0'], '', $request->input('telNumber')) ?? '',
            'budget' => '',
            'started_application' => '',
            'nationality' => '',
            'applied_unis' => '',
            'date' => Carbon::now()->format('d/m/Y H:i:s') ?? '',
            'grade' => $request->input('pt-grade') ?? 'C',
            'continent' => $request->input('leadContinent') ?? '',
            'country' => $request->input('country') ?? '',
            'city' => $request->input('city') ?? '',
            'university' => $request->input('university') ?? '',
            'discipline' => $request->input('discipline') ?? '',
            'speciality' => $request->input('leadSpeciality') ?? '',
            'uni_location' => $request->input('uniLocation') ?? '',
            'education_level' => '',
            'funding' => $request->input('selfFunded') ?? '',
            'social_profile_url' => $request->input('profileUrl') ?? '',
            'questions' => $request->input('questions'),
            'more_info' => '',
            'tracking' => $request->input('form-name') ?? '',
            'form_type' => $request->input('leadType') ?? '',
            'form_id' => '',
            'form_name' => $request->input('form-name') ?? '',
            'edu_notes' => '',
            'points' => $request->input('pt-total') ?? 0,
            'pt_country' => $request->input('pt-country') ?? 0,
            'pt_level' => $request->input('pt-level') ?? 0,
            'pt_program' => $request->input('pt-program') ?? 0,
            'pt_time' => $request->input('pt-time') ?? 0,
            'pt_funded' => $request->input('pt-funded') ?? 0,
            'pt_url' => $request->input('pt-url') ?? '',
            'pt_quest' => $request->input('pt-quest') ?? 0,
            'pt_grade' => $request->input('pt-grade') ?? '',
            'pt_total' => $request->input('pt-total') ?? 0,
            'representative' => '',
            'affiliate_visit_id' => '',
            'landing_page' => $request->input('landing_page') ?? '',
            'conversion_page' => $request->input('conversion-page') ?? '',
            'ga_client_id' => $request->input('ga-client-id') ?? '', // TODO ВОзможно тут нужен NULL
            'referrer' => '',
            'self_funded' => $request->input('selfFunded') ?? '',
            'mail_subject' => $request->input('mailSubject') ?? '',
            'suitability' => $request->input('suitability') ?? '',
            'educational_level' => $request->input('educationalLevel') ?? '',
            'lead_type' => $request->input('leadType') ?? '',
            'cta_clicked' => $request->input('form-name') ?? '',
            'owner_id' => 0,
            'old_budget' => '',
            'old_nationality' => '',
            'qualifier' => '',
        ];
    }
}
