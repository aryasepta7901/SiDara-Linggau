<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('sbs.users.profile', [
            'title' => 'Profil Saya',
            'user' => User::where('id', auth()->user()->id)->first(),

        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->changePass) {

            # Validation
            $request->validate([
                'passLama' => 'required',
                'passBaru' => 'required|same:confirmPassBaru|min:8',
                'confirmPassBaru' => 'required|same:passBaru',
            ],   [
                'required' => ':attribute Wajib di Isi',
                'same' => ':attribute dan :other  Harus Sama.',
                'min' => ':attribute harus minimal :min. Karakter',

            ]);


            #Match The Old Password
            if (!Hash::check($request->passLama, auth()->user()->password)) {
                // return back()->with("error", "Old Password Doesn't match!");
                Session::flash('error1', 'Password Lama Tidak Sama');
                return back()->withInput()->withErrors('');
            }
            #Update the new Password
            User::whereId(auth()->user()->id)->update([
                'password' => Hash::make($request->passBaru)
            ]);
            return redirect()->back()->with('success', "Password Berhasil di Ubah");
        }

        if ($request->changeProfile) {
            # Validation
            $request->validate([
                'name' => 'required',
                'email' => 'required|email',
            ],   [
                'required' => ':attribute Wajib di Isi',
                'email' => 'Alamat :attribute  Harus Valid',

            ]);
            User::whereId(auth()->user()->id)->update([
                'email' => $request->email,
                'name' => $request->name,
            ]);
            return redirect()->back()->with('success', "Profile Berhasil di Ubah");
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}
