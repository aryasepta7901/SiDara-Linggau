<?php

namespace App\Http\Controllers;

use App\Models\Kecamatan;
use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('sbs.users.index', [
            'title' => 'Daftar User',
            'users' => User::where('role', 'PCL')->get(),
            'kecamatan' => Kecamatan::whereNotIn('id', [1674])->doesntHave('User')->get(),
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
        //
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
        if ($request->role) {
            $request->validate([
                'kecamatan' => 'required',
            ],   [
                'required' => ':attribute Wajib di Pilih',
            ]);
            if ($request->kecamatan == 0) {
                // Non Aktifkan
                User::where('id', $user->id)->update(['kec_id' => null]);
                return redirect()->back()->with('success', 'User Berhasil di Non Aktifkan');
            } else {
                User::where('id', $user->id)->update(['kec_id' => $request->kecamatan]);
                return redirect()->back()->with('success', 'Wilayah Tugas Berhasil Di Tambah');
            }
        }
        if ($request->defaultPass) {
            User::where('id', $user->id)->update(['password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi']);
            return redirect()->back()->with('success', 'Password Default Berhasil di terapkan');
        }
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
