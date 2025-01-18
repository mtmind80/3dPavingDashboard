<?php

namespace App\Http\Controllers;

use App\Http\Requests\PermitNoteRequest;
use App\Http\Requests\PermitRequest;
use App\Http\Requests\SearchRequest;
use App\Mail\PermitUpdateToManager;
use App\Notifications\ProposalPermitNotification;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use App\Models\Proposal;
use App\Models\County;
use App\Models\Permit;
use App\Models\PermitNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use NumberFormatter;
use \App\Helpers\EnumFieldValues;

class PermitsController extends Controller
{


    public $statusCB;
    public $typesCB;

    public function __construct()
    {
        parent::__construct();
        //EnumFieldValues::get($table, $column);
        $this->statusCB = EnumFieldValues::get('permits', 'status');
        $this->typesCB = EnumFieldValues::get('permits', 'type');

    }


    public function index(Request $request)
    {
        $needle = $request->needle ?? null;
        $perPage = $request->perPage ?? 10;

        /*

         $permits = Permit::incomplete()
            ->search($needle)
            ->sortable()
            ->with(['proposal'])
            ->paginate($perPage);
        */


        if (auth()->user()->isAdmin()) {
            /*
            $permits = DB::table('permits')
                ->join('proposals','proposals.id','=','permits.proposal_id')
                ->selectRaw( 'permits.*, proposals.name, proposals.job_master_id, proposals.salesperson_id')
                ->where('permits.status', '<>', 'Approved')
                ->get();
            */

            $permits = Permit::notApproved()->with(['proposal', 'notes'])->get();

        } else {
            /*
            $permits = DB::table('permits')
                ->join('proposals','proposals.id','=','permits.proposal_id')
                ->selectRaw( 'permits.*, proposals.name, proposals.job_master_id, proposals.salesperson_id')
                ->where('permits.status', '<>', 'Approved')
                ->where('proposals.salesperson_id', '=', auth()->user()->id)
                ->get();
            */

            $permits = Permit::notApproved()
                ->where('salesperson_id', auth()->user()->id)
                ->with(['proposal', 'notes'])
                ->get();
        }

        //dd($permits);
        $counties = County::where('state', '=', 'FL')->orderBy('county')->groupBy('county')->get(['county']);


        $data = [
            'counties' => $counties,
            'permits' => $permits,
            'statusCB' => $this->statusCB,
            'needle' => $needle,
        ];

        return view('permit.index', $data);
    }

    public function search(SearchRequest $request)
    {
        return $this->index($request);
    }

    public function storeNote(Permit $permit, PermitNoteRequest $request)
    {
        if ($permit->id === null) {
            if (! $permit = Permit::find($request->permit_id)) {
                return redirect()->back()->with('error', 'Permit not found.');
            }
        }

        try {
            DB::transaction(function () use ($permit, $request){
                $data = [
                    'permit_id' => $permit->id,
                    'created_by' => auth()->user()->id,
                    'note' => $request->note,
                    'fee' => $request->fee,
                ];

                PermitNote::create($data);
            });
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        $permit->load(['notes', 'proposal' => fn($q) => $q->with(['salesPerson'])]);

        $permit->proposal->salesperson->notify(new ProposalPermitNotification($permit));

        if (!empty($this->returnTo)) {
            return redirect()->to($this->returnTo)->with('success', 'Permit note added.');
        } else {
            return redirect()->back()->with('success', 'Permit note added.');
        }
    }

    public function create($id)
    {
        $data['id'] = $id;
        $data['proposal'] = Proposal::where('id', '=', $id)->first();
        $data['statusCB'] = $this->statusCB;
        $counties = DB::table('florida_counties')->groupBy('county')->orderBy('county')->get(['county']);
        $data['counties'] = $counties;
        $data['cert_holders'] = ['APEX', '3D', 'NONE'];

        $data['statuses'] = $this->statusCB;
        $data['types'] = $this->typesCB;


        return view('permit.create', $data);
    }

    public function store(PermitRequest $request)
    {
        $inputs = $request->all();
        if (isset($inputs['submitted_on']) && $inputs['submitted_on'] <> '') {
            $inputs['submitted_on'] = Carbon::createFromFormat('m/d/Y', $request->submitted_on)->format('Y-m-d');
        }
        if (isset($inputs['expires_on']) && $inputs['expires_on'] <> '') {
            $inputs['expires_on'] = Carbon::createFromFormat('m/d/Y', $request->expires_on)->format('Y-m-d');
        }
        Permit::create($inputs);

        if (!empty($this->returnTo)) {
            return redirect()->to($this->returnTo)->with('success', 'Permit Added.');
        } else {
            return redirect()->route('show_workorder', ['id' => $inputs['proposal_id']])->with('success', 'Permit Added.');
        }
    }

    public function edit($permit)
    {
        if (is_numeric($permit)) {
            $permit = Permit::with([
                'proposal',
                'proposalDetail',
                'createdBy',
                'notes' => fn($q) => $q->with(['createdBy'])
            ])->find($permit);
        } else if ($permit instanceof Permit) {
            $permit->load([
                'proposal',
                'proposalDetail',
                'createdBy',
                'notes' => fn($q) => $q->with(['createdBy'])
            ]);
        }

        //("submitted_on") = '1/1/2024';
        $citiesCB = ['0' => 'Select a County'];
        $countiesCB = County::countiesCB();
        if ($permit) {
            $citiesCB = County::citiesCB($permit->county);
        }

        $data = [
            'permit' => $permit,
            'cert_holders' => ['APEX', '3D', 'NONE'],
            'statusCB' => $this->statusCB,
            'countiesCB' => $countiesCB,
            'citiesCB' => $citiesCB,
            'statuses' => $this->statusCB,
            'types' => $this->typesCB
        ];

        return view('permit.edit', $data);
    }

    public function update(Permit $permit, PermitRequest $request)
    {
        $inputs = $request->all();

        if (isset($inputs['submitted_on']) && $inputs['submitted_on'] <> '') {
            $inputs['submitted_on'] = Carbon::createFromFormat('m/d/Y', $request->submitted_on)->format('Y-m-d');
        }
        if (isset($inputs['expires_on']) && $inputs['expires_on'] <> '') {
            $inputs['expires_on'] = Carbon::createFromFormat('m/d/Y', $request->expires_on)->format('Y-m-d');
        }

        //updated permit so get sames person email
        $proposal = Proposal::where('id', '=', $permit->proposal_id)->with(['salesPerson'])->first()->toArray();

        //        dd($inputs);

        if ($permit->update($inputs)) {
            $subject = 'You have received a notification from  ' . env('APP_NAME');
            $msg = "Permit Updated";

            Mail::to($proposal['sales_person']['email'])->send(new PermitUpdateToManager($permit, $subject));

        };

        if (!empty($this->returnTo)) {
            return redirect()->to($this->returnTo)->with('success', $msg);
        } else {
            return redirect()->route('permit_list')->with('success', $msg);
        }
    }

    public function changeStatus(Permit $permit, Request $request)
    {
        $validator = Validator::make(
            $request->only(['permit_id', 'new_status']), [
                'permit_id' => 'required|positive',
                'status' => [
                    Rule::in(['Approved', 'Comments', 'Not Submitted', 'Submitted', 'Under Review']),
                ],
            ]
        );
        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->messages()->first());
        }

        if ($permit->id === null) {
            if (! $permit = Permit::find($request->permit_id)) {
                return redirect()->back()->with('error', 'Permit not found.');
            }
        }

        $permit->status = $request->new_status;
        $permit->save();

        return redirect()->back()->with('success', 'Permit status updated.');
    }

    public function destroy($id)
    {
        if (!$permit = Permit::where('id', '=', $id)->with('proposal')->first()) {
            return redirect()->back()->with('error', 'Permit not found.');
        }

        $id = $permit->proposal_id;
        try {
            $permit->delete();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->route('show_workorder', ['id' => $id])->with('success', 'Permit  deleted.');
    }

    public function noteList(Request $request)
    {
        if ($request->isMethod('post') && $request->ajax()) {
            $validator = Validator::make(
                $request->only(['permit_id']), [
                    'permit_id' => 'required|positive',
                ]
            );
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'error' => $validator->messages()->first(),
                ]);
            }
            if (! $permit = Permit::with([
                'proposal',
                'notes' => fn($q) => $q->with(['createdBy'])->orderBy('created_at', 'DESC')
            ])->find($request->permit_id)
            ) {
                return response()->json([
                    'success' => false,
                    'error' => 'Permit not found.',
                ]);
            }

            if ($permit->notes->count() === 0) {
                return response()->json([
                    'success' => false,
                    'error' => 'Permit does not have any note.',
                ]);
            }

            $response = [
                'success' => true,
                'html' => view('partials.note_list', ['permit' => $permit])->render(),
            ];
        } else {
            $response = [
                'success' => false,
                'error' => 'Invalid request.'
            ];
        }

        return response()->json($response);
    }

    public function ajaxFetchCities(Request $request)
    {
        if ($request->isMethod('post') && $request->ajax()) {
            $validator = Validator::make(
                $request->only(['county']), [
                    'county' => 'required',
                ]
            );
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->messages()->first(),
                ]);
            }

            if ($citiesCB = County::citiesCB($request->county)) {
                return response()->json([
                    'success' => true,
                    'data' => $citiesCB,
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'No cities found.',
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Invalid request.',
            ]);
        }
    }

}