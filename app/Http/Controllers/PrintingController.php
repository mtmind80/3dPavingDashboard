<?php

namespace App\Http\Controllers;

use App\Models\AcceptedDocuments;
use App\Models\MediaType;
use App\Models\Proposal;
use App\Models\ProposalMedia;
use App\Models\Service;
use App\Models\Term;
use App\Models\TermsOfService;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use PDF;


class PrintingController extends Controller
{

    public $storage_path;
    public $pdf_path;

    public function __construct(Request $request)
    {
        parent::__construct();

        $this->storage_path = storage_path('app/public/');

        $this->pdf_path = public_path('media/projects/');

//        $this->pdf_path = asset('/media/projects');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($proposal_id)
    {
        $data = [];
        return view("pdf.index", $data);

    }

public function setup(Request $request)
{

    $proposal_id =0;
    if(isset($request['proposal_id'])) {
        $proposal_id = $request['proposal_id'];
        $print_date = $request['print_date'];
    } else{
        return view('pages-404');
    }

    $proposal = Proposal::where('id' , '=', $proposal_id)->first();
    $media = ProposalMedia::whereIn('file_ext', ['png','jpg','pdf','gif'])->where('proposal_id', '=', $proposal_id)->get();

    $data =[
    'proposal_id' =>$proposal_id,
    'print_date' => $print_date,
    'proposal' => $proposal,
    'medias' => $media,
        'cancel_caption' =>'Cancel',
        'submit_caption' =>'Submit',
    ];


    return view("proposals.selectmedia", $data);

}


    public function proposalWImages(Request $request)
    {

        $proposal_id = 0;
        if (isset($request['proposal_id'])) {
            $proposal_id = $request['proposal_id'];
            $print_date = $request['print_date'];
            $selectmedia = $request['selectmedia'];
        }

        $mediasPDF = ProposalMedia::where('file_ext', '=', 'pdf')->whereIn('id', $selectmedia)->get();
        $medias = ProposalMedia::where('file_ext', '<>', 'pdf')->whereIn('id', $selectmedia)->get();


        $datestamp = date("Ymd");
        $pdfname = '3DPaving_' . $datestamp . '_' . $proposal_id . '.pdf';

        $orderType = 'ASC';

        $query = Proposal::with(['status', 'location', 'contact', 'details' => function ($w) use ($orderType) {
            $w->orderBy('dsort', $orderType);
        }]);

        if (!$proposal = $query->find($proposal_id)) {
            abort(404);
        }

        $currencyTotalDetailCosts = $proposal->currency_total_details_costs;

        $services = $proposal->details;
        $services_count = count($services);

        $service_overhead = Service::all()->toArray();

        $proposal = $proposal->toArray();

        $hostwithHttp = request()->getSchemeAndHttpHost();

        $TermsOfService = TermsOfService::orderBy('section')->get()->toArray();

        $terms = Term::orderBy('section')->get()->toArray();
        //provide values incase they are missing an id
        $manager = ['fname'=>'Unknown','lname'=>'Soldier'];
        $sales = ['fname'=>'Unknown','lname'=>'Soldier'];

        if($proposal['salesperson_id']) {
            $sales = User::where('id', '=', $proposal['salesperson_id'])->first()->toArray();
        }
        if($proposal['salesmanager_id']) {
            $manager = User::where('id','=',$proposal['salesmanager_id'])->first()->toArray();
        }

        //disable any debugbar code
        \Debugbar::disable();

        $data = [
            'title' => $pdfname,
            'print_date' => $print_date,
            'terms' => $terms,
            'services_count' => $services_count,
            'service_overhead' => $service_overhead,
            'ServiceTerms' => $TermsOfService,
            'date' => date('m/d/Y'),
            'hostwithHttp' => $hostwithHttp,
            'id' => $proposal_id,
            'sales' => $sales,
            'medias' => $medias,
            'mediasPDF' => $mediasPDF,
            'manager' => $manager,
            'proposal' => $proposal,
            'services' => $services,
            'currency_total_details_costs' => $currencyTotalDetailCosts,
        ];

        //return view('pdf.proposal',$data);
        //$savePath = storage_path('app/public');
        //delete any old file with the same name
        if(file_exists($this->storage_path . $pdfname)){
            unlink($this->storage_path . $pdfname);
        }

        //return view('pdf.proposal_build', $data);

        //create the new pdf
        // if this is a change order print other contract
        if($proposal['changeorder_id']){
            //dd('change order');
            $pdf = PDF::loadView('pdf.proposal_build_change_order_wimages', $data);
            //save the file to local disk
            $pdf->save($this->storage_path . $pdfname);

            $newpdfname = "Contract_" . $pdfname;

            rename($this->storage_path . $pdfname, $this->storage_path . $newpdfname);


        } else {
            //dd('no change order');
            //return view('pdf.proposal_build', $data);

            $pdf = PDF::loadView('pdf.proposal_build_wimages', $data);

            //save the file to local disk
            $pdf->save($this->storage_path . $pdfname);

            //merge with cover sheet
            $mergepdf = new \Jurosh\PDFMerge\PDFMerger;

            // add as many pdfs as you want
            if(count($mediasPDF)) {
                foreach($mediasPDF as $media) {
                    $file = $media->file_name;
                }
                $mergepdf->addPDF($this->storage_path . "coversheet.pdf", 'all', 'vertical')
                    ->addPDF($this->storage_path . $pdfname, 'all')
                    ->addPDF($this->pdf_path . $file, 'all');
            }  else {
                $mergepdf->addPDF($this->storage_path . "coversheet.pdf", 'all', 'vertical')
                    ->addPDF($this->storage_path . $pdfname, 'all');

            }
            //rename the merged file
            $newpdfname = "Contract_" . $pdfname;
            // call merge, output format `file`
            $mergepdf->merge('file', $this->storage_path . $newpdfname);
            //delete the pdf

        }


        //return the merged file

        // Set the appropriate headers

        header($_SERVER["SERVER_PROTOCOL"] . " 200 OK");
        header("Cache-Control: public"); // needed for internet explorer
        header("Content-Type: application/pdf");
        header("Content-Transfer-Encoding: Binary");
        header("Content-Length:" . filesize($this->storage_path . $newpdfname));
        header("Content-Disposition: attachment; filename=$newpdfname");
        readfile($this->storage_path . $newpdfname);

        unlink($this->storage_path . $pdfname);
        // unlink($this->storage_path . $newpdfname);

    }


    public function proposal(Request $request)
    {

        $proposal_id =0;
        if(isset($request['proposal_id'])) {
            $proposal_id = $request['proposal_id'];
            $print_date = $request['print_date'];
        }

        $datestamp = date("Ymd");
        $pdfname = '3DPaving_' . $datestamp . '_' . $proposal_id . '.pdf';

        $orderType = 'ASC';

        $query = Proposal::with(['status', 'location', 'contact', 'details' => function ($w) use ($orderType) {
            $w->orderBy('dsort', $orderType);
        }]);

        if (!$proposal = $query->find($proposal_id)) {
            abort(404);
        }

        $currencyTotalDetailCosts = $proposal->currency_total_details_costs;

        $services = $proposal->details;
        $services_count = count($services);

        $service_overhead = Service::all()->toArray();

        $proposal = $proposal->toArray();

        $hostwithHttp = request()->getSchemeAndHttpHost();

        $TermsOfService = TermsOfService::orderBy('section')->get()->toArray();

        $terms = Term::orderBy('section')->get()->toArray();
        //provide values incase they are missing an id
        $manager = ['fname'=>'Unknown','lname'=>'Soldier'];
        $sales = ['fname'=>'Unknown','lname'=>'Soldier'];

        if($proposal['salesperson_id']) {
            $sales = User::where('id', '=', $proposal['salesperson_id'])->first()->toArray();
        }
        if($proposal['salesmanager_id']) {
            $manager = User::where('id','=',$proposal['salesmanager_id'])->first()->toArray();
        }

        //disable any debugbar code
        \Debugbar::disable();

        $data = [
            'title' => $pdfname,
            'print_date' => $print_date,
            'terms' => $terms,
            'services_count' => $services_count,
            'service_overhead' => $service_overhead,
            'ServiceTerms' => $TermsOfService,
            'date' => date('m/d/Y'),
            'hostwithHttp' => $hostwithHttp,
            'id' => $proposal_id,
            'sales' => $sales,
            'manager' => $manager,
            'proposal' => $proposal,
            'services' => $services,
            'currency_total_details_costs' => $currencyTotalDetailCosts,
        ];

        //return view('pdf.proposal',$data);
        //$savePath = storage_path('app/public');
        //delete any old file with the same name
        if(file_exists($this->storage_path . $pdfname)){
            unlink($this->storage_path . $pdfname);
        }

        //return view('pdf.proposal_build', $data);

        //create the new pdf
        // if this is a change order print other contract
        if($proposal['changeorder_id']){
            //dd('change order');
            $pdf = PDF::loadView('pdf.proposal_build_change_order', $data);
            //save the file to local disk
            $pdf->save($this->storage_path . $pdfname);

            $newpdfname = "Contract_" . $pdfname;

            rename($this->storage_path . $pdfname, $this->storage_path . $newpdfname);


        } else {
            //dd('no change order');
            $pdf = PDF::loadView('pdf.proposal_build', $data);

            //save the file to local disk
            $pdf->save($this->storage_path . $pdfname);

            //merge with cover sheet
            $mergepdf = new \Jurosh\PDFMerge\PDFMerger;

            // add as many pdfs as you want
            $mergepdf->addPDF($this->storage_path . "coversheet.pdf", 'all', 'vertical')
                ->addPDF($this->storage_path . $pdfname, 'all');

            //rename the merged file
            $newpdfname = "Contract_" . $pdfname;
            // call merge, output format `file`
            $mergepdf->merge('file', $this->storage_path . $newpdfname);
            //delete the pdf

        }


        //return the merged file

        // Set the appropriate headers

        header($_SERVER["SERVER_PROTOCOL"] . " 200 OK");
        header("Cache-Control: public"); // needed for internet explorer
        header("Content-Type: application/pdf");
        header("Content-Transfer-Encoding: Binary");
        header("Content-Length:" . filesize($this->storage_path . $newpdfname));
        header("Content-Disposition: attachment; filename=$newpdfname");
        readfile($this->storage_path . $newpdfname);

        unlink($this->storage_path . $pdfname);
       // unlink($this->storage_path . $newpdfname);

    }


    public function coversheet()
    {

        $pdfname = 'coversheet.pdf';
        $pdf = PDF::loadView('pdf.proposal_coversheet');
        $pdf->save($this->storage_path . $pdfname);
        echo "Cover Sheet Created Saved as " . $pdfname;

    }


    public function printExamplePdfWithBAckgroundImage()
    {
        $users = \App\Models\User::get();

        $imgContent = file_get_contents(public_path() . '/images/bg-img.jpg');
        $type = 'jpg';
        $img64 = 'data:image/' . $type . ';base64,' . base64_encode($imgContent);

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
        $pdfname = '3DPaving_' . $datestamp . '_' . $proposal_id . 'pdf';

        $data = [
            'title' => 'Welcome to Hummingbird',
            'date' => date('m/d/Y')
        ];

        //return view('pdf.proposal',$data);

        $pdf = PDF::loadView('pdf.proposal', $data);

        return $pdf->download($pdfname);

    }


    /*
     * // Autoload composer classses...

// and now we can use library
$pdf = new \Jurosh\PDFMerge\PDFMerger;

// add as many pdfs as you want
$pdf->addPDF('path/to/source/file.pdf', 'all', 'vertical')
  ->addPDF('path/to/source/file1.pdf', 'all')
  ->addPDF('path/to/source/file2.pdf', 'all', 'horizontal');

// call merge, output format `file`
$pdf->merge('file', 'path/to/export/dir/file.pdf');

    https://packagist.org/packages/jurosh/pdf-merge



    https://packagist.org/packages/webklex/laravel-pdfmerger

    composer require webklex/laravel-pdfmerger
     */

}
