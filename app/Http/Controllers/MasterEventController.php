<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\MasterEvent;
use Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Session;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Intervention\Image\Facades\Image;

class MasterEventController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'clearance'])
            ->except('index', 'show');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    

    public function index()
    {
        $master_events = MasterEvent::orderby('master_event_id', 'desc')->paginate(10);
        return view('master_events.index', compact('master_events'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('master_events.create');
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
            'type_event_name'=>'required|max:100',
        ]);

        $type_event_name = $request['type_event_name'];

        $master_event = MasterEvent::create($request->only('type_event_name'));

        return redirect()->route('master_events.index')
            ->with('flash_message', 'Article,
             '. $master_event->type_event_name.' created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $master_event = MasterEvent::findOrFail($id);

        return view ('master_events.show', compact('master_event'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $master_event = MasterEvent::findOrFail($id);

        return view('master_events.edit', compact('master_event'));
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
            'type_event_name'=>'required|max:100',
        ]);

        $master_event = MasterEvent::findOrFail($id);
        $master_event->type_event_name = $request->input('type_event_name');
        $master_event->save();

        return redirect()->route('master_events.index', 
            $master_event->master_event_id)->with('flash_message', 
            'Article, '. $master_event->type_event_name.' updated');
    }
}
