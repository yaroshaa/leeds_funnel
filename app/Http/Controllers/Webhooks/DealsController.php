<?php

namespace App\Http\Controllers\Webhooks;

use App\Http\Controllers\Controller;
use App\Models\{Field, Stage};
use App\Repositories\FieldsRepository;
use App\Services\LeadQualification;
use App\Services\Pipedrive;
use Illuminate\Http\Request;

class DealsController extends Controller
{
    private FieldsRepository $fieldsRepository;

    private LeadQualification $leadQualificationService;

    /**
     * DealsController constructor.
     *
     * @param FieldsRepository $fieldsRepository
     * @param LeadQualification $leadQualificationService
     */
    public function __construct(FieldsRepository $fieldsRepository, LeadQualification $leadQualificationService)
    {
        $this->fieldsRepository = $fieldsRepository;
        $this->leadQualificationService = $leadQualificationService;
    }

    /**
     * @param Request $request
     * @return string
     */
    public function updated(Request $request): string
    {
        $canBeProcessed = config('app.env') === 'production';
        $studentStages = Stage::studentLeads()->pluck('pipedrive_id')->toArray();

        if (!$canBeProcessed) {
            $canBeProcessed = $request->input('current.id') == 10533;
        }

        if (in_array($request->input('current.stage_id'), $studentStages) && $canBeProcessed) {
            $this->leadQualificationService->setDeal($request->input('current'));
            $this->leadQualificationService->setUser($request->input('meta.user_id'));

            $qualification = $this->fieldsRepository->findOne(Field::QUALIFICATION);

            if ($request->input('current.' . $qualification->key) != $request->input('previous.' . $qualification->key)) {
                $this->leadQualificationService->init();
            }
        }

        return 'OK';
    }
}
