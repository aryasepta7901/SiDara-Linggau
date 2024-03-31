<?php

namespace App\Http\Controllers;

use App\Models\entrySBS;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        if (auth()->user()->role == 'PCL') {
            $kalimat = "Entry";
            $urlsbs = "/sbsentry";
            $urltbf = "/tbfentry";
            $urlbst = "/bstentry";
            $urlth = "/thentry";
        } elseif (auth()->user()->role == 'AD') {
            $kalimat = "Validasi";
            $urlsbs = "/sbsvalidasi/dinas";
            $urltbf = "/tbfvalidasi/dinas";
            $urlbst = "/bstvalidasi/dinas";
            $urlth  = "/thvalidasi/dinas";
        } else {
            $kalimat = "Validasi";
            $urlsbs = "/sbsvalidasi/bps";
            $urltbf = "/tbfvalidasi/bps";
            $urlbst = "/bstvalidasi/bps";
            $urlth  = "/thvalidasi/bps";
        }
        // $this->authorize('pcl');
        return view('dashboard', [
            'title' => 'Dashboard Survei Pertanian Holtikultura ',
            'kalimat' => $kalimat,
            'urlsbs' => $urlsbs,
            'urltbf' => $urltbf,
            'urlbst' => $urlbst,
            'urlth' => $urlth,
            // 'kecamatan' => Kecamatan::where('id', auth()->user()->kec_id)->first(),
            // 'tanaman' => Tanaman::whereIn('id', $data)->get(),
            // 'allTanaman' => Tanaman::whereNotIn('id', $data)->get(),
            // 'entryLast' => $entryLast,
            // 'entryNow' => $entryNow,
            // 'bulanNow' => $bulanNow,
            // 'tahunNow' => $tahunNow,
            // 'bulanLalu' => $bulanLalu,
            // 'tahunLalu' => $tahunLalu,
            // 'TextBulan' => Carbon::parse($bulanTahun)->isoFormat('MMMM YYYY', 'id'),

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
     * @param  \App\Models\entrySBS  $entrySBS
     * @return \Illuminate\Http\Response
     */
    public function show(entrySBS $entrySBS)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\entrySBS  $entrySBS
     * @return \Illuminate\Http\Response
     */
    public function edit(entrySBS $entrySBS)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\entrySBS  $entrySBS
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, entrySBS $entrySBS)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\entrySBS  $entrySBS
     * @return \Illuminate\Http\Response
     */
    public function destroy(entrySBS $entrySBS)
    {
        //
    }
}
