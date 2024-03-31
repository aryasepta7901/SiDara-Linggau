<?php

namespace App\Http\Controllers;

use App\Models\EntryTH;
use App\Models\Kecamatan;
use App\Models\Tanaman;
use App\Models\TH;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

use function App\Providers\getTrimester;

class ValidasiTHDinasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('dinas');
        // Mengambil TW dan tahun dari sesi
        $selectedTW = Session::get('selectedTWTH');
        $selectedYear = Session::get('selectedYearTH');
        if ($selectedTW != null || $selectedYear != null) {
            // Membuat objek Carbon dari TW dan tahun yang dipilih
            $TWNow = $selectedTW;
            $tahunNow = $selectedYear;
        } else {
            $bulanNow = date('m');
            $trimester = getTrimester($bulanNow);
            // TW Input
            $TWNow = $trimester;
            if ($TWNow == 4) {
                $tahunNow = date('Y') - 1;
            } else {
                $tahunNow = date('Y');
            }
        }
        return view('th.validasi.dinas.index', [
            'title' => 'Dashboard TH Triwulan ' . $TWNow . ' Tahun ' . $tahunNow,
            'kecamatan' => Kecamatan::whereNotIn('id', [1674])->get(),
            'twNow' => $TWNow,
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
        $data = EntryTH::updateOrCreate(
            [
                'TW' =>  $request->TW,
                'tahun' => $request->tahun,
                'kec_id' => $request->kec_id,
            ]
        );
        EntryTH::where('id', $data->id)->update(['status' => 4]);


        foreach ($tanamanIds as $tanamanId) {
            $validasiValue = $request->input('validasi' . $tanamanId);
            $data = EntryTH::updateOrCreate(
                [
                    'TW' =>  $request->TW,
                    'tahun' => $request->tahun,
                    'kec_id' => $request->kec_id,
                ]
            );
            TH::where('tanaman_id', $tanamanId)->where('entry_id', $data->id)->update(['status' => $validasiValue]);
            // }
            if ($validasiValue == 3) { //jika ada yang direvisi maka revisi->1 
                $catatanDinas = $request->input('catatanDinas' . $tanamanId);

                TH::where('tanaman_id', $tanamanId)->where('entry_id', $data->id)->update(
                    [
                        'catatan_dinas' => $catatanDinas,
                    ]
                );
                $data->update(['status' => 3]); //update EntryTH
            } elseif ($validasiValue == 2) {  //jika ada tanaman baru
                $data->update(['status' => 3]); //update EntryTH

                $catatanDinas = $request->input('catatanDinas' . $tanamanId);

                // Menentukan TW lalu
                $TWLalu = sprintf("%02d", $request->TW == 1 ? 4 : $request->TW - 1);
                if ($request->TW == 1) {
                    $tahunLalu =  $request->tahun - 1;
                } else {
                    $tahunLalu =  $request->tahun;
                }
                $data = EntryTH::updateOrCreate(
                    [
                        'TW' => $TWLalu,
                        'tahun' => $tahunLalu,
                        'kec_id' => $request->kec_id,
                    ]
                );
                $data = [
                    'r3' => 0,
                    'r4' => 0,
                    'r5' => 0,
                    'r6' => 0,
                    'r7' => 0,
                    'r8' => 0,
                    'r9' => 0,
                    'r10' => 0,
                    'r11' => 0,
                    'note' => '',
                    'tanaman_id' => $tanamanId,
                    'status' => 6,
                    'status_tanaman' => 2, //tanaman_baru
                    'catatan_dinas' => $catatanDinas,
                    'user_id' => Auth()->user()->id,
                    'entry_id' => $data->id,
                ];
                TH::create($data);
            }
        }

        return redirect('thvalidasi/dinas')->with('success', 'Validasi Berhasil di Lakukan');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EntryTH  $entryTH
     * @return \Illuminate\Http\Response
     */
    public function show(EntryTH $dina)
    {
        $this->authorize('dinas');

        $entryth = EntryTH::where('id', $dina->id)->first();

        return view('th.validasi.dinas.show', [
            'title' => "Kecamatan " . $entryth->kecamatan->kecamatan . " Triwulan " . $entryth->TW . " Tahun " . $entryth->tahun,
            'TW' => $entryth->TW,
            'tahun' =>  $entryth->tahun,
            'kecamatan' => $entryth->kec_id,
            'entryNow' => EntryTH::where('kec_id', $entryth->kec_id)->where('TW', $entryth->TW)->where('tahun', $entryth->tahun)->first(),
            'tanaman' => Tanaman::where('jenis_sph', 'TH')->orderBy('urut_kues', 'asc')->get(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\EntryTH  $entryTH
     * @return \Illuminate\Http\Response
     */
    public function edit(EntryTH $entryTH)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\EntryTH  $entryTH
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EntryTH $entryTH)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EntryTH  $entryTH
     * @return \Illuminate\Http\Response
     */
    public function destroy(EntryTH $entryTH)
    {
        //
    }
}
