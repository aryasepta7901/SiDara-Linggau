<?php

namespace App\Http\Controllers;

use App\Models\EntryTBF;
use App\Models\Kecamatan;
use App\Models\Tanaman;
use App\Models\TBF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

use function App\Providers\getTrimester;

class ValidasiTBFDinasController extends Controller
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
        $selectedTW = Session::get('selectedTWTBF');
        $selectedYear = Session::get('selectedYearTBF');
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
        return view('tbf.validasi.dinas.index', [
            'title' => 'Dashboard TBF Triwulan ' . $TWNow . ' Tahun ' . $tahunNow,
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
        $data = EntryTBF::updateOrCreate(
            [
                'TW' =>  $request->TW,
                'tahun' => $request->tahun,
                'kec_id' => $request->kec_id,
            ]
        );
        EntryTBF::where('id', $data->id)->update(['status' => 4]);


        foreach ($tanamanIds as $tanamanId) {
            $validasiValue = $request->input('validasi' . $tanamanId);
            $data = EntryTBF::updateOrCreate(
                [
                    'TW' =>  $request->TW,
                    'tahun' => $request->tahun,
                    'kec_id' => $request->kec_id,
                ]
            );
            TBF::where('tanaman_id', $tanamanId)->where('entry_id', $data->id)->update(['status' => $validasiValue]);
            // }
            if ($validasiValue == 3) { //jika ada yang direvisi maka revisi->1 
                $catatanDinas = $request->input('catatanDinas' . $tanamanId);

                TBF::where('tanaman_id', $tanamanId)->where('entry_id', $data->id)->update(
                    [
                        'catatan_dinas' => $catatanDinas,
                    ]
                );
                $data->update(['status' => 3]); //update EntryTBF
            } elseif ($validasiValue == 2) {  //jika ada tanaman baru
                $data->update(['status' => 3]); //update EntryTBF

                $catatanDinas = $request->input('catatanDinas' . $tanamanId);

                // Menentukan TW lalu
                $TWLalu = sprintf("%02d", $request->TW == 1 ? 4 : $request->TW - 1);
                if ($request->TW == 1) {
                    $tahunLalu =  $request->tahun - 1;
                } else {
                    $tahunLalu =  $request->tahun;
                }
                $data = EntryTBF::updateOrCreate(
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
                TBF::create($data);
            }
        }

        return redirect('tbfvalidasi/dinas')->with('success', 'Validasi Berhasil di Lakukan');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EntryTBF  $entryTBF
     * @return \Illuminate\Http\Response
     */
    public function show(EntryTBF $dina)
    {
        $this->authorize('dinas');

        $entryTBF = EntryTBF::where('id', $dina->id)->first();

        return view('tbf.validasi.dinas.show', [
            'title' => "Kecamatan " . $entryTBF->kecamatan->kecamatan . " Triwulan " . $entryTBF->TW . " Tahun " . $entryTBF->tahun,
            'TW' => $entryTBF->TW,
            'tahun' =>  $entryTBF->tahun,
            'kecamatan' => $entryTBF->kec_id,
            'entryNow' => EntryTBF::where('kec_id', $entryTBF->kec_id)->where('TW', $entryTBF->TW)->where('tahun', $entryTBF->tahun)->first(),
            'tanaman' => Tanaman::where('jenis_sph', 'TBF')->orderBy('urut_kues', 'asc')->get(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\EntryTBF  $entryTBF
     * @return \Illuminate\Http\Response
     */
    public function edit(EntryTBF $entryTBF)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\EntryTBF  $entryTBF
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EntryTBF $entryTBF)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EntryTBF  $entryTBF
     * @return \Illuminate\Http\Response
     */
    public function destroy(EntryTBF $entryTBF)
    {
        //
    }
}
