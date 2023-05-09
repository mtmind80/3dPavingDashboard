<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\NoteRequest;
use App\Http\Requests\SearchRequest;


use App\Models\ContactNote;

class ContactNotesController extends Controller
{

    public function __construct(Request $request)
    {
        parent::__construct();

    }

    public function index(Request $request)
    {
        //
    }

    public function search(SearchRequest $request)
    {
        return $this->index($request);
    }

    public function create()
    {
        $data = [
            'typesCB'              => ContactNoteType::typesCB(['0' => 'Select type']),
            'sourcesCB'            => LeadSource::sourcesCB(['0' => 'Select source']),
            'assignedToCB'         => ContactNote::assignedToCB(['0' => 'Select assigned to']),
            'countiesCB'           => Location::countiesCB(['' => 'Select county']),
            'contact_note_type_id' => null,
            'lead_source'          => null,
            'assigned_to'          => null,
            'county'               => null,
        ];

        return view('contactNotes.create', $data);
    }

    public function store(NoteRequest $request)
    {
        $inputs = $request->all();

        $inputs['created_by'] = auth()->user()->id;

        ContactNote::create($inputs);

        return redirect()->to($this->returnTo)->with('success', 'Note Added.');
    }

    public function edit(ContactNote $contactNote)
    {
        $data = [
            'contactNote' => $contactNote,
        ];

        return view('contactNotes.edit', $data);
    }

    public function update(ContactNote $contactNote, NoteRequest $request)
    {
        $inputs = $request->all();

        $inputs['created_by'] = auth()->user()->id;

        $contactNote->update($inputs);

        return redirect()->to($this->returnTo)->with('success', 'ContactNote updated.');
    }

    public function destroy(Request $request)
    {
        if (!$contactNote = ContactNote::find($request->item_id)) {
            return redirect()->back()->with('error', 'Note not found.');
        }

        try {
            $contactNote->delete();
        } catch (\Exception $e) {
            if (env('APP_ENV') == 'local') {
                return redirect()->back()->with('error', $e->getMessage());
            } else {
                \Log::error(get_class() . ' - ' . $e->getMessage());
                return redirect()->back()->with('error', 'Exception error');
            }
        }

        return redirect()->route('contact_note_list', !empty($request->http_query) ? explode('&', $request->http_query) : [])->with('success', 'Note deleted.');
    }

}
