<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentController extends Controller
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
        $payments = Payment::orderby('payment_type_id', 'desc')->paginate(10);

        foreach ($payments as $payment) {
            $payment->payment_session_name = DB::table('payment_sessions')->where('payment_session_id', $payment->payment_session_id)->first()->payment_session_name;
            $payment->payment_type_name = DB::table('payment_types')->where('payment_type_id', $payment->payment_type_id)->first()->payment_name;
            $payment->user_name = DB::table('users')->where('id', $payment->user_id)->first()->name;
        }

        return view('payments.index', compact('payments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users_list = DB::table('users')->get()->pluck('name', 'id');
        $payment_types_list = DB::table('payment_types')->get()->pluck('payment_name', 'payment_type_id');
        $payment_sessions_list = DB::table('payment_sessions')->get()->pluck('payment_session_name', 'payment_session_id');

        return view('payments.create', compact('users_list', 'payment_types_list', 'payment_sessions_list'));
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
            'payment_submitted'=>'required',
            'payment_verified'=>'required',
            'payment_verifier'=>'required',
            'text_file_proof'=>'required',
            'payment_session_id'=>'required',
            'payment_type_id'=>'required',
            'user_id'=>'required',
            'img_file_proof'=>'mimes:jpeg,bmp,png|size:2048',   
        ]);

        $file = Input::file('img_file_proof');
        $img = Image::make($file);
        // resize image to 300x400 and keep the aspect rati`o
        // $img->resize(300, 400, function ($constraint) {
        //     $constraint->aspectRatio();
        // });
        Response::make($img->encode('jpeg'));

        $payment = new Payment();
        $payment->payment_submitted = $request['name'];
        $payment->payment_verified = $request['email'];
        $payment->payment_verifier = $request['password'];
        $payment->img_file_proof = $img;
        $payment->birth_date = \Carbon\Carbon::createFromFormat('d/m/Y', $request->input('birth_date'));
        $payment->img_avatar = $img;
        $payment->address = $request['address'];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
