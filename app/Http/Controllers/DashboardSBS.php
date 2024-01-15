<?php

namespace App\Http\Controllers;

use App\Models\EntrySBS;
use App\Models\Kecamatan;
use App\Models\SBS;
use App\Models\Tanaman;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class DashboardSBS extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('dinas');
        // Mengambil bulan dan tahun dari sesi
        $selectedMonth = Session::get('selectedMonth');
        $selectedYear = Session::get('selectedYear');
        if ($selectedMonth != null || $selectedYear != null) {
            // Membuat objek Carbon dari bulan dan tahun yang dipilih
            $bulanTahun = Carbon::createFromDate($selectedYear, $selectedMonth, 1);

            $bulanNow = $selectedMonth;
            $tahunNow = $selectedYear;
        } else {
            // Mendapatkan tanggal saat ini
            $today = Carbon::now();
            // Mengurangkan satu bulan dari tanggal saat ini
            $bulanTahun = $today->subMonth();
            // Bulan sekarang
            $bulanNow = date('m') == 1 ? 12 : date('m') - 1;
            $tahunNow = date('m') == 1 ? date('Y') - 1 : date('Y');
        }
        return view('sbs.dashboard.index', [
            'title' => 'Dashboard SBS ' . Carbon::parse($bulanTahun)->isoFormat('MMMM YYYY', 'id'),
            'kecamatan' => Kecamatan::whereNotIn('id', [1674])->get(),
            'bulanNow' => $bulanNow,
            'tahunNow' => $tahunNow,


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
        // Mendapatkan data dari radio button dengan nama dinamis
        $tanamanIds = $request->input('tanaman_id');
        $data = EntrySBS::updateOrCreate(
            [
                'bulan' =>  $request->bulan,
                'tahun' => $request->tahun,
                'kec_id' => $request->kec_id,
            ]
        );
        EntrySBS::where('id', $data->id)->update(['status' => 4]);


        foreach ($tanamanIds as $tanamanId) {
            $validasiValue = $request->input('validasi' . $tanamanId);
            $data = EntrySBS::updateOrCreate(
                [
                    'bulan' =>  $request->bulan,
                    'tahun' => $request->tahun,
                    'kec_id' => $request->kec_id,
                ]
            );
            SBS::where('tanaman_id', $tanamanId)->where('entry_id', $data->id)->update(['status' => $validasiValue]);
            // }
            if ($validasiValue == 3) { //jika ada yang direvisi maka revisi->1 
                $catatanDinas = $request->input('catatanDinas' . $tanamanId);

                SBS::where('tanaman_id', $tanamanId)->where('entry_id', $data->id)->update(
                    [
                        'catatan_dinas' => $catatanDinas,
                    ]
                );
                $data->update(['status' => 3]); //update entrySBS
            } elseif ($validasiValue == 2) {  //jika ada tanaman baru
                $data->update(['status' => 3]); //update entrySBS

                $catatanDinas = $request->input('catatanDinas' . $tanamanId);
                $bulanLalu =  $request->bulan == 1 ? 12 : $request->bulan - 1;
                $tahunLalu =  $request->bulan == 1 ? $request->tahun - 1 : $request->tahun;
                $data = EntrySBS::updateOrCreate(
                    [
                        'bulan' => $bulanLalu,
                        'tahun' => $tahunLalu,
                        'kec_id' => $request->kec_id,
                    ]
                );
                $data = [
                    'r4' => 0,
                    'r5' => 0,
                    'r6' => 0,
                    'r7' => 0,
                    'r8' => 0,
                    'r9' => 0,
                    'r10' => 0,
                    'r11' => 0,
                    'r12' => 0,
                    'note' => '',
                    'tanaman_id' => $tanamanId,
                    'status' => 6,
                    'status_tanaman' => 2, //tanaman_baru
                    'catatan_dinas' => $catatanDinas,
                    'user_id' => Auth()->user()->id,
                    'entry_id' => $data->id,
                ];
                SBS::create($data);
            }
        }

        return redirect('dashboard')->with('success', 'Validasi Berhasil di Lakukan');

        // $dataRevisi = EntrySBS::where('kec_id', $request->kec_id)->where('bulan', $request->bulan)
        //     ->where('tahun', $request->tahun)->where('status_entry', 3)->pluck('tanaman_id')->toArray();
        // $dataCheck = EntrySBS::where('kec_id', $request->kec_id)->where('bulan', $request->bulan)
        //     ->where('tahun', $request->tahun)->where('status_entry', 2)->pluck('tanaman_id')->toArray();
        // if ($dataRevisi === []) {
        //     // Jika tidak ada revisi maka
        //     EntrySBS::where('kec_id', $request->kec_id)->where('bulan', $request->bulan)
        //         ->where('tahun', $request->tahun)->update(['status_entry' => 4]);
        // } elseif ($dataCheck !== []) {
        //     // Jika ada revisi maka hanya update selain data revisi
        //     EntrySBS::where('kec_id', $request->kec_id)->where('bulan', $request->bulan)
        //         ->where('tahun', $request->tahun)->whereNotIn('tanaman_id', $dataRevisi)->update(['status_entry' => 1]);
        // }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Kecamatan  $entrySBS
     * @return \Illuminate\Http\Response
     */
    public function show(Kecamatan $dashboard)
    {
        $this->authorize('dinas');

        // Mengambil bulan dan tahun dari sesi
        $selectedMonth = Session::get('selectedMonth');
        $selectedYear = Session::get('selectedYear');

        if ($selectedMonth != null || $selectedYear != null) {
            // Membuat objek Carbon dari bulan dan tahun yang dipilih
            $bulanTahun = Carbon::createFromDate($selectedYear, $selectedMonth, 1);
            $bulanNow = $selectedMonth;
            $tahunNow = $selectedYear;
        } else {
            // Mendapatkan tanggal saat ini
            $today = Carbon::now();
            // Mengurangkan satu bulan dari tanggal saat ini
            $bulanTahun = $today->subMonth();
            $bulanNow = date('m') == 1 ? 12 : date('m') - 1;
            $tahunNow = date('m') == 1 ? date('Y') - 1 : date('Y');
        }

        return view('sbs.dashboard.show', [
            'title' => "Kecamatan" . $dashboard->kecamatan . "(" . Carbon::parse($bulanTahun)->isoFormat('MMMM YYYY', 'id') . ")",
            'bulan' => $bulanNow,
            'tahun' => $tahunNow,
            'kecamatan' => $dashboard,
            'entryNow' => EntrySBS::where('kec_id', $dashboard->id)->where('bulan', $bulanNow)->where('tahun', $tahunNow)->first(),
            'tanaman' => Tanaman::where('jenis_sph', 'SBS')->orderBy('urut_kues', 'asc')->get(),
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
    public function update(Request $request, EntrySBS $entrySBS)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EntrySBS  $entrySBS
     * @return \Illuminate\Http\Response
     */
    public function destroy(EntrySBS $entrySBS)
    {
        //
    }
}
