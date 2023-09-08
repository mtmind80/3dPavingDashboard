<?php

namespace App\Http\Controllers;

use App\Models\AcceptedDocuments;
use App\Models\MediaType;
use App\Models\Permit;
use App\Models\Proposal;
use App\Models\ProposalMedia;
use App\Models\ProposalNote;
use App\Models\Term;
use App\Models\TermsOfService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use PDF;


class PrintingController extends Controller
{

    public $storage_path;

    public function __construct(Request $request)
    {
        parent::__construct();

        $this->storage_path = storage_path('app/public/');

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


    public function proposal($proposal_id)
    {

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

        $proposal = $proposal->toArray();

        $hostwithHttp = request()->getSchemeAndHttpHost();

        $TermsOfService = TermsOfService::orderBy('section')->get()->toArray();

        $terms = Term::orderBy('section')->get()->toArray();

        //echo "<pre>";
        //print_r($proposal['location']);
        //exit();

        $data = [
            'title' => $pdfname,
            'terms' => $terms,
            'ServiceTerms' => $TermsOfService,
            'date' => date('m/d/Y'),
            'hostwithHttp' => $hostwithHttp,
            'id' => $proposal_id,
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

        //create the new pdf
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
        unlink($this->storage_path . $pdfname);

        //return the merged file

        // Set the appropriate headers


        header($_SERVER["SERVER_PROTOCOL"] . " 200 OK");
        header("Cache-Control: public"); // needed for internet explorer
        header("Content-Type: application/pdf");
        header("Content-Transfer-Encoding: Binary");
        header("Content-Length:" . filesize($this->storage_path . $newpdfname));
        header("Content-Disposition: attachment; filename=$newpdfname");
        readfile($this->storage_path . $newpdfname);

        // Read the file content and output it
        //readfile($this->storage_path.$newpdfname);
        //return $pdf->download($pdfname);

        return;
        //back()->withSuccess('PDF saved');

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
