<?php

namespace App\Http\Controllers;

use App\Helpers\ExceptionError;
use App\Models\ProposalDetail;
use App\Models\ServiceSchedule;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProposalDetailSchedulesController extends Controller
{
    public function index($proposal_detail_id)
    {
        if (! $proposalDetail = ProposalDetail::with(['proposal', 'workOrder', 'schedule'])->find($proposal_detail_id)) {
            return view('pages-404');
        }

        if ($proposalDetail->workOrder === null && ! $proposalDetail->workOrder->canBeScheduled()) {
            return redirect()->back()->with('error', 'This workorder cannot be scheduled');
        }

        if (! $proposalDetail = ProposalDetail::with(['proposal', 'schedule'])->find($proposal_detail_id)) {
            return view('pages-404');
        }

        $data = [
            'proposalDetail' => $proposalDetail,
        ];

        return view('proposaldetails.schedule_service', $data);
    }

    public function ajaxAddOrUpdate(Request $request)
    {
        if ($request->isMethod('post') && $request->ajax()) {
            $validator = Validator::make(
                $request->only(['proposal_detail_id', 'schedule_id', 'title',  'from_date', 'from_time', 'to_date', 'to_time', 'note']), [
                    'proposal_detail_id' => 'required|positive',
                    'schedule_id' => 'nullable|positive',
                    'title' => 'required|plainText|max:100',
                    'from_date' => 'required|usDate',
                    'from_time' => 'required|time',
                    'to_date' => 'required|usDate',
                    'to_time' => 'required|time',
                    'note' => 'nullable|plainText|max:1000',
                ], [
                    'proposal_detail_id.required' => 'Unknown proposal id',
                    'schedule_id.positive' => 'Not a valid schedule id',
                    'title.required' => 'No title defined',
                    'title.plainText' => 'Invalid title',
                    'title.max' => 'Title can not be larger than 100 chars',
                    'from_date.required' => 'No from date value defined',
                    'from_date.usDate'  => 'Invalid from date entry',
                    'from_time.required' => 'No from time value defined',
                    'from_time.time' => 'Invalid from time entry',
                    'to_date.required' => 'No to date value defined',
                    'to_date.usDate' => 'Invalid to date entry',
                    'to_time.required' => 'No to time value defined',
                    'to_time.time' => 'Invalid to time entry',
                    'note.plainText' => 'Invalid note',
                    'note.max' => 'Note can not contain more than 1000 chars',
                ]
            );

            if ($validator->fails()) {
                $response = [
                    'success' => false,
                    'message' => $validator->messages()->first(),
                ];
            } else {
                try {
                    if (! $proposalDetail = ProposalDetail::with(['proposal'])->find($request->proposal_detail_id)) {
                        $response = [
                            'success' => false,
                            'message' => 'Service not found.',
                        ];
                    } else {
                        $title = $request->title;
                        $fromDate = $request->from_date;
                        $fromTime = $request->from_time;
                        $toDate = $request->to_date;
                        $toTime = $request->to_time;
                        $note = $request->note;

                        $startDate = Carbon::createFromFormat('m/d/Y h:i A', $fromDate . ' ' . $fromTime);
                        $endDate = Carbon::createFromFormat('m/d/Y h:i A', $toDate . ' ' . $toTime);

                        if ($startDate >= $endDate) {
                            $response = [
                                'success' => false,
                                'message' => 'Start date must be before end date.',
                            ];
                        } else {
                            $data = [
                                'title' => $title,
                                'start_date' => $startDate,
                                'end_date' => $endDate,
                                'note' => $note,
                                'updated_by' => auth()->user()->id,
                            ];

                            if ($request->schedule_id !== null) {
                                // update
                                $serviceSchedule = ServiceSchedule::find($request->schedule_id);
                                $serviceSchedule->update($data);
                                $msg = 'Schedule updated.';
                            } else {
                                // add new
                                $data['proposal_detail_id'] = $proposalDetail->id;
                                $data['created_by'] = auth()->user()->id;
                                ServiceSchedule::create($data);
                                $msg = 'Schedule added.';
                            }

                            $response = [
                                'success' => true,
                                'message' => $msg,
                                'html' => view('proposaldetails._schedule_service_tbody_content', ['proposalDetail' => $proposalDetail->load(['schedule'])])->render(),
                            ];
                        }
                    }
                } catch (Exception $e) {
                    $response = ExceptionError::handleAjaxError($e);
                }
            }
        } else {
            $response = [
                'success' => false,
                'message' => 'Invalid request.',
            ];
        }

        return response()->json($response);
    }

    public function ajaxRemove(Request $request)
    {
        if ($request->isMethod('post') && $request->ajax()) {
            $validator = Validator::make(
                $request->only(['proposal_detail_id', 'schedule_id']), [
                    'proposal_detail_id' => 'required|positive',
                    'schedule_id' => 'required|positive',
                ]
            );

            if ($validator->fails()) {
                $response = [
                    'success' => false,
                    'message' => $validator->messages()->first(),
                ];
            } else {
                try {
                    if (! $ServiceSchedule = ServiceSchedule::find($request->schedule_id)) {
                        $response = [
                            'success' => false,
                            'message' => 'Schedule not found.',
                        ];
                    } else {
                        $ServiceSchedule->delete();

                        $response = [
                            'success' => true,
                            'schedule_id_' => $request->schedule_id,
                            'message' => 'Schedule removed.',
                        ];
                    }
                } catch (Exception $e) {
                    $response = ExceptionError::handleAjaxError($e);
                }
            }
        } else {
            $response = [
                'success' => false,
                'message' => 'Invalid request.',
            ];
        }

        return response()->json($response);
    }

}
