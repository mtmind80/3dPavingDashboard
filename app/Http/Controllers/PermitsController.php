<?php

namespace App\Http\Controllers;

use App\Http\Requests\PermitNoteRequest;
use App\Http\Requests\PermitRequest;
use App\Http\Requests\SearchRequest;
use Illuminate\Support\Facades\DB;
use App\Models\Proposal;
use App\Models\County;
use App\Models\Permit;
use App\Models\PermitNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use NumberFormatter;

class PermitsController extends Controller
{

    public $statusCB = [
        'Not Submitted' => 'Not Submitted',
        'Submitted'     => 'Submitted',
        'Under Review'  => 'Under Review',
        'Approved'      => 'Approved',
        'Comments'      => 'Comments',
    ];

    public function index(Request $request)
    {
        $needle = $request->needle ?? null;
        $perPage = $request->perPage ?? 10;

        $permits = Permit::incomplete()
            ->search($needle)
            ->sortable()
            ->with(['proposal'])
            ->paginate($perPage);


        $counties = DB::table('counties')->groupBy('county')->get(['county']);


        $data = [
            'counties'  => $counties,
            'permits'  => $permits,
            'statusCB' => $this->statusCB,
            'needle'   => $needle,
        ];

        return view('permit.index', $data);
    }

    public function search(SearchRequest $request)
    {
        return $this->index($request);
    }

    public function storeNote(Permit $permit, PermitNoteRequest $request)
    {
        try {
            \DB::transaction(function () use ($permit, $request){
                $data = [
                    'permit_id'  => $permit->id,
                    'created_by' => auth()->user()->id,
                    'note'       => $request->note,
                    'fee'        => $request->fee,
                ];

                PermitNote::create($data);
            });
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

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
        $counties = DB::table('counties')->groupBy('county')->orderBy('county')->get(['county']);
        $data['counties'] = $counties;

        return view('permit.create', $data);
    }

    public function store(PermitRequest $request)
    {
        $inputs = $request->all();

        Permit::create($inputs);

        if (!empty($this->returnTo)) {
            return redirect()->to($this->returnTo)->with('success', 'Permit Added.');
        } else {
            return redirect()->route('show_workorder', ['id'=>$inputs['proposal_id']])->with('success', 'Permit Added.');
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
        } else if ($permit instanceof Permit){
            $permit->load([
                'proposal',
                'proposalDetail',
                'createdBy',
                'notes' => fn($q) => $q->with(['createdBy'])
            ]);
        }

        $countiesCB = County::countiesCB();

        $citiesCB = County::citiesCB($permit->county);

        $data = [
            'permit' => $permit,
            'statusCB' => $this->statusCB,
            'countiesCB' => $countiesCB,
            'citiesCB' => $citiesCB,
        ];

        return view('permit.edit', $data);
    }

    public function editX(Permit $permit)
    {
        $data = ['permit'=>$permit];
        return view('permit.edit', $data);
    }

    public function update(Permit $permit, PermitRequest $request)
    {
        $inputs = $request->all();

        $permit->update($inputs);

        if (!empty($this->returnTo)) {
            return redirect()->to($this->returnTo)->with('success', 'Permit updated.');
        } else {
            return redirect()->route('permit_list')->with('success', 'Permit updated.');
        }
    }

    public function changeStatus(Permit $permit, Request $request)
    {
        $validator = Validator::make(
            $request->only(['new_status']), [
                'status' => [
                    Rule::in(['Approved', 'Completed', 'Not Submitted', 'Submitted', 'Under Review']),
                ],
            ]
        );
        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->messages()->first());
        }

        $permit->status = $request->new_status;
        $permit->save();

        return redirect()->back()->with('success', 'Permit status updated.');
    }

    public function destroy($id)
    {
        if (!$permit = Permit::where('id','=', $id)->with('proposal')->first()) {
            return redirect()->back()->with('error', 'Permit not found.');
        }

        $id = $permit->proposal_id;
        try {
            $permit->delete();
        } catch (\Exception $e) {
                return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->route('show_workorder',['id'=>$id])->with('success', 'Permit  deleted.');
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
                    'success' => false, 'error' => $validator->messages()->first(),
                ]);
            }
            if (!$permit = Permit::with(['notes' => function ($q){
                $q->with(['createdBy'])->orderBy('created_at', 'DESC');
            }])->find($request->permit_id)) {
                return response()->json([
                    'success' => false, 'error' => 'Permit not found.',
                ]);
            }

            if (!$notes = $permit->notes) {
                return response()->json([
                    'success' => false, 'error' => 'Permit does not have any note.',
                ]);
            }

            $currencyFormater = new NumberFormatter(app()->getLocale() . "_US", NumberFormatter::CURRENCY);

            $data = [];

            foreach ($notes as $note) {
                $data[] = [
                    'date_creator' => $note->date_creator,
                    'fee'          => $currencyFormater->formatCurrency($note->fee, 'USD'),
                    'content'      => $note->note,
                ];
            }

            $response = [
                'success'    => true,
                'notes'      => $data,
                'total_fees' => $currencyFormater->formatCurrency($notes->sum('fee'), 'USD'),
            ];
        } else {
            $response = ['success' => false, 'error' => 'Invalid request.'];
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
