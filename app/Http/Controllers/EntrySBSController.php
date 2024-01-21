<?php

namespace App\Http\Controllers;

use App\Models\EntrySBS;
use App\Models\Kecamatan;
use App\Models\SBS;
use App\Models\Tanaman;
use Carbon\Carbon;
use Egulias\EmailValidator\Warning\TLD;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class EntrySBSController extends Controller
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
        $selectedMonth = Session::get('selectedMonth');
        $selectedYear = Session::get('selectedYear');

        if ($selectedMonth != null || $selectedYear != null) {
            // Membuat objek Carbon dari bulan dan tahun yang dipilih
            $bulanTahun = Carbon::createFromDate($selectedYear, $selectedMonth, 1);
            $bulanLalu = $selectedMonth == 1 ? 12 : $selectedMonth - 1;
            $tahunLalu = $selectedMonth == 1 ? $selectedYear - 1 : $selectedYear;

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
                // Menentukan bulan lalu
                $bulanLalu = date('m') - 2 < 1 ? 11 : (date('m') - 2 == 2 ? 12 : date('m') - 2);
                $tahunLalu = date('m') == 1 ? date('Y') - 1 : date('Y');
            } elseif ($tanggalNow >= 20) {
                // Mendapatkan tanggal saat ini
                $bulanTahun = Carbon::now();
                // Bulan sekarang
                $bulanNow = date('m');
                $tahunNow = date('Y');
                // Menentukan bulan lalu
                $bulanLalu =  date('m') == 1 ? 12 : date('m') - 1;
                $tahunLalu =  date('m') == 1 ? date('Y') - 1 : date('Y');
            }
        }
        $entryNow = EntrySBS::where('kec_id', auth()->user()->kec_id)->where('bulan', $bulanNow)->where('tahun', $tahunNow)->first();
        $entryLast = EntrySBS::where('kec_id', auth()->user()->kec_id)->where('bulan', $bulanLalu)->where('tahun', $tahunLalu)->where('status', 6)->first();
        if ($entryLast != null) {
            $data = SBS::where('entry_id', $entryLast->id)->whereNotIn('status_tanaman', [1])->pluck('tanaman_id')->toArray();
        } else {
            $data = [];
        }
        return view('sbs.entry.index', [
            'title' => 'Entry SBS Bulan ' . Carbon::parse($bulanTahun)->isoFormat('MMMM YYYY', 'id'),
            'kecamatan' => Kecamatan::where('id', auth()->user()->kec_id)->first(),
            'tanaman' => Tanaman::whereIn('id', $data)->get(),
            'allTanaman' => Tanaman::whereNotIn('id', $data)->get(),
            'entryLast' => $entryLast,
            'entryNow' => $entryNow,
            'bulanNow' => $bulanNow,
            'tahunNow' => $tahunNow,
            'bulanLalu' => $bulanLalu,
            'tahunLalu' => $tahunLalu,

        ]);
    }
    // Di dalam controller atau di mana pun yang sesuai
    public function storeMonthYearSelection(Request $request)
    {
        if ($request->resetButton != null) {
            Session::remove('selectedMonth');
            Session::remove('selectedYear');
        } else {
            // Menyimpan bulan dan tahun dalam sesi
            Session::put('selectedMonth', $request->bulan);
            Session::put('selectedYear', $request->tahun);
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
                    'r4' => 'required',
                    'r5'  => 'required|lte:r4',
                    'r6'  => 'required|lte:r4',
                    'r7'  => 'required|lte:r4',
                    'r8' => 'required',

                ],
                [
                    'required' => ':attribute Wajib di Isi',
                    'lte' => ' :attribute Harus lebih kecil atau sama dengan  dari :value.',
                ]
            );

            if ($request->r5 + $request->r6 + $request->r7 > $request->r4) {
                // Gunakan session untuk menyimpan pesan kesalahan
                $tanaman = Tanaman::where('id', $request->tanaman_id)->first();
                if ($tanaman->belum_habis == 0) {
                    Session::flash('error1', 'Terdapat Kesalahan: Total Nilai R5 dan R7 berjumlah ' . $request->r5 + $request->r6 + $request->r7);
                } else {
                    Session::flash('error1', 'Terdapat Kesalahan: Total Nilai R5 R6 dan R7 berjumlah ' . $request->r5 + $request->r6 + $request->r7);
                }

                // Redirect kembali
                return back()->withInput()->withErrors('');
            } else {
                $selectedMonth = Session::get('selectedMonth');
                $selectedYear = Session::get('selectedYear');
                if ($selectedMonth != null || $selectedYear != null) {
                    $bulanNow =  $selectedMonth;
                    $tahunNow =  $selectedYear;
                } else {
                    $tanggalNow = date('d');
                    if ($tanggalNow <= 19) {
                        $bulanNow = date('m') == 1 ? 12 : date('m') - 1;
                        $tahunNow = date('m') == 1 ? date('Y') - 1 : date('Y');
                    } elseif ($tanggalNow >= 20) {
                        $bulanNow = date('m');
                        $tahunNow = date('Y');
                    }
                }
                $data = EntrySBS::updateOrCreate(
                    [
                        'bulan' =>  $bulanNow,
                        'tahun' => $tahunNow,
                        'kec_id' => $request->kecamatan_id,
                    ]
                );
                $entry = SBS::where('tanaman_id', $request->tanaman_id)->where('entry_id', $data->id)->first();
                if ($entry == null) {
                    $id = $request->id;
                } else {
                    $id = $entry->id;
                }
                $total = $request->r4 - $request->r5 - $request->r7 + $request->r8;
                if ($total == 0) {
                    $status_tanaman = 1;
                } else {
                    $status_tanaman = 0;
                }
                SBS::updateOrCreate(
                    ['id' => $id],
                    [
                        'r4' => $request->r4,
                        'r5' => $request->r5,
                        'r6' => $request->r6,
                        'r7' => $request->r7,
                        'r8' => $request->r8,
                        'r9' => $total,
                        'status' => 0,
                        'status_tanaman' => $status_tanaman,
                        'entry_id' => $data->id,
                        'tanaman_id' => $request->tanaman_id,
                        'user_id' => $request->user_id,
                    ]
                );
                return redirect('/entry/pertProd/' . $request->tanaman_id);
            }
        }
        if ($request->submit2) {
            // validasi
            $tanaman = Tanaman::where('id', $request->tanaman_id)->first();
            $min_produktivitas = $tanaman->min_produktivitas;
            $r5 = $request->r5;
            $r6 = $request->r6;
            $min_r10 = $min_produktivitas * $r5;
            $min_r11 = $min_produktivitas * $r6;
            $max_produktivitas = $tanaman->max_produktivitas;
            $max_r10 = $max_produktivitas * $r5;
            $max_r11 = $max_produktivitas * $r6;
            $request->validate(
                [
                    'r10' => 'gte:' . $min_r10 . '|lte:' . $max_r10,
                    'r11' => 'gte:' . $min_r11 . '|lte:' . $max_r11,
                    'r12' => 'gte:' . $request->min_harga . '|lte:' . $request->max_harga,
                ],
                [
                    'required' => ':attribute Wajib di Isi',
                    'lte' => ' :attribute Harus lebih kecil atau sama dengan  dari :value.',
                    'gte' => ' :attribute Harus lebih besar atau sama dengan  dari :value.'
                ]
            );
            // entry2
            $selectedMonth = Session::get('selectedMonth');
            $selectedYear = Session::get('selectedYear');
            if ($selectedMonth != null || $selectedYear != null) {
                $bulanNow =  $selectedMonth;
                $tahunNow =  $selectedYear;
            } else {
                $tanggalNow = date('d');
                if ($tanggalNow <= 19) {
                    $bulanNow = date('m') == 1 ? 12 : date('m') - 1;
                    $tahunNow = date('m') == 1 ? date('Y') - 1 : date('Y');
                } elseif ($tanggalNow >= 20) {
                    $bulanNow = date('m');
                    $tahunNow = date('Y');
                }
            }
            $data = EntrySBS::updateOrCreate(
                [
                    'bulan' =>  $bulanNow,
                    'tahun' => $tahunNow,
                    'kec_id' => auth()->user()->kec_id,
                ]
            );
            $entry = SBS::where('tanaman_id', $request->tanaman_id)->where('entry_id', $data->id)->first();
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
            if ($request->r10 == null && $request->r11 == null) {
                $r12 = 0;
            } else {
                $r12 = $request->r12;
            }
            SBS::updateOrCreate(
                ['id' => $entry->id],
                [

                    'r10' => $r10,
                    'r11' => $r11,
                    'r12' => $r12,
                    'note' => $request->note,
                    'status' => 1,
                ]
            );
            return redirect('/entry')->with('success', 'Tanaman Berhasil dilakukan Entry');
        }

        // Tanaman Baru
        if ($request->submit3) {
            $request->validate(
                [
                    'r8' => 'required',
                    'note' => 'required',
                ],
                [
                    'required' => ':attribute Wajib di Isi',
                ]
            );
            // entry3
            $selectedMonth = Session::get('selectedMonth');
            $selectedYear = Session::get('selectedYear');
            if ($selectedMonth != null || $selectedYear != null) {
                $bulanNow =  $selectedMonth;
                $tahunNow =  $selectedYear;
            } else {
                $tanggalNow = date('d');
                if ($tanggalNow <= 19) {
                    $bulanNow = date('m') == 1 ? 12 : date('m') - 1;
                    $tahunNow = date('m') == 1 ? date('Y') - 1 : date('Y');
                } elseif ($tanggalNow >= 20) {
                    $bulanNow = date('m');
                    $tahunNow = date('Y');
                }
            }
            $data = EntrySBS::updateOrCreate(
                [
                    'bulan' =>  $bulanNow,
                    'tahun' => $tahunNow,
                    'kec_id' => auth()->user()->kec_id,
                ]
            );
            $entry = SBS::where('tanaman_id', $request->tanaman_id)->where('entry_id', $data->id)->first();
            if ($entry == null) {
                $id = $request->id;
            } else {
                $id = $entry->id;
            }
            SBS::updateOrCreate(
                ['id' => $id],
                [
                    'r4' => 0,
                    'r5' => 0,
                    'r6' => 0,
                    'r7' => 0,
                    'r8' => $request->r8,
                    'r9' => $request->r8,
                    'r10' => 0,
                    'r11' => 0,
                    'r12' => 0,
                    'tanaman_id' => $request->tanaman_id,
                    'status' => 1,
                    'user_id' => auth()->user()->id,
                    'entry_id' => $data->id,
                    'note' => $request->note,
                ]
            );
            return redirect('/entry')->with('success', 'Tanaman Berhasil dilakukan Entry');
        }

        if ($request->Btntanaman_id) {

            $selectedMonth = Session::get('selectedMonth');
            $selectedYear = Session::get('selectedYear');
            if ($selectedMonth != null || $selectedYear != null) {

                $bulanLalu =  $selectedMonth == 1 ? 12 : $selectedMonth - 1;
                $tahunLalu =  $selectedMonth == 1 ? $selectedYear - 1 : $selectedYear;
            } else {
                // Menentukan bulan lalu
                $bulanLalu = date('m') - 2 < 1 ? 11 : (date('m') - 2 == 2 ? 12 : date('m') - 2);
                $tahunLalu = date('m') == 1 ? date('Y') - 1 : date('Y');
                $tanggalNow = date('d');
                if ($tanggalNow <= 19) {
                    $bulanLalu = date('m') - 2 < 1 ? 11 : (date('m') - 2 == 2 ? 12 : date('m') - 2);
                    $tahunLalu = date('m') == 1 ? date('Y') - 1 : date('Y');
                } elseif ($tanggalNow >= 20) {
                    $bulanLalu = date('m') == 1 ? 12 : date('m') - 1;
                    $tahunLalu = date('m') == 1 ? date('Y') - 1 : date('Y');
                }
            }
            $data = EntrySBS::updateOrCreate(
                [
                    'bulan' =>  $bulanLalu,
                    'tahun' => $tahunLalu,
                    'kec_id' => auth()->user()->kec_id,
                    // 'user_id' => auth()->user()->id,
                ]
            );
            $entry = SBS::where('tanaman_id', $request->Btntanaman_id)->where('entry_id', $data->id)->first();
            if ($entry == null) {
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
                    'entry_id' => $data->id,
                    'tanaman_id' => $request->Btntanaman_id,
                    'user_id' => auth()->user()->id,
                    'status' => 4,
                    'status_tanaman' => 2 // tanaman_baru
                ];
                SBS::create($data);
            } else {
                $entry->update(['status_tanaman' => 2]);
            }

            return redirect('/entry')->with('success', 'Berhasil Menambahkan Komoditas Baru, Silahkan Lakukan Entry Pada Komoditas Tersebut');
        }
        if ($request->sendKues) {
            $EntrySBS = EntrySBS::where('kec_id', auth()->user()->kec_id)->where('bulan', $request->bulan)->where('tahun', $request->tahun)->first();

            if ($EntrySBS->status == 5) {
                // revisi dari BPS
                $EntrySBS->update(['status' => 4]);
                SBS::where('entry_id', $EntrySBS->id)->update(['status' => 4]);
            } else {
                // revisi dinas
                $EntrySBS->update(['status' => 2]);
                // $tanamanid_array = $EntrySBS->pluck('tanaman_id')->toArray();
                // Gunakan whereIn untuk mencocokkan beberapa nilai tanaman_id
                SBS::where('entry_id', $EntrySBS->id)->update(['status' => 2]);
            }


            return redirect('/entry')->with('success', 'Kuesioner Berhasil di Kirim');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tanaman  $EntrySBS
     * @return \Illuminate\Http\Response
     */
    // public function show(Tanaman $entry)
    // {
    //     return view('entry.sbs.show', [
    //         'title' => 'Tanaman ' . $entry->nama_tanaman,
    //         'entry' => EntrySBS::where('tanaman_id', $entry->id)->where('kec_id', auth()->user()->kec_id)->where('bulan', 10)->first(),
    //         'tanaman' => Tanaman::where('id', $entry->id)->first(),

    //     ]);
    // }
    public function pertLuas(Tanaman $entry)
    {
        $this->authorize('pcl');

        // Mengambil bulan dan tahun dari sesi
        $selectedMonth = Session::get('selectedMonth');
        $selectedYear = Session::get('selectedYear');

        if ($selectedMonth != null || $selectedYear != null) {
            $bulanNow = $selectedMonth;
            $tahunNow = $selectedYear;
            $bulanLalu = $selectedMonth == 1 ? 12 : $selectedMonth - 1;
            $tahunLalu = $selectedMonth == 1 ? $selectedYear - 1 : $selectedYear;
        } else {
            $tanggalNow = date('d');
            if ($tanggalNow <= 19) {
                // Bulan sekarang
                $bulanNow = date('m') == 1 ? 12 : date('m') - 1;
                $tahunNow = date('m') == 1 ? date('Y') - 1 : date('Y');
                // Menentukan bulan lalu
                $bulanLalu = date('m') - 2 < 1 ? 11 : (date('m') - 2 == 2 ? 12 : date('m') - 2);
                $tahunLalu = date('m') == 1 ? date('Y') - 1 : date('Y');
            } elseif ($tanggalNow >= 20) {
                // Bulan sekarang
                $bulanNow = date('m');
                $tahunNow = date('Y');
                // Menentukan bulan lalu
                $bulanLalu =  date('m') == 1 ? 12 : date('m') - 1;
                $tahunLalu =  date('m') == 1 ? date('Y') - 1 : date('Y');
            }
        }
        $entryLast = EntrySBS::where('kec_id', auth()->user()->kec_id)->where('bulan', $bulanLalu)->where('tahun', $tahunLalu)->first();
        $entryNow = EntrySBS::where('kec_id', auth()->user()->kec_id)->where('bulan', $bulanNow)->where('tahun', $tahunNow)->first();
        if ($entryNow == null) {
            $id = '';
        } else {
            $id = $entryNow->id;
        }

        return view('sbs.entry.show1', [
            'title' => 'Tanaman ' . $entry->nama_tanaman,
            'sbsLast' => SBS::where('tanaman_id', $entry->id)->where('entry_id', $entryLast->id)->first(),
            'sbsNow' => SBS::where('tanaman_id', $entry->id)->where('entry_id', $id)->first(),
            'entryNow' => $entryNow,
            'tanaman' => Tanaman::where('id', $entry->id)->first(),

        ]);
    }
    public function pertProd(Tanaman $entry)
    {  // Mengambil bulan dan tahun dari sesi
        $this->authorize('pcl');

        $selectedMonth = Session::get('selectedMonth');
        $selectedYear = Session::get('selectedYear');
        if ($selectedMonth != null || $selectedYear != null) {
            $bulanNow =  $selectedMonth;
            $tahunNow =  $selectedYear;
        } else {
            $tanggalNow = date('d');
            if ($tanggalNow <= 19) {
                // Bulan sekarang
                $bulanNow = date('m') == 1 ? 12 : date('m') - 1;
                $tahunNow = date('m') == 1 ? date('Y') - 1 : date('Y');
            } elseif ($tanggalNow >= 20) {
                // Bulan sekarang
                $bulanNow = date('m');
                $tahunNow = date('Y');
            }
        }
        $entryNow_id = EntrySBS::where('kec_id', auth()->user()->kec_id)->where('bulan', $bulanNow)->where('tahun', $tahunNow)->first()->id;

        return view('sbs.entry.show2', [
            'title' => 'Tanaman ' . $entry->nama_tanaman,
            'sbsNow' => SBS::where('tanaman_id', $entry->id)->where('entry_id', $entryNow_id)->first(),
            'tanaman' => Tanaman::where('id', $entry->id)->first(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\EntrySBS  $EntrySBS
     * @return \Illuminate\Http\Response
     */
    public function edit(EntrySBS $EntrySBS)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\EntrySBS  $EntrySBS
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EntrySBS $EntrySBS)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tanaman  $EntrySBS
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Tanaman $entry)
    {
        $entryLast = EntrySBS::where('kec_id', auth()->user()->kec_id)->where('bulan', $request->bulanLalu)->where('tahun', $request->tahunLalu)->first();
        $entryNow = EntrySBS::where('kec_id', auth()->user()->kec_id)->where('bulan', $request->bulanNow)->where('tahun', $request->tahunNow)->first();

        $sbsLast = SBS::where('tanaman_id', $entry->id)->where('entry_id', $entryLast->id)->first();
        if ($sbsLast->r4 == 0) {
            //    Hapus Jika 0
            $sbsLast->delete();
        } else {
            $sbsLast->update(['status_tanaman' => 1]);
        }
        if ($entryNow != null) {
            SBS::where('tanaman_id', $entry->id)->where('entry_id', $entryNow->id)->delete();
        }
        // Kontrak::destroy($kontrak->id);
        return redirect()->back()->with('success', 'Data Tanaman Berhasil di Hapus');
    }
}
