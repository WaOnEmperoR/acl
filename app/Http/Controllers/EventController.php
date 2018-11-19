<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Event;
use Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Session;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;

class EventController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'clearance'])
            ->except('index', 'show', 'eventsAhead');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $events = Event::orderby('event_id', 'desc')->paginate(10);

        foreach ($events as $event) {
            $event->type_event_name = DB::table('master_events')->where('master_event_id', $event->event_type_id)->first()->type_event_name;;
            $event->name = DB::table('users')->where('id', $event->user_id)->first()->name;
        }

        return view('events.index', compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users_list = DB::table('users')->get()->pluck('name', 'id');
        $event_types_list = DB::table('master_events')->get()->pluck('type_event_name', 'master_event_id');

        return view('events.create', compact('users_list', 'event_types_list'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'event_name'=>'required|max:150',
            'event_place'=>'required|max:150',
            'event_start'=>'required',
            'event_finish'=>'required',
            'event_type_id'=>'required',
            'user_id'=>'required',
        ]);

        $event = new Event();

        $event->event_name = $request['event_name'];
        $event->event_place = $request['event_place'];
        $event->event_type_id = $request['event_type_id'];
        $event->user_id = $request['user_id'];    
        $event->event_start = \Carbon\Carbon::createFromFormat('d/m/Y H:i', $request->input('event_start'));
        $event->event_finish = \Carbon\Carbon::createFromFormat('d/m/Y H:i', $request->input('event_finish'));

        $event->save();

        return redirect()->route('events.index')
            ->with('flash_message', 'Event,
             '. $event->event_name.' created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $events = Event::findOrFail($id);

        return view ('events.show', compact('events'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $users_list = DB::table('users')->get()->pluck('name', 'id');
        $event_types_list = DB::table('master_events')->get()->pluck('type_event_name', 'master_event_id');
        
        $event = Event::findOrFail($id);

        $preformat_event_start = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $event->event_start);
        $postformat_event_start = $preformat_event_start->format('d/m/Y H:i');
        $preformat_event_finish = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $event->event_finish);
        $postformat_event_finish = $preformat_event_finish->format('d/m/Y H:i');

        $event->event_start = $postformat_event_start;
        $event->event_finish = $postformat_event_finish;
        
        return view('events.edit', compact('event', 'users_list', 'event_types_list'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'event_name'=>'required|max:150',
            'event_place'=>'required|max:150',
            'event_start'=>'required',
            'event_finish'=>'required',
            'event_type_id'=>'required',
            'user_id'=>'required',
        ]);

        $event =Event::findOrFail($id);
        $event->event_name = $request->input('event_name');
        $event->event_place = $request->input('event_place');
        $event->event_start = \Carbon\Carbon::createFromFormat('d/m/Y H:i', $request->input('event_start'));
        $event->event_finish = \Carbon\Carbon::createFromFormat('d/m/Y H:i', $request->input('event_finish'));
        $event->event_type_id = $request->input('event_type_id');
        $event->user_id = $request->input('user_id');
        $event->save();

        return redirect()->route('events.index', 
            $event->event_id)->with('flash_message', 
            'Event, '. $event->event_name.' updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $event = Event::findOrFail($id);
        $event->delete();

        return redirect()->route('events.index')
            ->with('flash_message',
             'Event successfully deleted.');
    }

    public function eventsAhead()
    {
        $events = Event::whereDate('event_start', '<', \Carbon\Carbon::now())->orderby('event_start', 'asc')->get();

        foreach ($events as $event) {
            $event->type_event_name = DB::table('master_events')->where('master_event_id', $event->event_type_id)->first()->type_event_name;;
            $event->name = DB::table('users')->where('id', $event->user_id)->first()->name;
        }

        return response()->json(['events' => $events], 200);
    }
   

}
