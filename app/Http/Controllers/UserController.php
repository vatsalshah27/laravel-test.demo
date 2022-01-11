<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Hash;
use Illuminate\Support\Arr;

use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users','users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        ##Declare variable
        $user_id = 0;
        $input = array();
        $this->validate($request, [
            'first_name' => 'required|max:50',
            'last_name' => 'required|max:50',
            'mobile' => 'required|numeric',
            'email' => 'required|email|unique:users,email',
            'birth_date' => 'required',
            'image' => 'required', 
            'address' => 'max:255',
            //'image' => 'required|mimes:jpeg,jpg,png', 
            //'address' => 'required',
            'password' => 'required|same:confirm-password|min:8|max:20',
        ]);

        $input = $request->all();

        $input['password'] = Hash::make($input['password']);
        $user_id = User::create($input)->id;
        ##Check If successfully data is inserted in a database 
        if($user_id > 0){
            #Image file upload in users folder or not 
            if ($file = $request->file('image'))
            {
                $img = time().str_replace(' ', '', $file->getClientOriginalName());
                $file_name = $file->move('assets/images/users',$img);
                if($file_name){
                    $user = User::findOrFail($user_id);
                    //Get user data  
                    if($user) {
                        $user->profile_photo_path = $img;
                        $user->save();
                        #Redirect to user list page
                        return redirect()
                        ->route('users.index')
                        ->with('success', 'User information added successfully. Image uploaded and its information saved successfully.');
                    }else{
                        #Redirect to User list page
                        return redirect()
                        ->route('users.index')
                        ->with('error', 'User information added successfully. Image uploaded successfully but its information not updated successfully.');
                    }
                }else{
                    #Redirect to User list page
                    return redirect()
                    ->route('users.index')
                    ->with('error', 'User information added successfully. Image not uploaded.');
                }
            }else{
                #Redirect to User list page
                return redirect()
                ->route('users.create')
                ->with('error', 'Image file is missing. Please upload User image file.');
            }
            
        }else{
            #Redirect to user list page
            return redirect()
            ->route('users.create')
            ->with('error', 'Something went wrong. Please try after sometimes.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return view('users.show', compact('user','user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {   
        return view('users.edit', compact('user','user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $user_id = $user->id;

        $this->validate($request, [
            'first_name' => 'required|max:50',
            'last_name' => 'required|max:50',
            'mobile' => 'required|numeric',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'birth_date' => 'required',
            'address' => 'max:255',
            'image' => 'required_without:user_id|mimes:jpg,png,jpeg|max:5048'
            //'password' => 'required|same:confirm-password|min:8|max:20',
        ]);
        $input = $request->all();
        
        #Get user details by user id
        if($user_id > 0){
            $data = User::findOrFail($user_id);
        }
        #Image Upload
        if ($file = $request->file('image'))
        {
            $name = time().str_replace(' ', '', $file->getClientOriginalName());
            $file->move('assets/images/users',$name);
            if(!empty($data) && isset($data->profile_photo_path) && $data->profile_photo_path != null)
            {
                if (file_exists(public_path().'/assets/images/users/'.$data->profile_photo_path)) {
                    unlink(public_path().'/assets/images/users/'.$data->profile_photo_path);
                }
            }
            $input['profile_photo_path'] = $name;
        }else{
            $input['profile_photo_path'] = $data->profile_photo_path;
        }
        if($input['password'] != ''){

            $input['password'] = Hash::make($input['password']);
        }else{
             $input['password'] = $data->password;
        }
        #Update user details into users table.
        $user->update($input);
        #Redirect to user list page with message
        return redirect()
            ->route('users.index')
            ->with('success', 'User details updated successfully.');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user_id = $user->id;
        $data = array();
        #Get user details by user id
        if($user_id > 0){
            $data = User::findOrFail($user_id);
        }

        if(!empty($data) && isset($data->profile_photo_path) && $data->profile_photo_path != null)
        {
            if (file_exists(public_path().'/assets/images/users/'.$data->profile_photo_path)) {
                unlink(public_path().'/assets/images/users/'.$data->profile_photo_path);
            }
        }

        $user->delete();
        return redirect()
            ->route('users.index')
            ->with('success', 'User has been deleted successfully.');
    }
}
