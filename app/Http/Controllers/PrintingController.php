<?php

namespace App\Http\Controllers;

use App\Models\AcceptedDocuments;
use App\Models\MediaType;
use App\Models\Permit;
use App\Models\Proposal;
use App\Models\ProposalMedia;
use App\Models\ProposalNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use PDF;


class PrintingController extends Controller
{



    public function __construct(Request $request)
    {
        parent::__construct();

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($proposal_id)
    {
        $data =[];
        return view("pdf.index", $data);

    }


    public function proposal($proposal_id)
    {

        $datestamp = date("Ymd");
        $pdfname = '3DPaving_' . $datestamp . '_'. $proposal_id .'.pdf';


        $imgContent = file_get_contents(public_path().'/images/cover_page.jpg');
        $type = 'jpg';
        $img64 = 'data:image/'.$type.';base64,'.base64_encode($imgContent);

        print_r($img64);
        exit();


        $orderType = 'ASC';

        $query = Proposal::with(['status', 'details' => function($w) use ($orderType){
            $w->orderBy('dsort', $orderType);
        }]);

        if (!$proposal = $query->find($proposal_id)) {
            abort(404);
        }



        $currencyTotalDetailCosts = $proposal->currency_total_details_costs;

        $services = $proposal->details;

        $proposal = $proposal->toArray();

        $data = [];

        $hostwithHttp = request()->getSchemeAndHttpHost();

        $data = [
                'title' => $pdfname,
                'date' => date('m/d/Y'),
                'hostwithHttp' => $hostwithHttp,
                'id' => $proposal_id,
                'proposal' => $proposal,
                'services' => $services,
                'currency_total_details_costs' => $currencyTotalDetailCosts,
        ];

            //return view('pdf.proposal',$data);

            $pdf = PDF::loadView('pdf.proposal_contract', $data);

            return $pdf->download($pdfname);


    }



    public function coversheet()
    {

        //return view('pdf.proposal',$data);

        $pdfname = 'coversheet.pdf';
        $pdf = PDF::loadView('pdf.proposal_coversheet');

        return $pdf->download($pdfname);


    }




    public function printExamplePdfWithBAckgroundImage()
    {
        $users = \App\Models\User::get();

        $imgContent = file_get_contents(public_path().'/images/bg-img.jpg');
        $type = 'jpg';
        $img64 = 'data:image/'.$type.';base64,'.base64_encode($imgContent);

        $data = [
            'users' => $users,
            'img64' => $img64,
        ];

        $pdf = Pdf::loadView('pdf.example_report', $data);

        return $pdf->download('example_report.pdf');
    }


    public function proposalTest($proposal_id)
    {

        $datestamp = date("Ymd");
        $pdfname = '3DPaving_' . $datestamp . '_'. $proposal_id .'pdf';

        $data = [
            'title' => 'Welcome to Hummingbird',
            'date' => date('m/d/Y')
        ];

        //return view('pdf.proposal',$data);

        $pdf = PDF::loadView('pdf.proposal', $data);

        return $pdf->download($pdfname);

    }

}
