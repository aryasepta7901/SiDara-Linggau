<?php

namespace App\Http\Controllers;

use App\Models\EntryTBF;
use App\Models\Kecamatan;
use App\Models\Tanaman;
use App\Models\TBF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

use function App\Providers\getTrimester;

class ValidasiTBFBPSController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('bps');
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
        return view('tbf.validasi.bps.index', [
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
        if ($request->kirimValidasi) {
            // Mendapatkan data dari radio button dengan nama dinamis
            $tanamanIds = $request->input('tanaman_id');
            $data = EntryTBF::updateOrCreate(
                [
                    'TW' =>  $request->TW,
                    'tahun' => $request->tahun,
                    'kec_id' => $request->kec_id,
                ]
            );
            EntryTBF::where('id', $data->id)->update(['status' => 6]);

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
                if ($validasiValue == 5) { //jika ada yang direvisi 
                    $catatanBPS = $request->input('catatanBPS' . $tanamanId);

                    TBF::where('tanaman_id', $tanamanId)->where('entry_id', $data->id)->update(
                        [
                            'catatan_bps' => $catatanBPS,
                        ]
                    );
                    $data->update(['status' => 5]); //update EntryTBF
                } elseif ($validasiValue == 2) {  //jika ada tanaman baru
                    $data->update(['status' => 5]); //update EntryTBF

                    $catatanBPS = $request->input('catatanBPS' . $tanamanId);
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
                        'catatan_dinas' => $catatanBPS,
                        'user_id' => auth()->user()->id,
                        'entry_id' => $data->id,
                    ];
                    TBF::create($data);
                }
            }
            return redirect('tbfvalidasi/bps')->with('success', 'Validasi Berhasil di Lakukan');
        }

        // Tambah
        if ($request->Btntanaman_id) {

            $entryNow = EntryTBF::where('id', $request->entry_id)->first();

            // Menentukan TW lalu
            $TWLalu = sprintf("%02d", $entryNow->TW == 1 ? 4 : $entryNow->TW - 1);
            $tahunLalu = $entryNow->bulan  == 1 ? $entryNow->tahun - 1 : $entryNow->tahun;
            if ($entryNow->TW == 1) {
                $tahunLalu =  $entryNow->tahun - 1;
            } else {
                $tahunLalu =  $entryNow->tahun;
            }
            $entryLast = EntryTBF::updateOrCreate(
                [
                    'TW' =>  $TWLalu,
                    'tahun' => $tahunLalu,
                    'kec_id' => $entryNow->kec_id,
                    // 'user_id' => auth()->user()->id,
                ]
            );
            $tbfLast = TBF::where('tanaman_id', $request->Btntanaman_id)->where('entry_id', $entryLast->id)->first();
            if ($tbfLast == null) {
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
                    'entry_id' =>  $entryLast->id,
                    'tanaman_id' => $request->Btntanaman_id,
                    'user_id' => auth()->user()->id,
                    'status' => 6,
                    'status_tanaman' => 2 // tanaman_baru
                ];
                TBF::create($data);
            } else {
                $tbfLast->update(['status_tanaman' => 2]);
            }
            // TW Sekarang
            $data = [
                'r3' => 0,
                'r4' => 0,
                'r5' => 0,
                'r6' => 0,
                'r7' => $request->r7,
                'r8' => $request->r7,
                'r9' => 0,
                'r10' => 0,
                'r11' => 0,
                'note' => '',
                'entry_id' =>  $entryNow->id,
                'tanaman_id' => $request->Btntanaman_id,
                'user_id' => auth()->user()->id,
                'status' => 6,
                'status_tanaman' => 0 // tanaman_baru
            ];
            TBF::create($data);
        }
        return redirect('tbfvalidasi/bps/' . $request->entry_id)->with('success', 'Tanaman Berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EntryTBF  $entryTBF
     * @return \Illuminate\Http\Response
     */
    public function show(EntryTBF $bp)
    {

        $this->authorize('bps');

        $entryTBF = EntryTBF::where('id', $bp->id)->first();
        $data = TBF::where('entry_id', $entryTBF->id)->whereNotIn('status_tanaman', [1])->pluck('tanaman_id')->toArray();


        return view('tbf.validasi.bps.show', [
            'title' => "Kecamatan " . $entryTBF->kecamatan->kecamatan . " Triwulan " . $entryTBF->TW . " Tahun " . $entryTBF->tahun,
            'TW' => $entryTBF->TW,
            'tahun' =>  $entryTBF->tahun,
            'kecamatan' => $entryTBF->kec_id,
            'entryNow' => EntryTBF::where('kec_id', $entryTBF->kec_id)->where('TW', $entryTBF->TW)->where('tahun', $entryTBF->tahun)->first(),
            'tanaman' => Tanaman::where('jenis_sph', 'TBF')->orderBy('urut_kues', 'asc')->get(),
            'allTanaman' => Tanaman::whereNotIn('id', $data)->where('jenis_sph', 'TBF')->get(),

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
    public function destroy(Request $request)
    {
        $entryNow = EntryTBF::where('id', $request->entry_id)->first();

        // Menentukan TW lalu
        $TWLalu = sprintf("%02d", $entryNow->TW == 1 ? 4 : $entryNow->TW - 1);
        $tahunLalu = $entryNow->bulan  == 1 ? $entryNow->tahun - 1 : $entryNow->tahun;
        if ($entryNow->TW == 1) {
            $tahunLalu =  $entryNow->tahun - 1;
        } else {
            $tahunLalu =  $entryNow->tahun;
        }
        $entryLast = EntryTBF::where('kec_id', $entryNow->kec_id)->where('TW', $TWLalu)->where('tahun', $tahunLalu)->first();



        $tbfLast = TBF::where('tanaman_id', $request->tanaman_id)->where('entry_id', $entryLast->id)->first();
        if ($tbfLast->r4 == 0) {
            //    Hapus Jika 0
            $tbfLast->delete();
        } else {
            $tbfLast->update(['status_tanaman' => 1]);
        }
        if ($entryNow != null) {
            TBF::where('tanaman_id', $request->tanaman_id)->where('entry_id', $entryNow->id)->delete();
        }
        // Kontrak::destroy($kontrak->id);
        return redirect()->back()->with('success', 'Data Tanaman Berhasil di Hapus');
    }
}
