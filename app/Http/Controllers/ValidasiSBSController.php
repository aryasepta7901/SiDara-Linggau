<?php

namespace App\Http\Controllers;

use App\Models\EntrySBS;
use App\Models\Kecamatan;
use App\Models\SBS;
use App\Models\Tanaman;
use Dotenv\Parser\Entry;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Session;

class ValidasiSBSController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('bps');
        // Mengambil bulan dan tahun dari sesi
        $selectedMonth = Session::get('selectedMonth');
        $selectedYear = Session::get('selectedYear');
        if ($selectedMonth != null || $selectedYear != null) {
            // Membuat objek Carbon dari bulan dan tahun yang dipilih
            $bulanTahun = Carbon::createFromDate($selectedYear, $selectedMonth, 1);

            $bulanNow = $selectedMonth;
            $tahunNow = $selectedYear;
        } else {
            $tanggalNow = date('d');
            if ($tanggalNow <= 19) {
                // Mendapatkan tanggal saat ini
                $today = Carbon::now();
                // Mengurangkan satu bulan dari tanggal saat ini
                $bulanTahun = $today->subMonth();
                // Bulan sekarang
                $bulanNow = date('m') == 1 ? 12 : date('m') - 1;
                $tahunNow = date('m') == 1 ? date('Y') - 1 : date('Y');
            } elseif ($tanggalNow >= 20) {
                // Mendapatkan tanggal saat ini
                $bulanTahun = Carbon::now();
                // Bulan sekarang
                $bulanNow = date('m');
                $tahunNow = date('Y');
            }
        }
        return view('sbs.validasi.bps.index', [
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
        if ($request->kirimValidasi) {
            // Mendapatkan data dari radio button dengan nama dinamis
            $tanamanIds = $request->input('tanaman_id');
            $data = EntrySBS::updateOrCreate(
                [
                    'bulan' =>  $request->bulan,
                    'tahun' => $request->tahun,
                    'kec_id' => $request->kec_id,
                ]
            );
            EntrySBS::where('id', $data->id)->update(['status' => 6]);

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
                if ($validasiValue == 5) { //jika ada yang direvisi 
                    $catatanBPS = $request->input('catatanBPS' . $tanamanId);

                    SBS::where('tanaman_id', $tanamanId)->where('entry_id', $data->id)->update(
                        [
                            'catatan_bps' => $catatanBPS,
                        ]
                    );
                    $data->update(['status' => 5]); //update entrySBS
                } elseif ($validasiValue == 2) {  //jika ada tanaman baru
                    $data->update(['status' => 5]); //update entrySBS

                    $catatanBPS = $request->input('catatanBPS' . $tanamanId);
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
                        'catatan_dinas' => $catatanBPS,
                        'user_id' => Auth()->user()->id,
                        'entry_id' => $data->id,
                    ];
                    SBS::create($data);
                }
            }
            return redirect('validasi/bps')->with('success', 'Validasi Berhasil di Lakukan');
        }

        // Tambah
        if ($request->Btntanaman_id) {

            $entryNow = EntrySBS::where('id', $request->entry_id)->first();

            $bulanLalu = $entryNow->bulan == 1 ? 12 : $entryNow->bulan  - 1;
            $tahunLalu = $entryNow->bulan  == 1 ? $entryNow->tahun - 1 : $entryNow->tahun;
            $entryLast = EntrySBS::updateOrCreate(
                [
                    'bulan' =>  $bulanLalu,
                    'tahun' => $tahunLalu,
                    'kec_id' => $entryNow->kec_id,
                    // 'user_id' => auth()->user()->id,
                ]
            );
            $sbsLast = SBS::where('tanaman_id', $request->Btntanaman_id)->where('entry_id', $entryLast->id)->first();
            if ($sbsLast == null) {
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
                    'entry_id' =>  $entryLast->id,
                    'tanaman_id' => $request->Btntanaman_id,
                    'user_id' => auth()->user()->id,
                    'status' => 6,
                    'status_tanaman' => 2 // tanaman_baru
                ];
                SBS::create($data);
            } else {
                $sbsLast->update(['status_tanaman' => 2]);
            }
            // Bulan Sekarang
            $data = [
                'r4' => 0,
                'r5' => 0,
                'r6' => 0,
                'r7' => 0,
                'r8' => $request->r8,
                'r9' => $request->r8,
                'r10' => 0,
                'r11' => 0,
                'r12' => 0,
                'note' => '',
                'entry_id' =>  $entryNow->id,
                'tanaman_id' => $request->Btntanaman_id,
                'user_id' => auth()->user()->id,
                'status' => 6,
                'status_tanaman' => 0 // tanaman_baru
            ];
            SBS::create($data);
        }
        return redirect('validasi/bps/' . $request->entry_id)->with('success', 'Tanaman Berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Kecamatan  $kecamatan
     * @return \Illuminate\Http\Response
     */
    public function show(EntrySBS $bp)
    {
        $this->authorize('bps');

        $entrySBS = EntrySBS::where('id', $bp->id)->first();
        $data = SBS::where('entry_id', $entrySBS->id)->whereNotIn('status_tanaman', [1])->pluck('tanaman_id')->toArray();


        $bulanTahun = Carbon::createFromDate($entrySBS->tahun, $entrySBS->bulan, 1);
        return view('sbs.validasi.bps.show', [
            'title' => "Kecamatan " . $entrySBS->kecamatan->kecamatan . "(" . Carbon::parse($bulanTahun)->isoFormat('MMMM YYYY', 'id') . ")",
            'bulan' => $entrySBS->bulan,
            'tahun' =>  $entrySBS->tahun,
            'kecamatan' => $entrySBS->kec_id,
            'entryNow' => EntrySBS::where('kec_id', $entrySBS->kec_id)->where('bulan', $entrySBS->bulan)->where('tahun', $entrySBS->tahun)->first(),
            'tanaman' => Tanaman::where('jenis_sph', 'SBS')->orderBy('urut_kues', 'asc')->get(),
            'allTanaman' => Tanaman::whereNotIn('id', $data)->get(),

        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Kecamatan  $kecamatan
     * @return \Illuminate\Http\Response
     */
    public function edit(Kecamatan $kecamatan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Kecamatan  $kecamatan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Kecamatan $kecamatan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Kecamatan  $kecamatan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $entryNow = EntrySBS::where('id', $request->entry_id)->first();

        $bulanLalu = $entryNow->bulan == 1 ? 12 : $entryNow->bulan  - 1;
        $tahunLalu = $entryNow->bulan  == 1 ? $entryNow->tahun - 1 : $entryNow->tahun;
        $entryLast = EntrySBS::where('kec_id', $entryNow->kec_id)->where('bulan', $bulanLalu)->where('tahun', $tahunLalu)->first();



        $sbsLast = SBS::where('tanaman_id', $request->tanaman_id)->where('entry_id', $entryLast->id)->first();
        if ($sbsLast->r4 == 0) {
            //    Hapus Jika 0
            $sbsLast->delete();
        } else {
            $sbsLast->update(['status_tanaman' => 1]);
        }
        if ($entryNow != null) {
            SBS::where('tanaman_id', $request->tanaman_id)->where('entry_id', $entryNow->id)->delete();
        }
        // Kontrak::destroy($kontrak->id);
        return redirect()->back()->with('success', 'Data Tanaman Berhasil di Hapus');
    }
}
