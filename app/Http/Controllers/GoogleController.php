<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Spatie\GoogleCalendar\Event;




class GoogleController extends Controller
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
    public function index()
    {
        $data =[];
        return view("calendar.index", $data);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $event = new Event;

        $event->name = 'A new event';
        $event->description = 'Event description';
        $event->startDateTime = \Carbon::now();
        $event->endDateTime = \Carbon::now()->addHour();
        $event->addAttendee([
            'email' => 'keith@3-dpaving.com',
            'name' => 'Keith Daly',
            'comment' => 'Service Schedule',
            'responseStatus' => 'needsAction',
        ]);
        $event->addAttendee(['email' => 'mike.trachtenberg@gmail.com']);
        //$event->addMeetLink(); // optionally add a google meet link to the event

        $event->save();
        return ('Event created: %s\n' . $event->htmlLink. $event->Id);

    }

    public function getEvents()
    {
        // get all future events on a calendar
        return $events = Event::get();
    }

    public function UpdateEvent(Request $request)
    {
        $events = new Event;

        // update existing event
        $firstEvent = $events->first();
        $firstEvent->name = 'updated name';
        $firstEvent->save();

        $firstEvent->update(['name' => 'updated again']);

    }


    /**
     * Display the specified resource.
     *
     * @param  int  $calendarId
     * @return \Illuminate\Http\Response
     */
    public function show($calendarId)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($calendarId)
    {
        //
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($calendarId)
    {
        //
        // delete an event
        $event = Event::get($calendarId);
        $event->delete();
    }
}
//<iframe src="https://calendar.google.com/calendar/embed?height=600&wkst=1&ctz=America%2FNew_York&showPrint=0&src=bWlrZS50cmFjaHRlbmJlcmdAZ21haWwuY29t&src=YWRkcmVzc2Jvb2sjY29udGFjdHNAZ3JvdXAudi5jYWxlbmRhci5nb29nbGUuY29t&color=%23E67C73&color=%2333B679" style="border:solid 1px #777" width="800" height="600" frameborder="0" scrolling="no"></iframe>
