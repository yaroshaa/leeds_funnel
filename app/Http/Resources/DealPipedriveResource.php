<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class DealPipedriveResource
{
    /**
     * Transform the resource into an array.
     * @param array $data
     * @return array
     */
    public function toArray(array $data): array
    {
        $grade = $data['grade'] ?? 'C';
        $total = $data['pt_total'] ?? 0;
        $programmeLevel = $data['programme_level'] !== null
            ?  config("intercom.programme_level.{$data['programme_level']}")
            :  null ;

        return [
            config('pipedrive.fields.vip') => config("pipedrive.fields.vip_options.{$data['vip']}") ?? '',
            config('pipedrive.fields.grade') => $grade,
            config('pipedrive.fields.university_name') => $data['university'] ?? '',
            config('pipedrive.fields.discipline') => $data['discipline'] ?? '',
            config('pipedrive.fields.speciality') => $data['speciality'] ?? '',
            config('pipedrive.fields.education_level') => $data['educational_level'] ? config("pipedrive.fields.education_level_options.{$data['educational_level']}") : '',
            config('pipedrive.fields.start_of_study') => $data['start_time'] ?? '',
            config('pipedrive.fields.social_profile_url') => $data['social_profile_url'] ?? '',
            config('pipedrive.fields.pt_country') => $data['pt_country'] ?? 0,
            config('pipedrive.fields.pt_level') => $data['pt_level'] ?? 0,
            config('pipedrive.fields.pt_program') => $data['pt_program'] ?? 0,
            config('pipedrive.fields.pt_time') => $data['pt_time'] ?? 0,
            config('pipedrive.fields.pt_funds') => $data['pt_funded'] ?? 0,
            config('pipedrive.fields.affiliate') => $data['affiliate_visit_id'] ?? '',
            config('pipedrive.fields.landing_page') => $data['landing_page'] ?? '',
            config('pipedrive.fields.conversion_page') => $data['conversion_page'] ?? '',
            config('pipedrive.fields.ga_client_id') => $data['ga_client_id'] ?? '',
            config('pipedrive.fields.continent_from_filled_form') => $data['continent'] ?? '',
            config('pipedrive.fields.country_from_filled_form') => $data['country'] ?? '',
            config('pipedrive.fields.city_from_filled_form') => $data['city'] ?? '',
            config('pipedrive.fields.cta_clicked') => $data['cta_clicked'] ?? '',
            config('pipedrive.fields.lead_score') => $total,
            config('pipedrive.fields.locations_interested') => $data['uni_location'] ?? '',
            config('pipedrive.fields.datetime') => $data['date'] ?? '',
            config('pipedrive.fields.extra_info') => $grade.'.'.$total,
            config('pipedrive.fields.programme_from_filled_form') =>  $data['program'] ?? '',
            config('pipedrive.fields.funding_from_filled_form') =>  $data['self_funded'] ?? '',
            config('pipedrive.fields.pt_social_profile') =>  $data['pt_url'] ?? '',
            config('pipedrive.fields.old_budget') =>  $data['old_budget'] ?? '',
            config('pipedrive.fields.old_nationality') =>  $data['old_nationality'] ?? '',
            config('pipedrive.fields.qualifier') =>  $data['qualifier'] ?? '',
            config('pipedrive.fields.pt_questions') => $data['pt_quest'] ?? 0,
            config('pipedrive.fields.questions_asked_by_the_student') =>  $data['questions'] ?? '',
            config('pipedrive.fields.programme_level') =>  $programmeLevel ?? '',
        ];
    }
}
