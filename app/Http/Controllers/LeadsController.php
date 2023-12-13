<?php

namespace App\Http\Controllers;

use App\Http\Requests\LeadRequest;
use App\Http\Requests\LeadNoteRequest;
use App\Http\Requests\SearchRequest;
use App\Mail\LeadAssignedToManager;
use App\Models\ContactType;
use App\Models\Lead;
use App\Models\LeadNote;
use App\Models\LeadSource;
use App\Models\Location;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

use Barryvdh\DomPDF\Facade\Pdf;

class LeadsController extends Controller
{
    public function index(Request $request)
    {
        $needle = $request->needle ?? null;
        $perPage = $request->perPage ?? 10;
        //don't show archived leads
        $leads = Lead::search($needle)->sortable('status_id')->where('status_id', '<>', '4')->with(['status', 'assignedTo', 'previousAssignedTo', 'lastNote'])->paginate($perPage);

        $data = [
            'leads'      => $leads,
            'managersCB' => User::managersCB(),
            'needle'     => $needle,
        ];

        return view('leads.index', $data);
    }

    public function search(SearchRequest $request)
    {
        return $this->index($request);
    }

    public function ajaxCheckIfLeadExists(Request $request)
    {
        if ($request->isMethod('post') && $request->ajax()) {

            $validator = \Validator::make(
                [
                    'first_name' => $request->first_name,
                    'last_name'  => $request->last_name,
                    'email'      => $request->email,
                ],
                [
                    'first_name' => 'required|personName',
                    'last_name'  => 'nullable|personName',
                    'email'      => 'required|email',
                ]
            );

            if ($validator->fails()) {
                $response = [
                    'success' => false,
                    'message' => $validator->messages()->first(),
                ];
            } else {
                $query = Lead::where('email', $request->email);

                if (!empty($request->last_name)) {
                    $query->where(function ($q) use ($request){
                        $q->orWhere('first_name', 'LIKE', '%' . $request->last_name . '%')->orWhere('last_name', 'LIKE', '%' . $request->last_name . '%');
                    });
                } else {
                    $query->where('first_name', 'LIKE', '%' . $request->last_name . '%');
                }

                if (!$leads = $query->get()) {
                    $response = [
                        'success' => true,
                        'leads'   => 0,
                    ];
                } else {
                    $html = '<h4 class="fs16">' . __('translation.existing_leads') . ' (' . $leads->count() . ')</h4>';
                    foreach ($leads as $number => $lead) {
                        $html .= '<p class="mt0 mb5 fs13">' . ($number + 1) . '- <a href="' . route('lead_details', ['lead' => $lead->id]) . '" class="a-link"><b>' . $lead->full_name . '</b></a><br>';
                        $html .= '<span class="pl17">' . $lead->full_address_one_line . '</span></p>';
                    }

                    $response = [
                        'success' => true,
                        'leads'   => $leads->count(),
                        'html'    => $html,
                    ];
                }
            }

            return response()->json($response, 200);
        } else {
            return response()->json(['error' => 'Invalid request.'], 500);
        }
    }


    public function storeNote(Lead $lead, LeadNoteRequest $request)
    {
        try {
            \DB::transaction(function () use ($lead, $request){
                $data = [
                    'lead_id'    => $lead->id,
                    'created_by' => auth()->user()->id,
                    'note'       => $request->note,
                ];

                LeadNote::create($data);

                if ($lead->status_id == 1) {
                    $lead->status_id = 2;
                    $lead->save();
                }
            });
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        if (!empty($this->returnTo)) {
            return redirect()->to($this->returnTo)->with('success', 'Lead note added.');
        } else {
            return redirect()->back()->with('success', 'Lead note added.');
        }
    }

    public function create()
    {
        $data = [
            'sources'      => LeadSource::sourcesCB(['0' => 'Select source']),
            'managersCB'   => User::allmanagersCB(['0' => 'Select previous assigned to']),
            'countiesCB'   => Location::countiesCB(['0' => 'Select County']),
            'typesCB' => ContactType::typesCB(['0' => 'Lead Is']),
            'lead_source'  => null,
            'assigned_to'  => null,
            'county'       => null,
        ];

        return view('leads.create', $data);
    }

    public function store(LeadRequest $request)
    {
        $inputs = $request->all();

        $inputs['created_by'] = auth()->user()->id;
        $inputs['status_id'] = 1;

        Lead::create($inputs);

        if (!empty($this->returnTo)) {
            return redirect()->to($this->returnTo)->with('success', 'Lead Added.');
        } else {
            return redirect()->route('lead_list')->with('success', 'Lead Added.');
        }
    }

    public function details(Lead $lead)
    {

        $data = [
            'sources'      => LeadSource::sourcesCB(['0' => 'Select source']),
            'managersCB'   => User::managersCB(['0' => 'Select previous assigned to']),
            'countiesCB'   => Location::countiesCB(['' => 'Select county']),
            'lead_source'  => null,
            'assigned_to'  => null,
            'county'       => null,
        ];

        $data['lead'] = $lead;

        $data['lead_notes'] = LeadNote::where('lead_id', $lead->id);

        return view('leads.details', $data);
    }

    public function edit(Lead $lead)
    {
        $data = [
            'lead'      => $lead,
            'typesCB'      => ContactType::typesCB(),
            'managersCB'   => User::managersCB(['0' => 'Not Applicable']),
            'sources'    => LeadSource::sourcesCB(['0' => 'Select source']),
            'assignedToCB' => Lead::assigneesCB(['0' => 'Select assigned to']),
            'countiesCB'   => Location::countiesCB(),
        ];

        return view('leads.edit', $data);
    }

    public function update(Lead $lead, LeadRequest $request)
    {
        $inputs = $request->all();

        $lead->update($inputs);

        if (!empty($this->returnTo)) {
            return redirect()->to($this->returnTo)->with('success', 'Lead updated.');
        } else {
            return redirect()->route('lead_list')->with('success', 'Lead updated.');
        }
    }

    public function assignTo(Lead $lead, Request $request)
    {
        $validator = \Validator::make(
            $request->all(), [
                'assigned_to' => 'required|positive',
            ]
        );
        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->messages()->first());
        }

        try {
            $lead->assigned_to = $request->assigned_to;
            $lead->save();
            Mail::to($lead->assignedTo->email)->send(new LeadAssignedToManager($lead));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->back()->with('success', 'Lead updated.');
    }

    public function archive(Lead $lead)
    {
        $lead->status_id = 4;
        $lead->save();

        return redirect()->back()->with('success', 'Lead archived.');
    }

    public function destroy(Request $request)
    {
        if (!$lead = Lead::find($request->item_id)) {
            return redirect()->back()->with('error', 'Lead not found.');
        }

        try {
            $fullName = $lead->full_name;
            $lead->delete();
        } catch (\Exception $e) {
            if (env('APP_ENV') == 'local') {
                return redirect()->back()->with('error', $e->getMessage());
            } else {
                \Log::error(get_class() . ' - ' . $e->getMessage());
                return redirect()->back()->with('error', 'Exception error');
            }
        }

        return redirect()->route('lead_list', !empty($request->http_query) ? explode('&', $request->http_query) : [])->with('success', 'Lead <b>' . $lead->full_name . '</b> deleted.');
    }

    public function restore(Lead $lead)
    {
        $lead->restore();

        return redirect()->back()->with('success', 'Lead <b>' . $lead->full_name . '</b> restored.');
    }


}
