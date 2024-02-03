@extends('layouts.backEnd.main')

@section('content')
    <style>
        .table-container {
            overflow-x: auto;
            position: relative;
        }

        .sticky-col {
            position: sticky;
            left: 0;
            background-color: #f2f2f2;
            z-index: 2;
        }

        .custom-scroll {
            max-height: 1000px;
            /* Atur ketinggian maksimal */
            overflow: auto;
        }
    </style>


    <div class="col-lg-12">

        <div class="card">
            <div class="card-header d-flex justify-content-between">
                {{-- <table class="table table-head-fixed text-nowrap">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Reason</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>183</td>
                            <td>John Doe</td>
                            <td>11-7-2014</td>
                            <td><span class="tag tag-success">Approved</span></td>
                            <td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>
                        </tr>
                        <tr>
                            <td>219</td>
                            <td>Alexander Pierce</td>
                            <td>11-7-2014</td>
                            <td><span class="tag tag-warning">Pending</span></td>
                            <td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>
                        </tr>
                        <tr>
                            <td>657</td>
                            <td>Bob Doe</td>
                            <td>11-7-2014</td>
                            <td><span class="tag tag-primary">Approved</span></td>
                            <td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>
                        </tr>
                        <tr>
                            <td>175</td>
                            <td>Mike Doe</td>
                            <td>11-7-2014</td>
                            <td><span class="tag tag-danger">Denied</span></td>
                            <td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>
                        </tr>
                        <tr>
                            <td>134</td>
                            <td>Jim Doe</td>
                            <td>11-7-2014</td>
                            <td><span class="tag tag-success">Approved</span></td>
                            <td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>
                        </tr>
                        <tr>
                            <td>494</td>
                            <td>Victoria Doe</td>
                            <td>11-7-2014</td>
                            <td><span class="tag tag-warning">Pending</span></td>
                            <td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>
                        </tr>
                        <tr>
                            <td>832</td>
                            <td>Michael Doe</td>
                            <td>11-7-2014</td>
                            <td><span class="tag tag-primary">Approved</span></td>
                            <td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>
                        </tr>
                        <tr>
                            <td>982</td>
                            <td>Rocky Doe</td>
                            <td>11-7-2014</td>
                            <td><span class="tag tag-danger">Denied</span></td>
                            <td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>
                        </tr>
                    </tbody>
                </table> --}}
            </div>
            <div class="card-body ">
                <form method="post" action="{{ url('/validasi/bps') }}">
                    @csrf
                    {{-- @if ($entryNow->status == 4) --}}
                    <button type="submit" class="btn btn-sm btn-info ml-auto"><i class="fas fa-paper-plane">
                        </i> Kirim</button>
                    {{-- @endif --}}
                    <div class="custom-scroll" id="scrollTable">
                        <table id="example2" id="data-table" class="table  table-bordered table-striped table-responsive">
                            <thead class="sticky-header">
                                <tr>
                                    <th class="sticky-col" rowspan="2">No</th>
                                    <th style="width: 350px;" class="sticky-col" rowspan="2">Nama Tanaman</th>
                                    <th rowspan="2">Hasil Produksi yang di catat</th>
                                    <th rowspan="2">Luas Tanaman Akhir Bulan yang lalu(Hektar)</th>
                                    <th colspan="2">Luas Panen (Hektar)</th>
                                    <th rowspan="2">Luas Rusak/Tidak Berhasil/Puso (Hektar)</th>
                                    <th rowspan="2">Luas Penanaman Baru/Tambah Tanam (Hektar)</th>
                                    <th rowspan="2">Luas Tanaman Akhir Bulan Laporan (Hektar) (4)-(5)-(7)+8</th>
                                    <th colspan="2">Produksi (Kuintal)</th>
                                    <th rowspan="2">Rata rata Harga Jual di petani per Kilogram (Rupiah)</th>
                                    <th rowspan="2">Keterangan</th>
                                    <th rowspan="2">Validasi</th>
                                    <th rowspan="2">Komentar</th>

                                </tr>
                                <tr>
                                    <th>Habis/Dibongkar</th>
                                    <th>Belum Habis</th>
                                    <th>Dipanen Habis/Dibongkar</th>
                                    <th>Belum Habis</th>
                                </tr>
                                <tr>
                                    <td class="text-center">(1)</td>
                                    <td class="text-center">(2)</td>
                                    <td class="text-center">(3)</td>
                                    <td class="text-center">(4)</td>
                                    <td class="text-center">(5)</td>
                                    <td class="text-center">(6)</td>
                                    <td class="text-center">(7)</td>
                                    <td class="text-center">(8)</td>
                                    <td class="text-center">(9)</td>
                                    <td class="text-center">(10)</td>
                                    <td class="text-center">(11)</td>
                                    <td class="text-center">(12)</td>
                                    <td class="text-center">(13)</td>
                                    <td class="text-center"></td>
                                    <td class="text-center"></td>
                                </tr>

                            </thead>
                            <tbody>

                                @foreach ($tanaman as $value)
                                    @php
                                        $sbs = App\Models\SBS::where('tanaman_id', $value->id)
                                            ->where('entry_id', $entryNow->id)
                                            ->first();

                                    @endphp
                                    <tr>
                                        <td class="sticky-col">{{ $loop->iteration }}</td>
                                        <td class="sticky-col">{{ $value->nama_tanaman }}</td>
                                        <td>{{ $value->bentuk_produksi }}</td>
                                        @if ($sbs !== null)
                                            <td class="text-center">{{ $sbs->r4 }}</td>
                                            <td class="text-center">{{ $sbs->r5 }}</td>
                                            <td class="text-center">{{ $sbs->r6 }}</td>
                                            <td class="text-center">{{ $sbs->r7 }}</td>
                                            <td class="text-center">{{ $sbs->r8 }}</td>
                                            <td class="text-center">{{ $sbs->r9 }}</td>
                                            <td class="text-center">{{ $sbs->r10 }}</td>
                                            <td class="text-center">{{ $sbs->r11 }}</td>
                                            <td class="text-center">{{ $sbs->r12 }}</td>
                                            <td class="text-center">{{ $sbs->note }}</td>
                                            <td class="text-center">
                                                <div class="form-check">
                                                    <input @if ($sbs->status == 4 || $sbs->status == 2 || $sbs->status == 6) checked @endif
                                                        class="form-check-input" type="radio"
                                                        name="validasi{{ $sbs->tanaman_id }}" value="6">
                                                    <label class="form-check-label">
                                                        Setuju
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input @if ($sbs->status == 3) checked @endif
                                                        class="form-check-input" type="radio"
                                                        name="validasi{{ $sbs->tanaman_id }}" value="5">
                                                    <label class="form-check-label">
                                                        Revisi
                                                    </label>
                                                </div>

                                            </td>
                                            {{-- <td>
                                            @if ($sbsLast->status == 3)
                                                {{ $sbsLast->catatan_BPS }}
                                            @endif

                                        </td> --}}
                                            <td class="text-center"><input type="text"
                                                    name="catatanBPS{{ $sbs->tanaman_id }}"
                                                    @if ($sbs->status == 3 || $sbs->status == 2 || $sbs->status == 4 || $sbs->status == 5) value="{{ $sbs->catatan_BPS }}" @endif>
                                            </td>
                                            <input type="hidden" name="tanaman_id[]" value="{{ $value->id }}">
                                        @else
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio"
                                                        name="validasi{{ $value->id }}" value="6" checked>
                                                    <label class="form-check-label">
                                                        Setuju
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio"
                                                        name="validasi{{ $value->id }}" value="2">
                                                    <label class="form-check-label">
                                                        Tanaman Baru
                                                    </label>
                                                </div>
                                            </td>
                                            <td><input type="text" name="catatanBPS{{ $value->id }}">
                                            </td>
                                        @endif
                                        <!-- Menyertakan input tanaman_ids -->
                                        <input type="hidden" name="tanaman_id[]" value="{{ $value->id }}">
                                        <input type="hidden" name="kec_id" value="{{ $kecamatan }}">
                                        <input type="hidden" name="bulan" value="{{ $bulan }}">
                                        <input type="hidden" name="tahun" value="{{ $tahun }}">

                                    </tr>
                                @endforeach

                                <!-- Tambahkan baris-baris data lainnya sesuai kebutuhan -->
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->

    </div>



    <script script src="{{ asset('template') }}/plugins/sweetalert2/sweetalert2.min.js"></script>

    <script>
        var Toast = Swal.mixin({
            // toast: true,
            // position: 'top-end',
            // showConfirmButton: false,
            timer: 10000, // waktu dalam milidetik
            timerProgressBar: true,
        });
    </script>

    @if (Session::has('success'))
        <script>
            Toast.fire({
                icon: 'success',
                title: "{{ Session::get('success') }}"
            })
        </script>
    @endif
@endsection
