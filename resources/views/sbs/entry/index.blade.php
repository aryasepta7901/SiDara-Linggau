@extends('layouts.backEnd.main')

@section('content')
    <div class="col-lg-12">
        <div class="card">
            <form method="post" action="/entry/storeMonthYearSelection">
                <div class="card-body">
                    @csrf
                    <?php
                    // Daftar nama bulan dalam Bahasa Indonesia
                    $bulanIndonesia = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                    ?>
                    {{-- @php
                        $entryNowStatus1 = App\Models\EntrySBS::where('kec_id', auth()->user()->kec_id)
                            ->where('bulan', $bulanNow)
                            ->where('tahun', $tahunNow)
                            ->whereIn('status_entry', [1, 2, 4])
                            ->get(); //entry Bulan Ini yang belum disubmit dan sudah disubmit serta yang di acc dinas
                        $entryNowStatus2 = App\Models\EntrySBS::where('kec_id', auth()->user()->kec_id)
                            ->where('bulan', $bulanNow)
                            ->where('tahun', $tahunNow)
                            ->wherein('status_entry', [2, 4])
                            ->get(); //yang sudah disubmit
                        $totalEntryNow1 = $entryNowStatus1->count(); //belum submit
                        $totalEntryNow2 = $entryNowStatus2->count(); //sudah submit
                        $totalEntryLast = $tanaman->count(); //entry Bulan Lalu
                    @endphp --}}
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="tahun">Tahun:</label>
                                <select class="form-control select2bs4" name="tahun" id="tahun">
                                    <?php
                                    // Menampilkan opsi tahun
                                    $startYear = $tahunNow - 1; // Misalnya, menampilkan 10 tahun ke belakang
                                    $endYear = $tahunNow + 1; // Dan 10 tahun ke depan
                                    
                                    for ($year = $startYear; $year <= $endYear; $year++) {
                                        $selected = $year == $tahunNow ? 'selected' : '';
                                        echo "<option value='$year' $selected>$year</option>";
                                    }
                                    ?>
                                </select>

                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="bulan">Bulan</label>
                                <select class="form-control select2bs4" name="bulan" id="bulan">
                                    @php
                                        // Menampilkan opsi bulan
                                        foreach ($bulanIndonesia as $index => $namaBulan) {
                                            $selected = $index + 1 == $bulanNow ? 'selected' : '';
                                            echo "<option value='" . ($index + 1) . "' $selected>$namaBulan</option>";
                                        }
                                    @endphp
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" name="resetButton" value="resetButton" class="btn btn-success">Reset</button>
                    <button onclick="simpanKeLocalStorage()" class="btn btn-primary ml-auto" type="submit"
                        name="submitBulan">Tampilkan Data</button>
                </div>
            </form>
        </div>
        <div class="card">
            <div class="card-header">
                <b>Kecamatan {{ $kecamatan->kecamatan }}</b>
                <div class="d-flex justify-content-end">

                    {{-- @if ($totalEntryNow1 === $totalEntryLast && $totalEntryLast !== 0) --}}
                    @if ($entryLast !== null)
                        @php
                            $sbsLast = App\Models\SBS::where('entry_id', $entryLast->id)
                                ->whereNotIn('status_tanaman', [1]) //kecuali habis
                                ->get()
                                ->count();
                        @endphp
                    @endif
                    @if ($entryNow !== null && $entryLast !== null)
                        @php
                            $sbsNow = App\Models\SBS::where('entry_id', $entryNow->id)
                                ->whereIn('status', [1, 4, 6])
                                ->whereNotIn('status_tanaman', [2]) //kecuali tanaman baru
                                ->get()
                                ->count();
                        @endphp
                        @if (
                            ($sbsNow == $sbsLast && $entryNow->status == 0) ||
                                ($sbsNow == $sbsLast && $entryNow->status == 3) ||
                                ($sbsNow == $sbsLast && $entryNow->status == 5))
                            <form method="post" action="/entry">
                                @csrf
                                <input type="hidden" name="bulan" value="{{ $bulanNow }}">
                                <input type="hidden" name="tahun" value="{{ $tahunNow }}">
                                <button class="btn btn-success" type="submit" name="sendKues" value="sendKues"><i
                                        class="fa fa-send">
                                    </i> </button>
                            </form>
                        @endif
                        @if ($entryNow->status == 0 || $entryNow->status == 1 || $entryNow->status == 3 || $entryNow->status == 5)
                            <button class="btn btn-primary ml-2" data-toggle="modal" data-target="#tambah"><i
                                    class="fa fa-plus">
                                </i> </button>
                        @endif
                    @elseif($entryLast !== null)
                        @php
                            $sbsNow = '';
                        @endphp
                        @if ($entryLast->status == 6 && $sbsNow != $sbsLast)
                            <button class="btn btn-primary ml-2" data-toggle="modal" data-target="#tambah"><i
                                    class="fa fa-plus">
                                </i> </button>
                        @endif
                    @endif
                </div>
            </div>
            <div class="card-body">
                <section class="content">
                    <div class="container-fluid">
                        <div class="row">
                            @foreach ($tanaman as $value)
                                <div class="col-md-4 col-6">
                                    <div class="card mb-3">
                                        <div class="row no-gutters">
                                            <div class="col-md-3">
                                                <img src="{{ asset('img/tanaman.png') }}" class="img-fluid" alt="...">
                                            </div>
                                            <div class="col-md-9">
                                                <div class="card-body">
                                                    <h5 class="card-title"><a
                                                            href="/entry/pertLuas/{{ $value->id }}">{{ $value->nama_tanaman }}</a>
                                                    </h5>
                                                    {{-- <p class="card-text">This is a wider card with supporting text below as a natural
                                                    lead-in to additional content. This content is a little bit longer.</p> --}}
                                                    <p class="card-text"><small class="text-muted">
                                                            @php
                                                                $sbsLast = App\Models\SBS::where('entry_id', $entryLast->id)
                                                                    ->where('tanaman_id', $value->id)
                                                                    ->first();
                                                            @endphp
                                                            Status Entry :
                                                            @if ($entryNow != null)
                                                                @php
                                                                    $sbs = App\Models\SBS::where('entry_id', $entryNow->id)
                                                                        ->where('tanaman_id', $value->id)
                                                                        ->first();

                                                                @endphp
                                                                @if ($sbs != null)
                                                                    @if ($sbs->status == 6)
                                                                        <small class="badge badge-success">Disetujui
                                                                            BPS</small>
                                                                    @elseif($sbs->status == 5)
                                                                        <small class="badge badge-warning">Revisi
                                                                            BPS</small>
                                                                        @if ($sbsLast->status_tanaman == 2)
                                                                            <button class="badge badge-danger badge-sm"
                                                                                data-toggle="modal"
                                                                                data-target="#delete{{ $sbsLast->tanaman_id }}"><i
                                                                                    class="fas fa-trash"></i></button>
                                                                        @endif
                                                                    @elseif($sbs->status == 4)
                                                                        <small class="badge badge-success">Disetujui
                                                                            Dinas</small>
                                                                    @elseif($sbs->status == 3)
                                                                        <small class="badge badge-warning">Revisi
                                                                            Dinas </small>
                                                                        @if ($sbsLast->status_tanaman == 2)
                                                                            <button class="badge badge-danger badge-sm"
                                                                                data-toggle="modal"
                                                                                data-target="#delete{{ $sbsLast->tanaman_id }}"><i
                                                                                    class="fas fa-trash"></i></button>
                                                                        @endif
                                                                    @elseif($sbs->status == 2)
                                                                        <small class="badge badge-success">Terkirim</small>
                                                                    @elseif($sbs->status == 1)
                                                                        <small class="badge badge-success"><i
                                                                                class="fas fa-check"></i></small>
                                                                        @if ($sbsLast->status_tanaman == 2)
                                                                            <button class="badge badge-danger badge-sm"
                                                                                data-toggle="modal"
                                                                                data-target="#delete{{ $sbsLast->tanaman_id }}"><i
                                                                                    class="fas fa-trash"></i></button>
                                                                        @endif
                                                                    @else
                                                                        <small class="badge badge-info"><i
                                                                                class="fas fa-spinner"></i></small>
                                                                    @endif
                                                                @else
                                                                    @if ($sbsLast->status_tanaman == 2)
                                                                        <small class="badge badge-danger"><i
                                                                                class="fas fa-times"></i></small>
                                                                        <button class="badge badge-danger badge-sm"
                                                                            data-toggle="modal"
                                                                            data-target="#delete{{ $sbsLast->tanaman_id }}"><i
                                                                                class="fas fa-trash"></i></button>
                                                                    @else
                                                                        <small class="badge badge-danger"><i
                                                                                class="fas fa-times"></i></small>
                                                                    @endif
                                                                @endif
                                                            @else
                                                                <small class="badge badge-danger"><i
                                                                        class="fas fa-times"></i></small>
                                                                @if ($sbsLast->status_tanaman == 2)
                                                                    <button class="badge badge-danger badge-sm"
                                                                        data-toggle="modal"
                                                                        data-target="#delete{{ $sbsLast->tanaman_id }}"><i
                                                                            class="fas fa-trash"></i></button>
                                                                @endif
                                                            @endif
                                                        </small>
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-md-12 ">
                                                @if ($entryNow != null)
                                                    @if ($sbs != null)
                                                        @if ($sbs->status == 3)
                                                            <small class="text-center text-info">Note:
                                                                {{ $sbs->catatan_dinas }}</small>
                                                        @endif
                                                        @if ($sbs->status == 5)
                                                            <small class="text-center text-info">Note:
                                                                {{ $sbs->catatan_BPS }}</small>
                                                        @endif
                                                    @elseif($sbsLast->catatan_dinas !== null && $sbsLast->status_tanaman == 2)
                                                        <small class="text-center text-info">Note:
                                                            {{ $sbsLast->catatan_dinas }}</small>
                                                    @elseif($sbsLast->catatan_BPS !== null && $sbsLast->status_tanaman == 2)
                                                        <small class="text-center text-info">Note:
                                                            {{ $sbsLast->catatan_BPS }}</small>
                                                    @endif
                                                @endif


                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>
                </section>
            </div>
            <!-- /.card-body -->
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
                <form method="post" action="/entry">
                    @csrf
                    <div class="modal-body">
                        <table id="example1" class="table table-bordered table-striped ">
                            <thead>
                                <tr>
                                    <th>Nama Tanaman</th>
                                    <th>Tambah</th>
                                </tr>

                            </thead>
                            <tbody>
                                @foreach ($allTanaman as $value)
                                    <tr>
                                        <td>{{ $value->nama_tanaman }}</td>
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

    {{-- Delete Tanaman --}}
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
                    <form method="post" action="/entry/{{ $value->id }}">
                        @method('delete')
                        @csrf
                        <div class="modal-body">
                            <input type="hidden" value="{{ $bulanLalu }}" name="bulanLalu">
                            <input type="hidden" value="{{ $tahunLalu }}" name="tahunLalu">
                            <input type="hidden" value="{{ $bulanNow }}" name="bulanNow">
                            <input type="hidden" value="{{ $tahunNow }}" name="tahunNow">
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
