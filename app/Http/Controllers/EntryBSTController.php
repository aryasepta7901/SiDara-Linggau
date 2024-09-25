<?php

namespace App\Http\Controllers;

use App\Models\BST;
use App\Models\EntryBST;
use App\Models\Kecamatan;
use App\Models\Tanaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

use function App\Providers\getTrimester;

class EntryBSTController extends Controller
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
        $selectedTW = Session::get('selectedTWBST');
        $selectedYear = Session::get('selectedYearBST');


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
        $entryNow = EntryBST::where('kec_id', auth()->user()->kec_id)->where('TW', $TWNow)->where('tahun', $tahunNow)->first();
        $entryLast = EntryBST::where('kec_id', auth()->user()->kec_id)->where('TW', $TWLalu)->where('tahun', $tahunLalu)->where('status', 6)->first();


        if ($entryLast != null) {
            $data = BST::where('entry_id', $entryLast->id)->whereNotIn('status_tanaman', [1])->pluck('tanaman_id')->toArray();
        } else {
            $data = [];
        }
        return view('bst.entry.index', [
            'title' => 'Entry BST Triwulan ' . $TWNow . ' Tahun ' . $tahunNow,
            'kecamatan' => Kecamatan::where('id', auth()->user()->kec_id)->first(),
            'tanaman' => Tanaman::whereIn('id', $data)->orderBy('urut_kues', 'asc')->get(),
            'allTanaman' => Tanaman::whereNotIn('id', $data)->where('jenis_sph', 'BST')->get(),
            'entryLast' => $entryLast,
            'entryNow' => $entryNow,
            'twNow' => $TWNow,
            'tahunNow' => $tahunNow,
            'twLalu' => $TWLalu,
            'tahunLalu' => $tahunLalu,

        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function storeMonthYearSelection(Request $request)
    {
        if ($request->resetButton != null) {
            Session::remove('selectedTWBST');
            Session::remove('selectedYearBST');
        } else {
            // Menyimpan Triwulan dan tahun dalam sesi
            Session::put('selectedTWBST', $request->triwulan);
            Session::put('selectedYearBST', $request->tahun);
        }
        return redirect()->back();
    }
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
            if ($request->r6 != 0) {
                $request->validate(
                    [
                        'r7' => 'required|lte:r6|min:' . $request->r5,
                        'r8' => 'required|lte:r6',
                        'r9' => 'required|lte:r6',
                    ],
                    [
                        'required' => ':attribute Wajib di Isi',
                        'lte' => ' :attribute Harus lebih kecil atau sama dengan  dari :value.',
                        'min' => ':attribute harus lebih besar atau sama dengan  dari :min.',
                    ]
                );
            } else {
                // Jika r6 =0

            }
            $request->validate(
                [
                    'r3' => 'required|max:6|gt:0',
                    'r4'  => 'required|max:6|lte:r3',
                    'r5'  => 'required|max:6',
                    'r6'  => 'required|max:6',
                ],
                [
                    'required' => ':attribute Wajib di Isi',
                    'gt' => ' :attribute tidak boleh :value.',
                    'max' => ':attribute Maksimal :max Karakter',
                    'lte' => ' :attribute Harus lebih kecil atau sama dengan  dari :value.',

                ]
            );

            if ($request->r7 + $request->r8 + $request->r9 <= $request->r6) {
                $selectedTW = Session::get('selectedTWBST');
                $selectedYear = Session::get('selectedYearBST');
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
                $data = EntryBST::updateOrCreate(
                    [
                        'TW' =>  $TWNow,
                        'tahun' => $tahunNow,
                        'kec_id' => $request->kecamatan_id,
                    ]
                );
                $entry = BST::where('tanaman_id', $request->tanaman_id)->where('entry_id', $data->id)->first();
                if ($entry == null) {
                    $id = $request->id;
                } else {
                    $id = $entry->id;
                }

                if ($request->r6 == 0) {
                    $status_tanaman = 1; //tanaman habis
                    $r7 = 0;
                    $r8 = 0;
                    $r9 = 0;
                } else {
                    $status_tanaman = 0;
                    $r7 = $request->r7;
                    $r8 = $request->r8;
                    $r9 = $request->r9;
                }
                BST::updateOrCreate(
                    ['id' => $id],
                    [
                        'r3' => $request->r3,
                        'r4' => $request->r4,
                        'r5' => $request->r5,
                        'r6' => $request->r6,
                        'r7' => $r7,
                        'r8' => $r8,
                        'r9' => $r9,
                        'status_tanaman' => $status_tanaman,
                        'entry_id' => $data->id,
                        'tanaman_id' => $request->tanaman_id,
                        'user_id' => $request->user_id,
                    ]
                );
                return redirect('/bstentry/pertProd/' . $request->tanaman_id);
            } else {
                // Gunakan session untuk menyimpan pesan kesalahan
                Session::flash('error1', 'Terdapat Kesalahan: Total Nilai R7,R8 dan R9 berjumlah ' . $request->r7 + $request->r8 + $request->r9 . ',tidak boleh lebih besar dari nilai R6 yaitu ' . $request->r6);
                // Redirect kembali
                return back()->withInput()->withErrors('');
            }
        }
        if ($request->submit2) {
            // validasi
            $tanaman = Tanaman::where('id', $request->tanaman_id)->first();
            $min_produktivitas = $tanaman->min_produktivitas;
            $r8 = $request->r8;
            $min_r8 = $min_produktivitas * $r8;
            $max_produktivitas = $tanaman->max_produktivitas;
            $max_r8 = $max_produktivitas * $r8;
            $request->validate(
                [
                    'r10' => 'gte:' . $min_r8 . '|lte:' . $max_r8,
                    'r11' => 'gte:' . $request->min_harga . '|lte:' . $request->max_harga,
                ],
                [
                    'required' => ':attribute Wajib di Isi',
                    'lte' => ' :attribute Harus lebih kecil atau sama dengan  dari :value.',
                    'gte' => ' :attribute Harus lebih besar atau sama dengan  dari :value.'
                ]
            );
            // entry2
            $selectedTW = Session::get('selectedTWBST');
            $selectedYear = Session::get('selectedYearBST');
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
            $data = EntryBST::updateOrCreate(
                [
                    'TW' =>  $TWNow,
                    'tahun' => $tahunNow,
                    'kec_id' => auth()->user()->kec_id,
                ]
            );
            $entry = BST::where('tanaman_id', $request->tanaman_id)->where('entry_id', $data->id)->first();
            if ($request->r10 == null) {
                $r10 = 0;
            } else {
                $r10 = $request->r10;
            }
            if ($request->r11 == null) {
                $r11 = 0;
            } else {
                $r11 = $request->r11;
            }
            BST::updateOrCreate(
                ['id' => $entry->id],
                [


                    'r10' => $r10,
                    'r11' => $r11,
                    'note' => $request->note,
                    'status' => 1,
                ]
            );
            return redirect('/bstentry')->with('success', 'Tanaman Berhasil dilakukan Entry');
        }
        // Tanaman Baru
        if ($request->submit3) {
            $request->validate(
                [
                    'r5' => 'required|max:6|gt:0',
                    'note' => 'required',
                ],
                [
                    'required' => ':attribute Wajib di Isi',
                    'max' => ':attribute Maksimal :max Karakter',
                    'gt' => ' :attribute tidak boleh :value.',
                ]
            );
            // entry3
            $selectedTW = Session::get('selectedTWBST');
            $selectedYear = Session::get('selectedYearBST');
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
            $data = EntryBST::updateOrCreate(
                [
                    'TW' =>  $TWNow,
                    'tahun' => $tahunNow,
                    'kec_id' => auth()->user()->kec_id,
                ]
            );
            $entry = BST::where('tanaman_id', $request->tanaman_id)->where('entry_id', $data->id)->first();
            if ($entry == null) {
                $id = $request->id;
            } else {
                $id = $entry->id;
            }
            BST::updateOrCreate(
                ['id' => $id],
                [
                    'r3' => 0,
                    'r4' => 0,
                    'r5' => $request->r5,
                    'r6' => $request->r6,
                    'r7' => $request->r7,
                    'r8' => 0,
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
            return redirect('/bstentry')->with('success', 'Tanaman Berhasil dilakukan Entry');
        }
        if ($request->Btntanaman_id) {

            $selectedTW = Session::get('selectedTWBST');
            $selectedYear = Session::get('selectedYearBST');
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

            $data = EntryBST::updateOrCreate(
                [
                    'TW' =>  $TWLalu,
                    'tahun' => $tahunLalu,
                    'kec_id' => auth()->user()->kec_id,
                    // 'user_id' => auth()->user()->id,
                ]
            );
            $entry = BST::where('tanaman_id', $request->Btntanaman_id)->where('entry_id', $data->id)->first();
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
                BST::create($data);
            } else {
                $entry->update(['status_tanaman' => 2]);
            }

            return redirect('/bstentry')->with('success', 'Berhasil Menambahkan Komoditas Baru, Silahkan Lakukan Entry Pada Komoditas Tersebut');
        }
        if ($request->sendKues) {
            //$bulan = sprintf("%02d", $request->bulan);


            $EntryBST = EntryBST::where('kec_id', auth()->user()->kec_id)->where('TW', $request->triwulan)->where('tahun', $request->tahun)->first();

            if ($EntryBST->status == 5) {
                // revisi dari BPS
                $EntryBST->update(['status' => 4]);
                BST::where('entry_id', $EntryBST->id)->update(['status' => 4]);
            } else {
                // revisi dinas
                $EntryBST->update(['status' => 2]);
                // $tanamanid_array = $EntryBST->pluck('tanaman_id')->toArray();
                // Gunakan whereIn untuk mencocokkan beberapa nilai tanaman_id
                BST::where('entry_id', $EntryBST->id)->update(['status' => 2]);
            }


            return redirect('/bstentry')->with('success', 'Kuesioner Berhasil di Kirim');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BST  $bST
     * @return \Illuminate\Http\Response
     */
    public function show(BST $entrybst)
    {
        $this->authorize('pcl');

        $id = last(explode('/', request()->url()));
        $entrybst = EntryBST::where('id', $id)->first();
        return view('bst.entry.show', [
            'title' => "Kecamatan " . $entrybst->kecamatan->kecamatan . " Triwulan " . $entrybst->TW . " Tahun " . $entrybst->tahun,
            'bulan' => $entrybst->bulan,
            'tahun' =>  $entrybst->tahun,
            'kecamatan' => $entrybst->kec_id,
            'entryNow' => EntryBST::where('kec_id', $entrybst->kec_id)->where('TW', $entrybst->TW)->where('tahun', $entrybst->tahun)->first(),
            'tanaman' => Tanaman::where('jenis_sph', 'BST')->orderBy('urut_kues', 'asc')->get(),
        ]);
    }
    public function reset(Request $request) //kondisi harus clean, jika tidak clean maka akan reset form
    {
        BST::where('tanaman_id', $request->tanaman_id)->where('entry_id', $request->entry_id)->delete();
        return redirect('/bstentry/pertLuas/' . $request->tanaman_id);
    }
    public function pertLuas(Tanaman $bstentry)
    {
        $this->authorize('pcl');
        // Mengambil TW dan tahun dari sesi
        $selectedTW = Session::get('selectedTWBST');
        $selectedYear = Session::get('selectedYearBST');


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
        $entryLast = EntryBST::where('kec_id', auth()->user()->kec_id)->where('TW', $TWLalu)->where('tahun', $tahunLalu)->first();
        $entryNow = EntryBST::where('kec_id', auth()->user()->kec_id)->where('TW', $TWNow)->where('tahun', $tahunNow)->first();
        if ($entryNow == null) {
            $id = '';
        } else {
            $id = $entryNow->id;
        }
        $bstNow = BST::where('tanaman_id', $bstentry->id)->where('entry_id', $id)->first();
        return view('bst.entry.show1', [
            'title' => 'Tanaman ' . $bstentry->nama_tanaman,
            'bstLast' => BST::where('tanaman_id', $bstentry->id)->where('entry_id', $entryLast->id)->first(),
            'bstNow' => $bstNow,
            'entryNow' => $entryNow,
            'tanaman' => Tanaman::where('id', $bstentry->id)->first(),
            'twNow' => $TWNow,

        ]);
    }
    public function pertProd(Tanaman $bstentry)
    {  // Mengambil bulan dan tahun dari sesi
        $this->authorize('pcl');

        // Mengambil TW dan tahun dari sesi
        $selectedTW = Session::get('selectedTWBST');
        $selectedYear = Session::get('selectedYearBST');


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

        $entryNow_id = EntryBST::where('kec_id', auth()->user()->kec_id)->where('TW', $TWNow)->where('tahun', $tahunNow)->first()->id;

        return view('bst.entry.show2', [
            'title' => 'Tanaman ' . $bstentry->nama_tanaman,
            'bstNow' => BST::where('tanaman_id', $bstentry->id)->where('entry_id', $entryNow_id)->first(),
            'tanaman' => Tanaman::where('id', $bstentry->id)->first(),
        ]);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BST  $bST
     * @return \Illuminate\Http\Response
     */


    public function edit(BST $bST)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BST  $bST
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BST $bST)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BST  $bST
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Tanaman $bstentry)
    {
        $entryLast = EntryBST::where('kec_id', auth()->user()->kec_id)->where('TW', $request->twLalu)->where('tahun', $request->tahunLalu)->first();
        $entryNow = EntryBST::where('kec_id', auth()->user()->kec_id)->where('TW', $request->twNow)->where('tahun', $request->tahunNow)->first();
        $bstlast = BST::where('tanaman_id', $bstentry->id)->where('entry_id', $entryLast->id)->first();

        if ($bstlast->r3 == 0) {
            //    Hapus Jika 0
            $bstlast->delete();
        } else {
            $bstlast->update(['status_tanaman' => 1]);
        }
        if ($entryNow != null) {
            BST::where('tanaman_id', $bstentry->id)->where('entry_id', $entryNow->id)->delete();
        }
        // Kontrak::destroy($kontrak->id);
        return redirect()->back()->with('success', 'Data Tanaman Berhasil di Hapus');
    }
}
