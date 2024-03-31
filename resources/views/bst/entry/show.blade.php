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
                <a type="button" href="{{ url('/tbfentry') }}" class="btn btn-default" data-dismiss="modal">Kembali</a>
            </div>
            <div class="card-body">

                <div class="custom-scroll" id="scrollTable">
                    <table id="example2" id="data-table" class="table  table-bordered table-striped table-responsive">
                        <thead class="sticky-header">
                            <tr>
                                <th class="sticky-col" rowspan="2">No</th>
                                <th style="width: 350px;" class="sticky-col" rowspan="2">Nama Tanaman</th>
                                <th rowspan="2">Hasil Produksi yang di catat</th>
                                <th rowspan="2">Jumlah Tanaman Akhir Triwulan yang Lalu (Pohon/Rumpun)</th>
                                <th class="text-center" colspan="2">Selama Triwulan</th>
                                <th rowspan="2">Jumlah Tanaman Akhir Triwulan Laporan (3)-(4)+(5)</th>
                                <th class="text-center" colspan="3">Di Akhir Triwulan</th>
                                <th rowspan="2">Produksi (Kuintal)</th>
                                <th rowspan="2">Rata rata Harga Jual di petani per Kilogram (Rupiah)</th>
                                <th rowspan="2">Keterangan</th>
                            </tr>
                            <tr>
                                <th>Tanaman yang Dibongkar/Ditebang (Pohon/Rumpun)</th>
                                <th>Tanaman Baru/Penanaman Baru (Pohon/Rumpun)</th>
                                <th>Tanaman Belum Menghasilkan (Pohon/Rumpun)</th>
                                <th>Tanaman Produksi yang Sedang Menghasilkan (Pohon/Rumpun)</th>
                                <th>Tanaman Tua/Rusak (Pohon/Rumpun)</th>
                            </tr>
                            {{-- <tr>
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
                            </tr> --}}

                        </thead>
                        <tbody>

                            @foreach ($tanaman as $value)
                                @php
                                    $bst = App\Models\BST::where('tanaman_id', $value->id)
                                        ->where('entry_id', $entryNow->id)
                                        ->first();

                                @endphp
                                <tr>
                                    <td class="sticky-col">{{ $loop->iteration }}</td>
                                    <td class="sticky-col">{{ $value->nama_tanaman }} @if ($value->satuan_luas == 'Rumpun')
                                            (*)
                                        @endif
                                    </td>
                                    <td>{{ $value->bentuk_produksi }}</td>
                                    @if ($bst !== null)
                                        <td class="text-center">{{ $bst->r3 }}</td>
                                        <td class="text-center">{{ $bst->r4 }}</td>
                                        <td class="text-center">{{ $bst->r5 }}</td>
                                        <td class="text-center">{{ $bst->r6 }}</td>
                                        <td class="text-center">{{ $bst->r7 }}</td>
                                        <td class="text-center">{{ $bst->r8 }}</td>
                                        <td class="text-center">{{ $bst->r9 }}</td>
                                        <td class="text-center">{{ $bst->r10 }}</td>
                                        <td class="text-center">{{ $bst->r11 }}</td>
                                        <td class="text-center">{{ $bst->note }}</td>
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
                                    @endif
                                </tr>
                            @endforeach

                            <!-- Tambahkan baris-baris data lainnya sesuai kebutuhan -->
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
                Catatan : *) Jumlah Tanaman diisi dalam satuan Rumpun. Untuk buah naga 1 tiang = 1 rumpun
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
