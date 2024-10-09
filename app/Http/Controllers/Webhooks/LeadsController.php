<?php

namespace App\Http\Controllers\Webhooks;

use App\Http\Controllers\Controller;
use App\Http\Resources\FromFacebookLeadResource;
use App\Http\Resources\FromIntercomLeadResource;
use App\Http\Resources\LeadgenResource;
use App\Services\LeadCreation;
use Illuminate\Http\Request;

class LeadsController extends Controller
{
    private LeadCreation $leadCreationService;

    public function __construct(LeadCreation $leadCreationService)
    {
        $this->leadCreationService = $leadCreationService;
    }

    public function leadgen(Request $request): string
    {
        $leadgenResource = (new LeadgenResource)->toArray($request);
        $this->leadCreationService->fromLeadgen($leadgenResource);

        return 'OK';
    }

    public function facebook(Request $request): string
    {
//        return $request->get('hub_challenge'); //TODO For start  webhook from Facebook

        $facebookResource = (new FromFacebookLeadResource)->toArray($request);
        $this->leadCreationService->fromFacebook($facebookResource);

        return 'OK';
    }

    public function intercom(Request $request): string
    {
        $canProcess = ($request->input('data.item.tag.name') === 'Sophia' && $request->input('delivery_status') === 'pending');
        $intercomResource = (new FromIntercomLeadResource)->toArray($request);

        if ($canProcess) {
            $this->leadCreationService->fromIntercom($intercomResource);
        }

        return 'OK';
    }
}
