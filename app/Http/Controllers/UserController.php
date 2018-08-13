<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\User;
use Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Session;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Intervention\Image\Facades\Image;

class UserController extends Controller
{
    public function __construct() 
    {
        $this->middleware(['auth', 'isAdmin']);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();

        return view('users.index')->with('users', $users);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::get();
        return view('users.create', ['roles'=>$roles]);
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
            'name'=>'required|max:120',
            'email'=>'required|email|unique:users',
            'password'=>'required|min:6|confirmed',
            'gender'=>'required',
            'img_avatar'=>'mimes:jpeg,bmp,png|size:2048',   
        ]);

        $file = Input::file('avatar');
        $img = Image::make($file);
        // resize image to 300x400 and keep the aspect ratio
        $img->resize(300, 400, function ($constraint) {
            $constraint->aspectRatio();
        });
        Response::make($img->encode('jpeg'));

        $user = new User();
        $user->name = $request['name'];
        $user->email = $request['email'];
        $user->password = $request['password'];
        $user->gender = $request['gender'];
        $user->birth_date = \Carbon\Carbon::createFromFormat('d/m/Y', $request->input('birth_date'));
        $user->img_avatar = $img;
        $user->address = $request['address'];

        $user->save();
  
        $roles = $request['roles'];

        if (isset($roles)) {

            foreach ($roles as $role) {
            $role_r = Role::where('id', '=', $role)->firstOrFail();            
            $user->assignRole($role_r);
            }
        }        

        return redirect()->route('users.index')
            ->with('flash_message',
             'User successfully added.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return redirect('users');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::get();

        $preformat_birth_date = \Carbon\Carbon::createFromFormat('Y-m-d', $user->birth_date);
        $postformat_birth_date = $preformat_birth_date->format('d/m/Y');

        $user->birth_date = $postformat_birth_date; 

        return view('users.edit', compact('user', 'roles'));
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
        $user = User::findOrFail($id);
        $this->validate($request, [
            'name'=>'required|max:120',
            'email'=>'required|email|unique:users,email,'.$id,
            'password'=>'required|min:6|confirmed',
            'gender'=>'required',
            'img_avatar'=>'mimes:jpeg,bmp,png|size:2048',            
        ]);

        $file = Input::file('avatar');
        $img = Image::make($file);
        // resize image to 300x400 and keep the aspect ratio
        $img->resize(300, 400, function ($constraint) {
            $constraint->aspectRatio();
        });
        Response::make($img->encode('jpeg'));
        
        $input = $request->only(['name', 'email', 'password', 'gender', 'birth_date', 'address']);
        $input['birth_date'] = \Carbon\Carbon::createFromFormat('d/m/Y', $request->input('birth_date'));
        $input['img_avatar'] = $img;

        $roles = $request['roles'];
        $user->fill($input)->save();

        if (isset($roles)) {        
            $user->roles()->sync($roles);            
        }        
        else {
            $user->roles()->detach();
        }
        return redirect()->route('users.index')
            ->with('flash_message',
             'User successfully edited.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index')
            ->with('flash_message',
             'User successfully deleted.');
    }

    public function image($id){
        $user = User::find($id);

        $pic = Image::make($user->img_avatar);
        $response = Response::make($pic->encode('jpeg'));
        $response->header('Content-Type','image/jpeg');

        return $response;

    }
}
