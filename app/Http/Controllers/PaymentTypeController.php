<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\PaymentType;
use Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Session;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Intervention\Image\Facades\Image;

class PaymentTypeController extends Controller
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
        $payment_types = PaymentType::orderby('payment_type_id', 'desc')->paginate(10);
        return view('payment_types.index', compact('payment_types'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('payment_types.create');
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
            'payment_name'=>'required|max:100',
        ]);

        $payment_name = $request['payment_name'];

        $payment_type = PaymentType::create($request->only('payment_name'));

        return redirect()->route('payment_types.index')
            ->with('flash_message', 'Article,
             '. $payment_type->payment_name.' created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $payment_type = PaymentType::findOrFail($id);

        return view ('payment_types.show', compact('payment_type'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $payment_type = PaymentType::findOrFail($id);

        return view('payment_types.edit', compact('payment_type'));
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
            'payment_name'=>'required|max:100',
        ]);

        $payment_type = PaymentType::findOrFail($id);
        $payment_type->payment_name = $request->input('payment_name');
        $payment_type->save();

        return redirect()->route('payment_types.index', 
            $payment_type->payment_type_id)->with('flash_message', 
            'Article, '. $payment_type->payment_name.' updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $payment_type = PaymentType::findOrFail($id);
        $payment_type->delete();

        return redirect()->route('payment_types.index')
            ->with('flash_message',
             'Payment Type successfully deleted.');
    }
}
