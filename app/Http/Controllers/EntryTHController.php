<?php

namespace App\Http\Controllers;

use App\Models\EntryTH;
use App\Models\Kecamatan;
use App\Models\Tanaman;
use App\Models\TH;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

use function App\Providers\getTrimester;

class EntryTHController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('pcl');
        // Mengambil bulan dan tahun dari sesi
        $selectedTW = Session::get('selectedTWTH');
        $selectedYear = Session::get('selectedYearTH');


        if ($selectedTW != null || $selectedYear != null) {
            // Membuat objek Carbon dari bulan dan tahun yang dipilih
            $TWNow = $selectedTW;
            $tahunNow = $selectedYear;
            $TWLalu = sprintf("%02d", $TWNow == 1 ? 4 : $TWNow - 1);
            $tahunLalu = $selectedTW == 1 ? $selectedYear - 1 : $selectedYear;
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

            // Menentukan TW lalu
            $TWLalu = sprintf("%02d", $trimester == 1 ? 4 : $trimester - 1);
            if ($TWNow == 1) {
                $tahunLalu = date('Y') - 1;
            } else {
                $tahunLalu = $tahunNow;
            }
        }
        $entryNow = EntryTH::where('kec_id', auth()->user()->kec_id)->where('TW', $TWNow)->where('tahun', $tahunNow)->first();
        $entryLast = EntryTH::where('kec_id', auth()->user()->kec_id)->where('TW', $TWLalu)->where('tahun', $tahunLalu)->where('status', 6)->first();
        if ($entryLast != null) {
            $data = TH::where('entry_id', $entryLast->id)->whereNotIn('status_tanaman', [1])->pluck('tanaman_id')->toArray();
        } else {
            $data = [];
        }
        return view('th.entry.index', [
            'title' => 'Entry TH Triwulan ' . $TWNow . ' Tahun ' . $tahunNow,
            'kecamatan' => Kecamatan::where('id', auth()->user()->kec_id)->first(),
            'tanaman' => Tanaman::whereIn('id', $data)->orderBy('urut_kues', 'asc')->get(),
            'allTanaman' => Tanaman::whereNotIn('id', $data)->where('jenis_sph', 'TH')->get(),
            'entryLast' => $entryLast,
            'entryNow' => $entryNow,
            'twNow' => $TWNow,
            'tahunNow' => $tahunNow,
            'twLalu' => $TWLalu,
            'tahunLalu' => $tahunLalu,

        ]);
    }
    public function storeMonthYearSelection(Request $request)
    {
        if ($request->resetButton != null) {
            Session::remove('selectedTWTH');
            Session::remove('selectedYearTH');
        } else {
            // Menyimpan Triwulan dan tahun dalam sesi
            Session::put('selectedTWTH', $request->triwulan);
            Session::put('selectedYearTH', $request->tahun);
        }
        return redirect()->back();
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
        if ($request->submit1) {
            $request->validate(
                [
                    'r3' => 'required|max:6|gt:0',
                    'r4'  => 'required|lte:r3',
                    'r5'  => 'lte:r3',
                    'r6'  => 'required|lte:r3',
                    'r7' => 'required|max:6',

                ],
                [
                    'required' => ':attribute Wajib di Isi',
                    'lte' => ' :attribute Harus lebih kecil atau sama dengan  dari :value.',
                    'gt' => ' :attribute tidak boleh :value.',
                    'max' => ':attribute Maksimal :max Karakter'
                ]
            );

            if ($request->r4 + $request->r5 + $request->r6 <= $request->r3) {

                $selectedTW = Session::get('selectedTWTH');
                $selectedYear = Session::get('selectedYearTH');
                if ($selectedTW != null || $selectedYear != null) {
                    $TWNow =  $selectedTW;
                    $tahunNow =  $selectedYear;
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
                $data = EntryTH::updateOrCreate(
                    [
                        'TW' =>  $TWNow,
                        'tahun' => $tahunNow,
                        'kec_id' => $request->kecamatan_id,
                    ]
                );
                $entry = TH::where('tanaman_id', $request->tanaman_id)->where('entry_id', $data->id)->first();
                if ($entry == null) {
                    $id = $request->id;
                } else {
                    $id = $entry->id;
                }
                $total = $request->r3 - $request->r4 - $request->r6 + $request->r7;
                if ($total == 0) {
                    $status_tanaman = 1;
                } else {
                    $status_tanaman = 0;
                }
                TH::updateOrCreate(
                    ['id' => $id],
                    [
                        'r3' => $request->r3,
                        'r4' => $request->r4,
                        'r5' => $request->r5,
                        'r6' => $request->r6,
                        'r7' => $request->r7,
                        'r8' => $total,
                        'status_tanaman' => $status_tanaman,
                        'entry_id' => $data->id,
                        'tanaman_id' => $request->tanaman_id,
                        'user_id' => $request->user_id,
                    ]
                );
                return redirect('/thentry/pertProd/' . $request->tanaman_id);
            } else {

                // Gunakan session untuk menyimpan pesan kesalahan
                $tanaman = Tanaman::where('id', $request->tanaman_id)->first();
                if ($tanaman->belum_habis == 0) {
                    Session::flash('error1', 'Terdapat Kesalahan: Total Nilai R4 dan R6 berjumlah ' . $request->r4 + $request->r5 + $request->r6);
                } else {
                    Session::flash('error1', 'Terdapat Kesalahan: Total Nilai R4 R5 dan R6 berjumlah ' . $request->r4 + $request->r5 + $request->r6);
                }
                // Redirect kembali
                return back()->withInput()->withErrors('');
            }
        }
        if ($request->submit2) {
            // validasi
            $tanaman = Tanaman::where('id', $request->tanaman_id)->first();
            $min_produktivitas = $tanaman->min_produktivitas;
            $r4 = $request->r4;
            $r5 = $request->r5;
            $min_r9 = $min_produktivitas * $r4;
            $min_r10 = $min_produktivitas * $r5;
            $max_produktivitas = $tanaman->max_produktivitas;
            $max_r9 = $max_produktivitas * $r4;
            $max_r10 = $max_produktivitas * $r5;
            $request->validate(
                [
                    'r9' => 'gte:' . $min_r9 . '|lte:' . $max_r9,
                    'r10' => 'gte:' . $min_r10 . '|lte:' . $max_r10,
                    'r11' => 'gte:' . $request->min_harga . '|lte:' . $request->max_harga,
                ],
                [
                    'required' => ':attribute Wajib di Isi',
                    'lte' => ' :attribute Harus lebih kecil atau sama dengan  dari :value.',
                    'gte' => ' :attribute Harus lebih besar atau sama dengan  dari :value.'
                ]
            );
            // entry2
            $selectedTW = Session::get('selectedTWTH');
            $selectedYear = Session::get('selectedYearTH');
            if ($selectedTW != null || $selectedYear != null) {
                $TWNow =  $selectedTW;
                $tahunNow =  $selectedYear;
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
            $data = EntryTH::updateOrCreate(
                [
                    'TW' =>  $TWNow,
                    'tahun' => $tahunNow,
                    'kec_id' => auth()->user()->kec_id,
                ]
            );
            $entry = TH::where('tanaman_id', $request->tanaman_id)->where('entry_id', $data->id)->first();
            if ($request->r9 == null) {
                $r9 = 0;
            } else {
                $r9 = $request->r9;
            }
            if ($request->r10 == null) {
                $r10 = 0;
            } else {
                $r10 = $request->r10;
            }
            if ($request->r9 == null && $request->r10 == null) {
                $r11 = 0;
            } else {
                $r11 = $request->r11;
            }
            TH::updateOrCreate(
                ['id' => $entry->id],
                [

                    'r9' => $r9,
                    'r10' => $r10,
                    'r11' => $r11,
                    'note' => $request->note,
                    'status' => 1,
                ]
            );
            return redirect('/thentry')->with('success', 'Tanaman Berhasil dilakukan Entry');
        }

        // Tanaman Baru
        if ($request->submit3) {
            $request->validate(
                [
                    'r7' => 'required|gt:0',
                    'note' => 'required',
                ],
                [
                    'required' => ':attribute Wajib di Isi',
                    'gt' => ' :attribute tidak boleh :value.',
                ]
            );
            // entry3
            $selectedTW = Session::get('selectedTWTH');
            $selectedYear = Session::get('selectedYearTH');
            if ($selectedTW != null || $selectedYear != null) {
                $TWNow =  $selectedTW;
                $tahunNow =  $selectedYear;
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
            $data = EntryTH::updateOrCreate(
                [
                    'TW' =>  $TWNow,
                    'tahun' => $tahunNow,
                    'kec_id' => auth()->user()->kec_id,
                ]
            );
            $entry = TH::where('tanaman_id', $request->tanaman_id)->where('entry_id', $data->id)->first();
            if ($entry == null) {
                $id = $request->id;
            } else {
                $id = $entry->id;
            }
            TH::updateOrCreate(
                ['id' => $id],
                [
                    'r3' => 0,
                    'r4' => 0,
                    'r5' => 0,
                    'r6' => 0,
                    'r7' => $request->r7,
                    'r8' => $request->r7,
                    'r9' => 0,
                    'r10' => 0,
                    'r11' => 0,
                    'tanaman_id' => $request->tanaman_id,
                    'status' => 1,
                    'user_id' => auth()->user()->id,
                    'entry_id' => $data->id,
                    'note' => $request->note,
                ]
            );
            return redirect('/thentry')->with('success', 'Tanaman Berhasil dilakukan Entry');
        }

        if ($request->Btntanaman_id) {

            $selectedTW = Session::get('selectedTWTH');
            $selectedYear = Session::get('selectedYearTH');
            if ($selectedTW != null || $selectedYear != null) {
                // Membuat objek Carbon dari bulan dan tahun yang dipilih
                $TWNow = $selectedTW;
                $tahunNow = $selectedYear;
                $TWLalu = sprintf("%02d", $TWNow == 1 ? 4 : $TWNow - 1);
                $tahunLalu = $selectedTW == 1 ? $selectedYear - 1 : $selectedYear;
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
                // Menentukan TW lalu
                $TWLalu = sprintf("%02d", $trimester == 1 ? 4 : $trimester - 1);
                if ($TWNow == 1) {
                    $tahunLalu = date('Y') - 1;
                } else {
                    $tahunLalu = $tahunNow;
                }
            }

            $data = EntryTH::updateOrCreate(
                [
                    'TW' =>  $TWLalu,
                    'tahun' => $tahunLalu,
                    'kec_id' => auth()->user()->kec_id,
                    // 'user_id' => auth()->user()->id,
                ]
            );
            $entry = TH::where('tanaman_id', $request->Btntanaman_id)->where('entry_id', $data->id)->first();
            if ($entry == null) {
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
                    'entry_id' => $data->id,
                    'tanaman_id' => $request->Btntanaman_id,
                    'user_id' => auth()->user()->id,
                    'status' => 6,
                    'status_tanaman' => 2 // tanaman_baru
                ];
                TH::create($data);
            } else {
                $entry->update(['status_tanaman' => 2]);
            }

            return redirect('/thentry')->with('success', 'Berhasil Menambahkan Komoditas Baru, Silahkan Lakukan Entry Pada Komoditas Tersebut');
        }
        if ($request->sendKues) {
            //$bulan = sprintf("%02d", $request->bulan);


            $EntryTH = EntryTH::where('kec_id', auth()->user()->kec_id)->where('TW', $request->triwulan)->where('tahun', $request->tahun)->first();

            if ($EntryTH->status == 5) {
                // revisi dari BPS
                $EntryTH->update(['status' => 4]);
                TH::where('entry_id', $EntryTH->id)->update(['status' => 4]);
            } else {
                // revisi dinas
                $EntryTH->update(['status' => 2]);
                // $tanamanid_array = $EntryTH->pluck('tanaman_id')->toArray();
                // Gunakan whereIn untuk mencocokkan beberapa nilai tanaman_id
                TH::where('entry_id', $EntryTH->id)->update(['status' => 2]);
            }


            return redirect('/thentry')->with('success', 'Kuesioner Berhasil di Kirim');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EntryTH  $entryTH
     * @return \Illuminate\Http\Response
     */
    public function show(EntryTH $entryTH)
    {
        $this->authorize('pcl');

        $id = last(explode('/', request()->url()));
        $entryth = EntryTH::where('id', $id)->first();
        return view('th.entry.show', [
            'title' => "Kecamatan " . $entryth->kecamatan->kecamatan . " Triwulan " . $entryth->TW . " Tahun " . $entryth->tahun,
            'bulan' => $entryth->bulan,
            'tahun' =>  $entryth->tahun,
            'kecamatan' => $entryth->kec_id,
            'entryNow' => EntryTH::where('kec_id', $entryth->kec_id)->where('TW', $entryth->TW)->where('tahun', $entryth->tahun)->first(),
            'tanaman' => Tanaman::where('jenis_sph', 'TH')->orderBy('urut_kues', 'asc')->get(),
        ]);
    }
    public function reset(Request $request) //kondisi harus clean, jika tidak clean maka akan reset form
    {
        TH::where('tanaman_id', $request->tanaman_id)->where('entry_id', $request->entry_id)->delete();
        return redirect('/thentry/pertLuas/' . $request->tanaman_id);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\EntryTH  $entryTH
     * @return \Illuminate\Http\Response
     */
    public function pertLuas(Tanaman $thentry)
    {
        $this->authorize('pcl');
        // Mengambil TW dan tahun dari sesi
        $selectedTW = Session::get('selectedTWTH');
        $selectedYear = Session::get('selectedYearTH');


        if ($selectedTW != null || $selectedYear != null) {
            // Membuat objek Carbon dari bulan dan tahun yang dipilih
            $TWNow = $selectedTW;
            $tahunNow = $selectedYear;
            $TWLalu = sprintf("%02d", $TWNow == 1 ? 4 : $TWNow - 1);
            $tahunLalu = $selectedTW == 1 ? $selectedYear - 1 : $selectedYear;
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

            // Menentukan TW lalu
            $TWLalu = sprintf("%02d", $trimester == 1 ? 4 : $trimester - 1);
            if ($TWNow == 1) {
                $tahunLalu = date('Y') - 1;
            } else {
                $tahunLalu = $tahunNow;
            }
        }
        $entryLast = EntryTH::where('kec_id', auth()->user()->kec_id)->where('TW', $TWLalu)->where('tahun', $tahunLalu)->first();
        $entryNow = EntryTH::where('kec_id', auth()->user()->kec_id)->where('TW', $TWNow)->where('tahun', $tahunNow)->first();
        if ($entryNow == null) {
            $id = '';
        } else {
            $id = $entryNow->id;
        }
        $thNow = TH::where('tanaman_id', $thentry->id)->where('entry_id', $id)->first();
        return view('th.entry.show1', [
            'title' => 'Tanaman ' . $thentry->nama_tanaman,
            'thLast' => TH::where('tanaman_id', $thentry->id)->where('entry_id', $entryLast->id)->first(),
            'thNow' => $thNow,
            'entryNow' => $entryNow,
            'tanaman' => Tanaman::where('id', $thentry->id)->first(),
            'twNow' => $TWNow,

        ]);
    }
    public function pertProd(Tanaman $thentry)
    {  // Mengambil bulan dan tahun dari sesi
        $this->authorize('pcl');

        // Mengambil TW dan tahun dari sesi
        $selectedTW = Session::get('selectedTWTH');
        $selectedYear = Session::get('selectedYearTH');


        if ($selectedTW != null || $selectedYear != null) {
            // Membuat objek Carbon dari bulan dan tahun yang dipilih
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

        $entryNow_id = EntryTH::where('kec_id', auth()->user()->kec_id)->where('TW', $TWNow)->where('tahun', $tahunNow)->first()->id;

        return view('th.entry.show2', [
            'title' => 'Tanaman ' . $thentry->nama_tanaman,
            'thNow' => TH::where('tanaman_id', $thentry->id)->where('entry_id', $entryNow_id)->first(),
            'tanaman' => Tanaman::where('id', $thentry->id)->first(),
        ]);
    }
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
    public function destroy(Request $request, Tanaman $thentry)
    {

        $entryLast = EntryTH::where('kec_id', auth()->user()->kec_id)->where('TW', $request->twLalu)->where('tahun', $request->tahunLalu)->first();
        $entryNow = EntryTH::where('kec_id', auth()->user()->kec_id)->where('TW', $request->twNow)->where('tahun', $request->tahunNow)->first();

        $thlast = TH::where('tanaman_id', $thentry->id)->where('entry_id', $entryLast->id)->first();
        if ($thlast->r3 == 0) {
            //    Hapus Jika 0
            $thlast->delete();
        } else {
            $thlast->update(['status_tanaman' => 1]);
        }
        if ($entryNow != null) {
            TH::where('tanaman_id', $thentry->id)->where('entry_id', $entryNow->id)->delete();
        }
        // Kontrak::destroy($kontrak->id);
        return redirect()->back()->with('success', 'Data Tanaman Berhasil di Hapus');
    }
}
