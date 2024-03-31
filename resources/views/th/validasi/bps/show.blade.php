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
                <a type="button" href="{{ url('/thvalidasi/bps') }}" class="btn btn-default" data-dismiss="modal">Kembali</a>

                @if ($entryNow->status == 6)
                    <button class="btn btn-primary ml-auto" data-toggle="modal" data-target="#tambah"><i class="fa fa-plus">
                        </i> </button>
                @endif

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
                <form method="post" action="{{ url('/thvalidasi/bps') }}">
                    @csrf
                    @if ($entryNow->status == 4 || $entryNow->status == 6)
                        <button type="submit" name="kirimValidasi" value="kirimValidasi"
                            class="btn btn-sm btn-info ml-auto"><i class="fas fa-paper-plane">
                            </i> Kirim</button>
                    @endif
                    <div class="custom-scroll" id="scrollTable">
                        <table id="example2" id="data-table" class="table  table-bordered table-striped table-responsive">
                            <thead>
                                <tr>
                                    <th class="sticky-col" rowspan="2">No</th>
                                    <th style="width: 350px;" class="sticky-col" rowspan="2">Nama Tanaman</th>
                                    <th rowspan="2">Hasil Produksi yang di catat</th>
                                    <th rowspan="2">Luas Tanaman Akhir Triwulan yang lalu(m2)</th>
                                    <th colspan="2">Luas Panen (m2)</th>
                                    <th rowspan="2">Luas Rusak/Tidak Berhasil/Puso (m2)</th>
                                    <th rowspan="2">Luas Penanaman Baru/Tambah Tanam (m2)</th>
                                    <th rowspan="2">Luas Tanaman Akhir Triwulan Laporan (m2)
                                        (3)-(4)-(6)+(7)</th>
                                    <th colspan="2">Produksi (Kilogram)</th>
                                    <th rowspan="2">Rata rata Harga Jual di petani per Kilogram (Rupiah)</th>
                                    <th rowspan="2">Keterangan</th>
                                    <th rowspan="2">Validasi</th>
                                    <th rowspan="2">Komentar</th>
                                    <th rowspan="2">Action</th>


                                </tr>
                                <tr>
                                    <th>Habis/Dibongkar</th>
                                    <th>Belum Habis</th>
                                    <th>Dipanen Habis/Dibongkar</th>
                                    <th>Belum Habis</th>
                                </tr>
                                <tr>
                                    <td class="text-center">(1)</td>
                                    <td colspan="2" class="text-center">(2)</td>
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
                                    <td class="text-center"></td>
                                    <td class="text-center"></td>
                                </tr>

                            </thead>
                            <tbody>
                                @foreach ($tanaman as $value)
                                    @php
                                        $th = App\Models\TH::where('tanaman_id', $value->id)
                                            ->where('entry_id', $entryNow->id)
                                            ->first();

                                    @endphp
                                    <tr>
                                        <td class="sticky-col">{{ $loop->iteration }}</td>
                                        <td class="sticky-col">{{ $value->nama_tanaman }} @if ($value->satuan_luas == 'Pohon')
                                                (*)
                                            @endif
                                        </td>
                                        <td>{{ $value->bentuk_produksi }}</td>
                                        @if ($th !== null)
                                            <td class="text-center">{{ $th->r3 }}</td>
                                            <td class="text-center">{{ $th->r4 }}</td>
                                            <td class="text-center">{{ $th->r5 }}</td>
                                            <td class="text-center">{{ $th->r6 }}</td>
                                            <td class="text-center">{{ $th->r7 }}</td>
                                            <td class="text-center">{{ $th->r8 }}</td>
                                            <td class="text-center">{{ $th->r9 }}</td>
                                            <td class="text-center">{{ $th->r10 }}</td>
                                            <td class="text-center">{{ $th->r11 }}</td>
                                            <td class="text-center">{{ $th->note }}</td>
                                            <td class="text-center">
                                                <div class="form-check">
                                                    <input @if ($th->status == 4 || $th->status == 2 || $th->status == 6) checked @endif
                                                        class="form-check-input" type="radio"
                                                        name="validasi{{ $th->tanaman_id }}" value="6">
                                                    <label class="form-check-label">
                                                        Setuju
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input @if ($th->status == 3) checked @endif
                                                        class="form-check-input" type="radio"
                                                        name="validasi{{ $th->tanaman_id }}" value="5">
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
                                                    name="catatanBPS{{ $th->tanaman_id }}"
                                                    @if ($th->status == 3 || $th->status == 2 || $th->status == 4 || $th->status == 5) value="{{ $th->catatan_BPS }}" @endif>
                                            </td>
                                            <input type="hidden" name="tanaman_id[]" value="{{ $value->id }}">

                                            <td>
                                                @if ($th->r3 == 0)
                                                    <button type="button" class="badge badge-danger badge-sm"
                                                        data-toggle="modal" data-target="#delete{{ $th->tanaman_id }}"><i
                                                            class="fas fa-trash"></i></button>
                                                @endif
                                            </td>
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
                                            <td></td>
                                        @endif
                                        <!-- Menyertakan input tanaman_ids -->
                                        <input type="hidden" name="tanaman_id[]" value="{{ $value->id }}">
                                        <input type="hidden" name="kec_id" value="{{ $kecamatan }}">
                                        <input type="hidden" name="TW" value="{{ $TW }}">
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
            <div class="card-footer">
                Catatan: (*) Untuk luasan diisi dalam satuan Pohon
            </div>
        </div>
        <!-- /.card -->

    </div>

    {{-- Modal --}}
    {{-- Tambah --}}
    <div class="modal fade" id="tambah">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Tambah Tanaman</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post"action="{{ url('/thvalidasi/bps') }}">
                    @csrf
                    <div class="modal-body">
                        <table id="example1" class="table table-bordered table-striped ">
                            <thead>
                                <tr>
                                    <th>Nama Tanaman</th>
                                    <th>Tanaman Baru (r7)</th>
                                    <th>Tambah</th>
                                </tr>

                            </thead>
                            <tbody>

                                @foreach ($allTanaman as $value)
                                    <tr>
                                        <td>{{ $value->nama_tanaman }}</td>
                                        </td>
                                        <input type="hidden" name="entry_id" value="{{ $entryNow->id }}">
                                        <td><input type="number" name="r7" step=".01" min="0">
                                        </td>
                                        <td class="text-center">
                                            {{-- <a href="" class="btn btn-sm btn-primary"><i class="fas fa-eye"></i></a> --}}
                                            <button type="submit" name="Btntanaman_id" value="{{ $value->id }}"
                                                class="btn btn-sm btn-info ml-auto"><i class="fa fa-plus">
                                                </i></button>
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    {{-- Delete --}}
    @foreach ($tanaman as $value)
        <div class="modal fade" id="delete{{ $value->id }}">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Hapus Data Tanaman</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="post" action="{{ url('/thvalidasi/bps/' . $value->id) }}">
                        @method('delete')
                        @csrf
                        <div class="modal-body">
                            {{-- <input type="hidden" value="{{ $bulanLalu }}" name="bulanLalu">
                            <input type="hidden" value="{{ $tahunLalu }}" name="tahunLalu">
                            <input type="hidden" value="{{ $bulanNow }}" name="bulanNow">
                            <input type="hidden" value="{{ $tahunNow }}" name="tahunNow"> --}}

                            <input type="hidden" name="entry_id" value="{{ $entryNow->id }}">
                            <input type="hidden" name="tanaman_id" value="{{ $value->id }}">
                            Apakah Anda Yakin ingin menghapus data Tanaman <b> {{ $value->nama_tanaman }}</b>
                        </div>

                        <div class="modal-footer justify-content-between">
                            <a type="button" class="btn btn-default" data-dismiss="modal">Close</a>
                            <button type="submit" name="submit3" value="submit3" class="btn btn-primary">Hapus</button>
                        </div>
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
    @endforeach



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
