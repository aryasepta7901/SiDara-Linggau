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

        .sticky-col::after {
            content: '';
            position: absolute;
            background-color: inherit;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: -1;
        }
    </style>


    <div class="col-lg-12">

        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <a type="button" href="{{ url('/thvalidasi/dinas') }}" class="btn btn-default" data-dismiss="modal">Kembali</a>

            </div>
            <div class="card-body">
                <form method="post" action="{{ url('/thvalidasi/dinas') }}">
                    @csrf
                    @if ($entryNow->status == 2)
                        <button type="submit" class="btn btn-sm btn-info ml-auto"><i class="fas fa-paper-plane">
                            </i> Kirim</button>
                    @endif

                    <table id="example2" id="data-table" class="table table-bordered table-striped table-responsive">

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
                                    <td class="sticky-col">{{ $value->nama_tanaman }} @if ($value->id == '196201005')
                                            (*)
                                        @elseif($value->id == '196201001')
                                            (**)
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
                                                <input @if ($th->status == 4 || $th->status == 2) checked @endif
                                                    class="form-check-input" type="radio"
                                                    name="validasi{{ $th->tanaman_id }}" value="4">
                                                <label class="form-check-label">
                                                    Setuju
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input @if ($th->status == 3) checked @endif
                                                    class="form-check-input" type="radio"
                                                    name="validasi{{ $th->tanaman_id }}" value="3">
                                                <label class="form-check-label">
                                                    Revisi
                                                </label>
                                            </div>

                                        </td>
                                        {{-- <td>
                                            @if ($sbsLast->status == 3)
                                                {{ $sbsLast->catatan_dinas }}
                                            @endif

                                        </td> --}}
                                        <td class="text-center"><input type="text"
                                                name="catatanDinas{{ $th->tanaman_id }}"
                                                @if ($th->status == 3 || $th->status == 2) value="{{ $th->catatan_dinas }}" @endif>
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
                                                    name="validasi{{ $value->id }}" value="4" checked>
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
                                        <td><input type="text" name="catatanDinas{{ $value->id }}">
                                        </td>
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


                </form>
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
                Catatan  : * Anggrek Potong : tanaman anggrek yang bentuk hasil panen/ dijual berupa bunga potong (tangkai
                bunga)

                ** Anggrek Pot : tanaman anggrek yang bentuk hasil panen/ dijual berupa tanaman berbunga
                (dalam pot)
            </div>
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
