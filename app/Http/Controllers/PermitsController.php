<?php

namespace App\Http\Controllers;

use App\Http\Requests\PermitNoteRequest;
use App\Http\Requests\PermitRequest;
use App\Http\Requests\SearchRequest;

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

        $data = [
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

    public function create()
    {
        $data = [

        ];

        return view('permit.create', $data);
    }

    public function store(PermitRequest $request)
    {
        $inputs = $request->all();

        $inputs['created_by'] = auth()->user()->id;
        $inputs['status_id'] = 1;

        Permit::create($inputs);

        if (!empty($this->returnTo)) {
            return redirect()->to($this->returnTo)->with('success', 'Permit Added.');
        } else {
            return redirect()->route('permit_list')->with('success', 'Permit Added.');
        }
    }

    public function details(Permit $permit)
    {
        $data = [
            'permit' => $permit->load(['notes' => function ($q){
                $q->with(['createdBy']);
            }, 'proposal', 'proposalDetail', 'createdBy']),
        ];
        $data['statusCB'] = $this->statusCB;

        return view('permit.details', $data);
    }

    public function edit(Permit $permit)
    {
        $data = ['permit'=>$permit];
        return view('permit.details', $data);
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

    public function destroy(Request $request)
    {
        if (!$permit = Permit::find($request->item_id)) {
            return redirect()->back()->with('error', 'Permit not found.');
        }

        try {
            $fullName = $permit->full_name;
            $permit->delete();
        } catch (\Exception $e) {
            if (env('APP_ENV') == 'local') {
                return redirect()->back()->with('error', $e->getMessage());
            } else {
                \Log::error(get_class() . ' - ' . $e->getMessage());
                return redirect()->back()->with('error', 'Exception error');
            }
        }

        return redirect()->route('permit_list', !empty($request->http_query) ? explode('&', $request->http_query) : [])->with('success', 'Permit <b>' . $permit->full_name . '</b> deleted.');
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

}
