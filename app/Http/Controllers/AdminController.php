<?php

namespace App\Http\Controllers;

use App\Models\EntrySBS;
use App\Models\SBS;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('sbs.admin.entry', [
            'title' => 'Daftar Entry SBS',
            'entrySBS' => entrySBS::all(),

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
     *  $entrySBS
     * @return \Illuminate\Http\Response
     */
    public function show(EntrySBS $entrysbs)
    {
        $id = last(explode('/', request()->url()));
        $entrySBS  = EntrySBS::where('id', $id)->first();
        return view('sbs.admin.sbs', [
            'title' => 'Daftar Entry SBS Kecamatan ' . $entrySBS->Kecamatan->kecamatan . '(' . $entrySBS->bulan . '/' . $entrySBS->tahun . ')',
            'SBS' => SBS::where('entry_id', $id)->get(),

        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\EntrySBS  $entrySBS
     * @return \Illuminate\Http\Response
     */
    public function edit(EntrySBS $entrySBS)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\EntrySBS  $entrySBS
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EntrySBS $entry)
    {
        $id = last(explode('/', request()->url()));
        EntrySBS::where('id', $id)->update(['status' => $request->status]);
        return redirect()->back()->with('success', 'Status Berhasil di Ubah');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EntrySBS  $entrySBS
     * @return \Illuminate\Http\Response
     */
    public function destroy(EntrySBS $entrySBS)
    {
        $id = last(explode('/', request()->url()));
        EntrySBS::where('id', $id)->delete();
        return redirect()->back()->with('success', 'Data Berhasil di Hapus');
    }
}
