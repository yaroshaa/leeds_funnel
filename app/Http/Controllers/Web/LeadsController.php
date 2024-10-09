<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Services\Paginator;
use Carbon\Carbon;
use DB;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class LeadsController extends Controller
{
    private Paginator $paginator;

    public function __construct(Paginator $paginator)
    {
        $this->paginator = $paginator;
    }

    public function pipedrive(Request $request): View
    {
        $items = Lead::fromPipedrive()
            ->select(DB::raw('*, `deal_id` AS `deal`, DATE(`created_at`) AS `date`'))
            ->when($request->filled('dates'), function (Builder $builder) {
                $dates = explode(' ~ ', request('dates'));

                $builder->whereBetween('created_at', [
                    Carbon::createFromFormat('d.m.Y', $dates[0])->startOfDay(),
                    Carbon::createFromFormat('d.m.Y', $dates[1])->endOfDay(),
                ]);
            })
            ->when($request->filled('user'), function (Builder $builder) use ($request) {
                $builder->where('user_id', $request->input('user'));
            })
            ->orderByDesc('date')
            ->get()
            ->groupBy(request('group_by', 'date'));


        $items = $this->paginator->paginate($items, 7)->withQueryString();

        return view('dashboard.leads.pipedrive', compact('items'));
    }

    public function channels()
    {
        return view('dashboard.leads.channels');
    }
}
