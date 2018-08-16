<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\PaymentSession;
use Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Session;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Intervention\Image\Facades\Image;

class PaymentSessionController extends Controller
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
        $payment_sessions = PaymentSession::orderby('payment_session_id', 'desc')->paginate(10);
        return view('payment_sessions.index', compact('payment_sessions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('payment_sessions.create');
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
            'payment_start_date'=>'required',
            'payment_finish_date'=>'required',
        ]);

        $payment_session = new PaymentSession();

        $payment_session->payment_start_date = \Carbon\Carbon::createFromFormat('d/m/Y', $request->input('payment_start_date'));
        $payment_session->payment_finish_date = \Carbon\Carbon::createFromFormat('d/m/Y', $request->input('payment_finish_date'));

        $payment_session->save();

        return redirect()->route('payment_sessions.index')
            ->with('flash_message', 'Payment Session created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $payment_session = PaymentSession::findOrFail($id);

        return view ('payment_sessions.show', compact('payment_session'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $payment_session = PaymentSession::findOrFail($id);

        $preformat_payment_start_date = \Carbon\Carbon::createFromFormat('Y-m-d', $payment_session->payment_start_date);
        $postformat_payment_start_date = $preformat_payment_start_date->format('d/m/Y');
        $preformat_payment_finish_date = \Carbon\Carbon::createFromFormat('Y-m-d', $payment_session->payment_finish_date);
        $postformat_payment_finish_date = $preformat_payment_finish_date->format('d/m/Y');

        $payment_session->payment_start_date = $postformat_payment_start_date;
        $payment_session->payment_finish_date = $postformat_payment_finish_date;  

        return view('payment_sessions.edit', compact('payment_session'));
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
            'payment_start_date'=>'required',
            'payment_finish_date'=>'required',
        ]);

        $payment_session = PaymentSession::findOrFail($id);
        $payment_session->payment_start_date = \Carbon\Carbon::createFromFormat('d/m/Y', $request->input('payment_start_date'));
        $payment_session->payment_finish_date = \Carbon\Carbon::createFromFormat('d/m/Y', $request->input('payment_finish_date'));
        $payment_session->save();

        return redirect()->route('payment_sessions.index', 
            $payment_session->payment_session_id)->with('flash_message', 
            'Article, '. $payment_session->type_event_name.' updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $payment_session = PaymentSession::findOrFail($id);
        $payment_session->delete();

        return redirect()->route('payment_sessions.index')
            ->with('flash_message',
             'Payment Session successfully deleted.');
    }
}
