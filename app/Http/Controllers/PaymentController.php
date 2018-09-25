<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Payment;
use Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Session;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;

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
										            'payment_session_id'=>'required',
										            'payment_type_id'=>'required',
										            'user_id'=>'required',
										            'transfer_image'=>'mimes:jpeg,bmp,png',   
										        ]);
		
		$file = Input::file('transfer_image');
		$img = Image::make($file);
		Response::make($img->encode('jpeg'));
		
		$payment = new Payment();
		$payment->payment_submitted = \Carbon\Carbon::createFromFormat('d/m/Y', $request->input('payment_submitted'));
		$payment->payment_verified = \Carbon\Carbon::createFromFormat('d/m/Y', $request->input('payment_verified'));
		// 		$payment->payment_submitted = $request['payment_submitted'];
		// 		$payment->payment_verified = $request['payment_verified'];
		$payment->payment_verifier = $request['payment_verifier'];
		$payment->img_file_proof = $img;
		$payment->text_file_proof = $request['text_file_proof'];
		$payment->verification_status = $request['verification_status'];
		$payment->rejection_cause = $request['rejection_status'];
		$payment->payment_session_id = $request['payment_session_id'];
		$payment->payment_type_id = $request['payment_type_id'];
		$payment->user_id = $request['user_id'];
		$payment->save();
		
		return redirect()->route('payments.index')
		            ->with('flash_message', 'Payment 
              created');
	}
	
	
	
	
	
	
	/**
	* Display the specified resource.
	*
	* @param  int  $id
	* @return \Illuminate\Http\Response
	*/
	public function show($id)
	{
		$payments = Payment::findOrFail($id);
		
		return view ('payments.show', compact('payments'));
	}
	/**
	* Show the form for editing the specified resource.
	*
	* @param  int  $id
	* @return \Illuminate\Http\Response
	*/
	public function edit($payment_session_id, $payment_type_id, $user_id)
	{
		$users_list = DB::table('users')->get()->pluck('name', 'id');
		$payment_types_list = DB::table('payment_types')->get()
										                                ->pluck('payment_name', 'payment_type_id');
		$payment_sessions_list = DB::table('payment_sessions')->get()
										                                ->pluck('payment_session_name', 'payment_session_id');
		
		$payment = DB::table('payments')
										            ->where('payment_session_id', $payment_session_id)
										            ->where('payment_type_id', $payment_type_id)
										            ->where('user_id', $user_id)
										            ->first();
		
		$preformat_payment_submitted = \Carbon\Carbon::createFromFormat('Y-m-d', $payment->payment_submitted);
		$postformat_payment_submitted = $preformat_payment_submitted->format('d/m/Y');
		$preformat_payment_verified = \Carbon\Carbon::createFromFormat('Y-m-d', $payment->payment_verified);
		$postformat_payment_verified = $preformat_payment_verified->format('d/m/Y');
		
		$payment->payment_submitted = $postformat_payment_submitted;
		$payment->payment_verified = $postformat_payment_verified;
		
		return view('payments.edit', compact('payment', 'users_list', 'payment_types_list', 'payment_sessions_list'));
	}
	
	
	
	
	/**
	* Update the specified resource in storage.
	*
	* @param  \Illuminate\Http\Request  $request
	* @param  int  $id
	* @return \Illuminate\Http\Response
	*/
	public function update(Request $request, $payment_session_id, $payment_type_id, $user_id)
	{
		$this->validate($request, [
										            'payment_submitted'=>'required',
										            'payment_session_id'=>'required',
										            'payment_type_id'=>'required',
										            'user_id'=>'required',
										            'transfer_image'=>'mimes:jpeg,bmp,png|size:2048',   
										        ]);
		
		$file = Input::file('transfer_image');
		$img = Image::make($file);
		Response::make($img->encode('jpeg'));
		
		$payment = DB::table('payments')
										            ->where('payment_session_id', $payment_session_id)
										            ->where('payment_type_id', $payment_type_id)
										            ->where('user_id', $user_id)
										            ->first();
		
		$payment->payment_submitted = \Carbon\Carbon::createFromFormat('d/m/Y', $request->input('payment_submitted'));
		$payment->payment_verified = \Carbon\Carbon::createFromFormat('d/m/Y', $request->input('payment_verified'));
		// 		$payment->payment_submitted = $request['payment_submitted'];
		// 		$payment->payment_verified = $request['payment_verified'];
		$payment->payment_verifier = $request['payment_verifier'];
		$payment->img_file_proof = $img;
		$payment->text_file_proof = $request['text_file_proof'];
		$payment->verification_status = $request['verification_status'];
		$payment->rejection_cause = $request['rejection_status'];
		$payment->img_avatar = $img;
		$payment->save();
		
		return redirect()->route('payments.index', 
						            $payment->payment_session_id)->with('flash_message', 
						            'Payment, '. $payment->user_id.' updated');
	}
	
	
	
	
	/**
	* Remove the specified resource from storage.
			    *
			    * @param  int  $id
			    * @return \Illuminate\Http\Response
			    */
			    public function destroy($payment_session_id, $payment_type_id, $user_id)
			    {
		$payment = DB::table('payments')
						                    ->where('payment_session_id', $payment_session_id)
						                    ->where('payment_type_id', $payment_type_id)
						                    ->where('user_id', $user_id)
						                    ->first();
		
		$payment->delete();
		
		return redirect()->route('payments.index')
						                        ->with('flash_message',
						                         'Payment successfully deleted.');
	}
}
