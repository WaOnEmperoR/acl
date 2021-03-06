<?php

namespace App\Http\Controllers;

use Auth;
use App\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Intervention\Image\Facades\Image;
use Yajra\Datatables\Datatables;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'clearance'])
            ->except('index', 'show');
    }

    public function paymentsData()
    {
        $payments = Payment::query();

        return Datatables::of($payments)
            ->addColumn('payment_session_name', function($payment) {
                return DB::table('payment_sessions')->where('payment_session_id', $payment->payment_session_id)->first()->payment_session_name;
            })
            ->addColumn('payment_type_name', function($payment) {
                return DB::table('payment_types')->where('payment_type_id', $payment->payment_type_id)->first()->payment_name;
            })
            ->addColumn('user_name', function($payment) {
                return DB::table('users')->where('id', $payment->user_id)->first()->name;
            })
            ->addColumn('payment_verifier_name', function($payment) {
                return DB::table('users')->where('id', $payment->payment_verifier)->first()->name;
            })
            ->addColumn('action', function ($payment) {
                return '<a href="payments/edit/' . $payment->payment_session_id . '/' . $payment->payment_type_id . '/' . $payment->user_id .
                    '" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> Edit</a>'.
                    '<a href="payments/destroy/' . $payment->payment_session_id . '/' . $payment->payment_type_id . '/' . $payment->user_id .
                    '" class="btn btn-danger btn-primary"><i class="glyphicon glyphicon-delete"></i> Delete</a>';
            })
            ->make(true);
    }

    public function getAddEditRemoveColumn()
    {
        return view('datatables.eloquent.add-edit-remove-column');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $payments = Payment::orderby('payment_type_id', 'desc')->paginate(2);

        // foreach ($payments as $payment) {
        //     $payment->payment_session_name = DB::table('payment_sessions')->where('payment_session_id', $payment->payment_session_id)->first()->payment_session_name;
        //     $payment->payment_type_name = DB::table('payment_types')->where('payment_type_id', $payment->payment_type_id)->first()->payment_name;
        //     $payment->user_name = DB::table('users')->where('id', $payment->user_id)->first()->name;
        // }

        // return view('payments.index', compact('payments'));

        return view('payments.index_yajra');        
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
            'payment_submitted' => 'required',
            'payment_session_id' => 'required',
            'payment_type_id' => 'required',
            'user_id' => 'required',
            'payment_submitted' => 'required',
            'payment_verified' => 'required',
            'transfer_image' => 'required_without_all:text_file_proof|mimes:jpeg,bmp,png|max:512',
            'text_file_proof' => 'required_without_all:transfer_image',
            'verification_status' => 'required',
            'rejection_cause' => 'required_if:verification_status,R',
        ]);

        $file_uploaded = false;

        if ($request->hasFile('transfer_image')) {
            $file = Input::file('transfer_image');
            $img = Image::make($file);
            Response::make($img->encode('jpeg'));

            $file_uploaded = true;
        }

        $payment = new Payment();
        $payment->payment_submitted = \Carbon\Carbon::createFromFormat('d/m/Y', $request->input('payment_submitted'));
        $payment->payment_verified = \Carbon\Carbon::createFromFormat('d/m/Y', $request->input('payment_verified'));

        $payment->payment_verifier = $request['payment_verifier'];
        if ($file_uploaded) {
            $payment->img_file_proof = $img;
        }

        $payment->text_file_proof = $request['text_file_proof'];
        $payment->verification_status = $request['verification_status'];
        $payment->rejection_cause = $request['rejection_cause'];
        $payment->payment_session_id = $request['payment_session_id'];
        $payment->payment_type_id = $request['payment_type_id'];
        $payment->payment_verifier = Auth::user()->id;
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

        return view('payments.show', compact('payments'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($payment_session_id, $payment_type_id, $user_id)
    {
        $payment = Payment::where('payment_session_id', $payment_session_id)
            ->where('payment_type_id', $payment_type_id)
            ->where('user_id', $user_id)
            ->first();

        $payment->username = DB::table('users')->where('id', $user_id)->pluck('name')->first();
        $payment->payment_type_name = DB::table('payment_types')->where('payment_type_id', $payment_type_id)->pluck('payment_name')->first();
        $payment->payment_session_name = DB::table('payment_sessions')->where('payment_session_id', $payment_session_id)->pluck('payment_session_name')->first();

        $preformat_payment_submitted = \Carbon\Carbon::createFromFormat('Y-m-d', $payment->payment_submitted);
        $postformat_payment_submitted = $preformat_payment_submitted->format('d/m/Y');
        $preformat_payment_verified = \Carbon\Carbon::createFromFormat('Y-m-d', $payment->payment_verified);
        $postformat_payment_verified = $preformat_payment_verified->format('d/m/Y');

        $payment->payment_submitted = $postformat_payment_submitted;
        $payment->payment_verified = $postformat_payment_verified;

        return view('payments.edit', compact('payment'));
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
        $payment = Payment::where('payment_session_id', $payment_session_id)
            ->where('payment_type_id', $payment_type_id)
            ->where('user_id', $user_id)
            ->first();

        $this->validate($request, [
            'payment_submitted' => 'required',
            'payment_session_id' => 'required',
            'payment_type_id' => 'required',
            'user_id' => 'required',
            'payment_submitted' => 'required',
            'payment_verified' => 'required',
            'transfer_image' => 'required_without_all:text_file_proof,payment_img|mimes:jpeg,bmp,png|max:512',
            'text_file_proof' => 'required_without_all:transfer_image,payment_img',
            'verification_status' => 'required',
            'rejection_cause' => 'required_if:verification_status,R',
        ]);

        $file_uploaded = false;

        if ($request->hasFile('transfer_image')) {
            $file = Input::file('transfer_image');
            $img = Image::make($file);
            Response::make($img->encode('jpeg'));

            $file_uploaded = true;
        }

        $payment->payment_submitted = \Carbon\Carbon::createFromFormat('d/m/Y', $request->input('payment_submitted'));
        $payment->payment_verified = \Carbon\Carbon::createFromFormat('d/m/Y', $request->input('payment_verified'));

        $payment->payment_verifier = $request['payment_verifier'];

        if ($file_uploaded) {
            $payment->img_file_proof = $img;
        }

        $payment->text_file_proof = $request['text_file_proof'];
        $payment->verification_status = $request['verification_status'];
        $payment->rejection_cause = $request['rejection_cause'];
        $payment->payment_verifier = Auth::user()->id;
        $payment->save();

        return redirect()->route('payments.index')->with('flash_message',
            'Payment, ' . $payment->user_id . ' updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($payment_session_id, $payment_type_id, $user_id)
    {
        $payment = Payment::where('payment_session_id', $payment_session_id)
            ->where('payment_type_id', $payment_type_id)
            ->where('user_id', $user_id)
            ->first();

        $payment->delete();

        return redirect()->route('payments.index')
            ->with('flash_message',
                'Payment successfully deleted.');
    }

    public function getPaymentUser($begin, $end)
    {
        $payments = Payment::where('user_id', Auth::user()->id)->select('payment_submitted', 'payment_verified', 'payment_session_id', 'payment_type_id', 'user_id', 'rejection_cause', 'img_file_proof')->skip($begin)->take($end)->get();

        foreach ($payments as $payment) {
            $payment->payment_session_name = DB::table('payment_sessions')->where('payment_session_id', $payment->payment_session_id)->first()->payment_session_name;
            $payment->payment_type_name = DB::table('payment_types')->where('payment_type_id', $payment->payment_type_id)->first()->payment_name;
            $payment->user_name = DB::table('users')->where('id', $payment->user_id)->first()->name;
            $payment->img_file_proof = base64_encode($payment->img_file_proof);
        }

        return response()->json(['payments' => $payments], 200);    
    }

    public function getBillUser()
    {
        $payments = DB::statement(
            "select left_table.payment_type_id, left_table.payment_session_id,left_table.payment_name, left_table.payment_session_name,
            right_table.payment_type_id, right_table.payment_session_id
            FROM
            (SELECT pt.payment_type_id, ps.payment_session_id, pt.payment_name, ps.payment_session_name
            FROM payment_types pt, payment_sessions ps) left_table
            LEFT JOIN
            (SELECT p.payment_type_id, p.payment_session_id
            FROM payments p
            WHERE p.user_id = ".Auth::user()->id.") right_table
            ON left_table.payment_type_id=right_table.payment_type_id AND left_table.payment_session_id=right_table.payment_session_id
            WHERE right_table.payment_type_id IS NULL AND right_table.payment_session_id IS NULL
            ORDER BY left_table.payment_type_id ASC, left_table.payment_session_id ASC
            "    
        );

    }

    public function submitPaymentUser(Request $request)
    {
        $this->validate($request, [
            'payment_session_id' => 'required',
            'payment_type_id' => 'required',
            'transfer_image' => 'required_without_all:text_file_proof|mimes:jpeg,bmp,png|max:512',
            'text_file_proof' => 'required_without_all:transfer_image',
        ]);

        $file_uploaded = false;

        if ($request->hasFile('transfer_image')) {
            $file = Input::file('transfer_image');
            $img = Image::make($file);
            Response::make($img->encode('jpeg'));

            $file_uploaded = true;
        }

        $payment = new Payment();
        $payment->payment_submitted = \Carbon\Carbon::now();
        if ($file_uploaded) {
            $payment->img_file_proof = $img;
        }
        $payment->text_file_proof = $request['text_file_proof'];
        $payment->payment_session_id = $request['payment_session_id'];
        $payment->payment_type_id = $request['payment_type_id'];
        $payment->user_id = Auth::user()->id;

        if ($payment->save())
        {
            return response()->json(['success' => 'Payment Saved'], 200);
        }
        else
        {
            return response()->json(['error' => 'Payment Insertion Failed'], 401);    
        }

    }
}
