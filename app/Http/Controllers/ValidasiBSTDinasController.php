<?php

namespace App\Http\Controllers;

use App\Models\BST;
use App\Models\entrybst;
use App\Models\Kecamatan;
use App\Models\Tanaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

use function App\Providers\getTrimester;

class ValidasiBSTDinasController extends Controller
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
        $selectedTW = Session::get('selectedTWBST');
        $selectedYear = Session::get('selectedYearBST');
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
        return view('bst.validasi.dinas.index', [
            'title' => 'Dashboard BST Triwulan ' . $TWNow . ' Tahun ' . $tahunNow,
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
        $data = EntryBST::updateOrCreate(
            [
                'TW' =>  $request->TW,
                'tahun' => $request->tahun,
                'kec_id' => $request->kec_id,
            ]
        );
        EntryBST::where('id', $data->id)->update(['status' => 4]);


        foreach ($tanamanIds as $tanamanId) {
            $validasiValue = $request->input('validasi' . $tanamanId);
            $data = EntryBST::updateOrCreate(
                [
                    'TW' =>  $request->TW,
                    'tahun' => $request->tahun,
                    'kec_id' => $request->kec_id,
                ]
            );
            BST::where('tanaman_id', $tanamanId)->where('entry_id', $data->id)->update(['status' => $validasiValue]);
            // }
            if ($validasiValue == 3) { //jika ada yang direvisi maka revisi->1 
                $catatanDinas = $request->input('catatanDinas' . $tanamanId);

                BST::where('tanaman_id', $tanamanId)->where('entry_id', $data->id)->update(
                    [
                        'catatan_dinas' => $catatanDinas,
                    ]
                );
                $data->update(['status' => 3]); //update EntryBST
            } elseif ($validasiValue == 2) {  //jika ada tanaman baru
                $data->update(['status' => 3]); //update EntryBST

                $catatanDinas = $request->input('catatanDinas' . $tanamanId);

                // Menentukan TW lalu
                $TWLalu = sprintf("%02d", $request->TW == 1 ? 4 : $request->TW - 1);
                if ($request->TW == 1) {
                    $tahunLalu =  $request->tahun - 1;
                } else {
                    $tahunLalu =  $request->tahun;
                }
                $data = EntryBST::updateOrCreate(
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
                BST::create($data);
            }
        }

        return redirect('bstvalidasi/dinas')->with('success', 'Validasi Berhasil di Lakukan');
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EntryBST  $entrybst
     * @return \Illuminate\Http\Response
     */
    public function show(EntryBST $dina)
    {
        $this->authorize('dinas');

        $entryBST = EntryBST::where('id', $dina->id)->first();

        return view('bst.validasi.dinas.show', [
            'title' => "Kecamatan " . $entryBST->kecamatan->kecamatan . " Triwulan " . $entryBST->TW . " Tahun " . $entryBST->tahun,
            'TW' => $entryBST->TW,
            'tahun' =>  $entryBST->tahun,
            'kecamatan' => $entryBST->kec_id,
            'entryNow' => EntryBST::where('kec_id', $entryBST->kec_id)->where('TW', $entryBST->TW)->where('tahun', $entryBST->tahun)->first(),
            'tanaman' => Tanaman::where('jenis_sph', 'BST')->orderBy('urut_kues', 'asc')->get(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\entrybst  $entrybst
     * @return \Illuminate\Http\Response
     */
    public function edit(entrybst $entrybst)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\entrybst  $entrybst
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, entrybst $entrybst)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\entrybst  $entrybst
     * @return \Illuminate\Http\Response
     */
    public function destroy(entrybst $entrybst)
    {
        //
    }
}
