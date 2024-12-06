<?php

namespace App\Http\Controllers;

use App\Models\Proposal;
use App\Models\ProposalMedia;
use App\Models\AcceptedDocuments;
use Log;
use Exception;
use Illuminate\Http\Request;
use App\Http\Requests\MediaRequest;
use Illuminate\Support\Facades\DB;
use App\Models\MediaType;

class UploadController extends Controller
{
    const FILE_SIZE_LIMIT = 1500000;

    public function showuploadform($proposal_id, $proposal_detail_id = null)
    {

        $accepted_filetypes = AcceptedDocuments::all()->pluck('extension')->toArray();
        $data['doctypes'] = implode(',', $accepted_filetypes);
        $data['doctypes'] = str_replace(",", ", ", $data['doctypes']);
        $data['mediatypes'] = MediaType::all()->toArray();
        $data['proposal'] = Proposal::find($proposal_id)->toArray();
        $data['proposal_id'] = $proposal_id;
        $data['proposal_detail_id'] = $proposal_detail_id;

        return view('upload.upload', $data);

    }

    public function showworkorderuploadform($proposal_id, $proposal_detail_id = null)
    {

        $accepted_filetypes = AcceptedDocuments::all()->pluck('extension')->toArray();
        $data['doctypes'] = implode(',', $accepted_filetypes);
        $data['doctypes'] = str_replace(",", ", ", $data['doctypes']);
        $data['mediatypes'] = MediaType::all()->toArray();
        $data['proposal'] = Proposal::find($proposal_id)->toArray();
        $data['proposal_id'] = $proposal_id;
        $data['proposal_detail_id'] = $proposal_detail_id;

        return view('upload.workorderupload', $data);

    }

    public function doupload(MediaRequest $request)
    {
        $accepted_filetypes = AcceptedDocuments::all()->pluck('extension')->toArray();
        $doctypes = implode(',', $accepted_filetypes);
        $doctypes = explode(',', $doctypes);
        $guid = bin2hex(openssl_random_pseudo_bytes(16));
        $random = rand(50, 100);
        $newfilename = $request['proposal_id'] . "_" . $random . "_" . $guid;

        $proposal_id = $request['proposal_id'];
        $proposal_detail_id = $request['proposal_detail_id'];
        $media_type_id = $request['media_type_id'];
        $description = htmlspecialchars($request['description']);
        $created_by = auth()->user()->id;
        $original_name = basename($_FILES["file"]["name"]);
        $file_ext = '';
        $file_size = 0;
        $IsImage = 1;
        $image_height = 0;
        $image_width = 0;

        $msg = "There were errors.";
        $target_dir = "media/projects/";
        $target_file = basename($_FILES["file"]["name"]);
        $uploadOk = 1;

        $extension = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $new_filename = $target_dir . $newfilename . "." . $extension;
        // Check if filetype is accepted
        if (!in_array($extension, $doctypes)) {
            $msg .= "This file type ($extension) is not allowed.";
            \Session::flash('error', $msg);
            return redirect()->route('show_proposal', ['id' => $proposal_id]);
            //return with error
        }

        $filetype = $_FILES['file']['type'];
        $check = getimagesize($_FILES['file']['tmp_name']);

        if ($check !== false) {
            $msg .= "File is an image - " . $check["mime"] . ".";
            $IsImage = 1;
            $image_height = $check[1];
            $image_width = $check[0];
            $file_type = $check["mime"];

        } else { // not an image
            $msg .= " File is not an image.";
        }
        $file_size = $_FILES["file"]["size"];

        if ($file_size > self::FILE_SIZE_LIMIT) {
            $msg .= "Sorry, your file is too large.";
            \Session::flash('error', $msg);
            return redirect()->route('show_proposal', ['id' => $proposal_id]);
            //redirect();

        }

        // Check if file already exists
        if (file_exists($new_filename)) {
            //rename file
            $msg .= "Sorry, file already exists.";
            $guid = bin2hex(openssl_random_pseudo_bytes(16));
            $random = rand(101, 150);
            $newfilename = $request['proposal_id'] . "_" . $random . "_" . $guid;
            $new_filename = $target_dir . $newfilename . "." . $extension;

        }

        $data['proposal_id'] = $proposal_id;
        $data['proposal_detail_id'] = $proposal_detail_id;
        $data['media_type_id'] = $media_type_id;
        $data['description'] = $description;
        $data['created_by'] = auth()->user()->id;
        $data['original_name'] = basename($_FILES["file"]["name"]);
        $data['file_ext'] = strtolower($extension);
        $data['file_size'] = $file_size;
        $data['IsImage'] = $IsImage;
        $data['image_height'] = $image_height;
        $data['image_width'] = $image_width;
        $data['file_name'] = $newfilename . "." . $extension;
        $data['file_path'] = $target_dir;

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
            // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["file"]["tmp_name"], $new_filename)) {

                $proposal_media = ProposalMedia::create($data);
                $msg = "The file " . htmlspecialchars(basename($_FILES["file"]["name"])) . " has been uploaded to $new_filename.";
                \Session::flash('success', $msg);
                return redirect()->route('show_proposal', ['id' => $proposal_id]);


            } else {
                $msg = "Sorry, there was an error uploading your file.";
                \Session::flash('error', $msg);
                return redirect()->route('show_proposal', ['id' => $proposal_id]);
            }
        }


    }


    public function doworkorderupload(MediaRequest $request)
    {
        $accepted_filetypes = AcceptedDocuments::all()->pluck('extension')->toArray();
        $doctypes = implode(',', $accepted_filetypes);
        $doctypes = explode(',', $doctypes);
        $guid = bin2hex(openssl_random_pseudo_bytes(16));
        $random = rand(50, 100);
        $newfilename = $request['proposal_id'] . "_" . $random . "_" . $guid;

        $proposal_id = $request['proposal_id'];
        $proposal_detail_id = $request['proposal_detail_id'];
        $media_type_id = $request['media_type_id'];
        $description = htmlspecialchars($request['description']);
        $created_by = auth()->user()->id;
        $original_name = basename($_FILES["file"]["name"]);
        $file_ext = '';
        $file_size = 0;
        $IsImage = 1;
        $image_height = 0;
        $image_width = 0;

        $msg = "There were errors.";
        $target_dir = "media/projects/";
        $target_file = basename($_FILES["file"]["name"]);
        $uploadOk = 1;

        $extension = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $new_filename = $target_dir . $newfilename . "." . $extension;
        // Check if filetype is accepted
        if (!in_array($extension, $doctypes)) {
            $msg .= "This file type ($extension) is not allowed.";
            \Session::flash('error', $msg);
            return redirect()->route('show_workorder', ['id' => $proposal_id]);
            //return with error
        }

        $filetype = $_FILES['file']['type'];
        $check = getimagesize($_FILES['file']['tmp_name']);

        if ($check !== false) {
            $msg .= "File is an image - " . $check["mime"] . ".";
            $IsImage = 1;
            $image_height = $check[1];
            $image_width = $check[0];
            $file_type = $check["mime"];

        } else { // not an image
            $msg .= " File is not an image.";
        }
        $file_size = $_FILES["file"]["size"];

        if ($file_size > self::FILE_SIZE_LIMIT) {
            $msg .= "Sorry, your file is too large.";
            \Session::flash('error', $msg);
            return redirect()->route('show_workorder', ['id' => $proposal_id]);
            //redirect();

        }

        // Check if file already exists
        if (file_exists($new_filename)) {
            //rename file
            $msg .= "Sorry, file already exists.";
            $guid = bin2hex(openssl_random_pseudo_bytes(16));
            $random = rand(101, 150);
            $newfilename = $request['proposal_id'] . "_" . $random . "_" . $guid;
            $new_filename = $target_dir . $newfilename . "." . $extension;

        }

        $data['proposal_id'] = $proposal_id;
        $data['proposal_detail_id'] = $proposal_detail_id;
        $data['media_type_id'] = $media_type_id;
        $data['description'] = $description;
        $data['created_by'] = auth()->user()->id;
        $data['original_name'] = basename($_FILES["file"]["name"]);
        $data['file_ext'] = $extension;
        $data['file_size'] = $file_size;
        $data['IsImage'] = $IsImage;
        $data['image_height'] = $image_height;
        $data['image_width'] = $image_width;
        $data['file_name'] = $newfilename . "." . $extension;
        $data['file_path'] = $target_dir;

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
            // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["file"]["tmp_name"], $new_filename)) {

                $proposal_media = ProposalMedia::create($data);
                $msg = "The file " . htmlspecialchars(basename($_FILES["file"]["name"])) . " has been uploaded to $new_filename.";
                \Session::flash('success', $msg);
                return redirect()->route('show_workorder', ['id' => $proposal_id]);


            } else {
                $msg = "Sorry, there was an error uploading your file.";
                \Session::flash('error', $msg);
                return redirect()->route('show_workorder', ['id' => $proposal_id]);
            }
        }


    }


    public function ajaxupload(MediaRequest $request)
    {

        $accepted_filetypes = AcceptedDocuments::all()->pluck('extension')->toArray();
        $doctypes = implode(',', $accepted_filetypes);
        $doctypes = explode(',', $doctypes);
        $guid = bin2hex(openssl_random_pseudo_bytes(16));
        $random = rand(50, 100);
        $newfilename = $request['proposal_id'] . "_" . $random . "_" . $guid;

        $data['return_to'] = $request['returnTo'];
        $data['tab'] = $request['tab'];

        $proposal_id = $request['proposal_id'];
        $proposal = Proposal::where('id', '=', $proposal_id)->first();
        $route = 'show_proposal';
        if ($proposal['job_master_id']) {
            $route = 'show_workorder';

        }

        $proposal_detail_id = $request['proposal_detail_id'];
        $media_type_id = $request['media_type_id'];
        $description = htmlspecialchars($request['description']);
        $created_by = auth()->user()->id;
        $original_name = basename($_FILES["file"]["name"]);
        $file_ext = '';
        $file_size = 0;
        $IsImage = 1;
        $image_height = 0;
        $image_width = 0;

        $msg = "There were errors.";
        $target_dir = "media/projects/";
        $target_file = basename($_FILES["file"]["name"]);
        $uploadOk = 1;
        $extension = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $new_filename = $target_dir . $newfilename . "." . $extension;
        // Check if filetype is accepted
        if (!in_array($extension, $doctypes)) {
            $msg .= "This file type ($extension) is not allowed.";
            \Session::flash('error', $msg);
            return redirect()->route($route, ['id' => $proposal_id]);
            //return with error
        }
        $check = getimagesize($_FILES["file"]["tmp_name"]);

        if ($check !== false) {
            $msg .= "File is an image - " . $check["mime"] . ".";
            $IsImage = 1;
            $image_height = $check[1];
            $image_width = $check[0];
            $file_type = $check["mime"];

        } else { // not an image
            $msg .= " File is not an image.";
        }
        $file_size = $_FILES["file"]["size"];

        if ($file_size > 5000000) {
            $msg .= "Sorry, your file is too large.";
            \Session::flash('error', $msg);
            return redirect()->route($route, ['id' => $proposal_id]);
            //redirect();

        }

        // Check if file already exists
        if (file_exists($new_filename)) {
            //rename file
            $msg .= "Sorry, file already exists.";
            $guid = bin2hex(openssl_random_pseudo_bytes(16));
            $random = rand(101, 150);
            $newfilename = $request['proposal_id'] . "_" . $random . "_" . $guid;
            $new_filename = $target_dir . $newfilename . "." . $extension;

        }

        $data['proposal_id'] = $proposal_id;
        $data['proposal_detail_id'] = $proposal_detail_id;
        $data['media_type_id'] = $media_type_id;
        $data['description'] = $description;
        $data['created_by'] = auth()->user()->id;
        $data['original_name'] = basename($_FILES["file"]["name"]);
        $data['file_ext'] = $extension;
        $data['file_size'] = $file_size;
        $data['IsImage'] = $IsImage;
        $data['image_height'] = $image_height;
        $data['image_width'] = $image_width;
        $data['file_name'] = $newfilename . "." . $extension;
        $data['file_path'] = $target_dir;

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
            // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["file"]["tmp_name"], $new_filename)) {

                $proposal_media = ProposalMedia::create($data);
                $msg = "The file " . htmlspecialchars(basename($_FILES["file"]["name"])) . " has been uploaded to $new_filename.";
                \Session::flash('success', $msg);
                return redirect()->route($route, ['id' => $proposal_id]);

            } else {
                $msg = "Sorry, there was an error uploading your file.";
                \Session::flash('error', $msg);
                return redirect()->route($route, ['id' => $proposal_id]);
            }
        }


    }


}
