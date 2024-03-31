@extends('layouts.backEnd.main')

@section('content')
    <div class="col-lg-12">

        @if ($entryNow !== null)
            @if ($entryNow->status == 0)
                @php
                    $text = 'Entry Sedang dilakukan, jika sudah selesai silahkan klik tombol kirim';
                    $alert = 'info';
                @endphp
            @elseif($entryNow->status == 2)
                @php
                    $text = 'Formulir telah di kirimkan kepada <b>Admin Dinas</b> untuk dilakukan reviu';
                    $alert = 'success';
                @endphp
            @elseif($entryNow->status == 3)
                @php
                    $text =
                        'Terdapat revisi dari <b>Admin Dinas</b>. Perhatikan catatan yang diberikan dan lakukan perbaikan. <br> Jika sudah selesai silahkan klik tombol kirim';
                    $alert = 'warning';
                @endphp
            @elseif($entryNow->status == 4)
                @php
                    $text = 'Entry disetujui <b>Admin Dinas</b>, Formulir Akan dikirimkan kepada <b>BPS</b>';
                    $alert = 'success';
                @endphp
            @elseif($entryNow->status == 5)
                @php
                    $text =
                        'Terdapat revisi dari <b>BPS</b>. Perhatikan catatan yang diberikan dan lakukan perbaikan. <br> Jika sudah selesai silahkan klik tombol kirim';
                    $alert = 'warning';
                @endphp
            @elseif($entryNow->status == 6)
                @php
                    $text = 'Entry disetujui <b>BPS</b>, Data Sudah Final';
                    $alert = 'success';
                @endphp
            @endif
            <div class="alert alert-{{ $alert }} alert-dismissible">
                <button type="button" class="close"></button>
                <p><i class="icon fas fa-info"></i> {!! $text !!}

                    @if ($entryNow->status == 2 || $entryNow->status == 4 || $entryNow->status == 6)
                        <hr>
                        <a href="{{ url('/thentry/' . $entryNow->id) }}" class="btn btn-sm btn-info">Lihat Rekapitulasi Data
                            <i class="fas fa-eye"></i>
                        </a>
                    @endif

                </p>
            </div>
        @elseif($entryLast != null)
            <div class="alert alert-info alert-dismissible">
                <button type="button" class="close"></button>
                <p><i class="icon fas fa-info"></i>Entry belum dilakukan, silahkan lakukan entry</p>
            </div>
        @endif

        <div class="card">
            <form method="post" action="{{ url('/thentry/storeMonthYearSelection') }}">
                <div class="card-body">
                    @csrf
                    <?php
                    ?>
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
                                <label for="triwulan">Triwulan</label>
                                <select class="form-control select2bs4" name="triwulan" id="triwulan">
                                    @php
                                        // Menampilkan opsi angka dari 01 hingga 04
                                        for ($i = 1; $i <= 4; $i++) {
                                            $angka = str_pad($i, 2, '0', STR_PAD_LEFT); // Menambahkan 0 di depan jika panjang string-nya kurang dari dua digit
                                            $selected = $angka == $twNow ? 'selected' : '';
                                            echo "<option value='$angka' $selected>$angka</option>";
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
                <b>Kecamatan {{ $kecamatan->kecamatan }} Triwulan {{ $twNow }} ({{ $tahunNow }})</b>
                <div class="d-flex justify-content-end">

                    {{-- @if ($totalEntryNow1 === $totalEntryLast && $totalEntryLast !== 0) --}}
                    @if ($entryLast !== null)
                        @php
                            $thLast = App\Models\TH::where('entry_id', $entryLast->id)
                                ->whereNotIn('status_tanaman', [1]) //kecuali habis
                                ->get()
                                ->count();
                        @endphp
                    @endif
                    @if ($entryNow !== null && $entryLast !== null)
                        @php
                            $thNow = App\Models\TH::where('entry_id', $entryNow->id)
                                ->whereIn('status', [1, 4, 6])
                                ->whereNotIn('status_tanaman', [2]) //kecuali tanaman baru
                                ->get()
                                ->count();
                        @endphp
                        @if (
                            ($thNow == $thLast && $entryNow->status == 0) ||
                                ($thNow == $thLast && $entryNow->status == 3) ||
                                ($thNow == $thLast && $entryNow->status == 5))
                            <form method="post" action="{{ url('/thentry') }}">
                                @csrf
                                <input type="hidden" name="triwulan" value="{{ $twNow }}">
                                <input type="hidden" name="tahun" value="{{ $tahunNow }}">
                                <button class="btn btn-success" type="submit" name="sendKues" value="sendKues"><i
                                        class="fas fa-paper-plane">
                                    </i> Kirim </button>
                            </form>
                        @endif
                        @if ($entryNow->status == 0 || $entryNow->status == 1 || $entryNow->status == 3 || $entryNow->status == 5)
                            <button class="btn btn-primary ml-2" data-toggle="modal" data-target="#tambah"><i
                                    class="fa fa-plus">
                                </i> </button>
                        @endif
                    @elseif($entryLast !== null)
                        @php
                            $thNow = '';
                        @endphp
                        @if ($entryLast->status == 6 && $thNow != $thLast)
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
                                                    <h5 class="card-title">
                                                        @if ($entryNow != null)
                                                            @php
                                                                $th = App\Models\th::where('entry_id', $entryNow->id)
                                                                    ->where('tanaman_id', $value->id)
                                                                    ->first();

                                                            @endphp
                                                            @if ($th != null && $th->status == 0)
                                                                <form method="post" action="{{ url('/thentry/reset') }}">
                                                                    @csrf
                                                                    <input type="hidden" name="tanaman_id"
                                                                        value="{{ $th->tanaman_id }}">
                                                                    <input type="hidden" name="entry_id"
                                                                        value="{{ $th->entry_id }}">
                                                                    <button class="plain-button"
                                                                        type="submit">{{ $value->nama_tanaman }}</button>
                                                                </form>
                                                            @else
                                                                <a
                                                                    href="{{ url('/thentry/pertLuas/' . $value->id) }}">{{ $value->nama_tanaman }}</a>
                                                            @endif
                                                        @else
                                                            <a
                                                                href="{{ url('/thentry/pertLuas/' . $value->id) }}">{{ $value->nama_tanaman }}</a>
                                                        @endif
                                                    </h5>
                                                    {{-- <p class="card-text">This is a wider card with supporting text below as a natural
                                                    lead-in to additional content. This content is a little bit longer.</p> --}}
                                                    <p class="card-text"><small class="text-muted">
                                                            @php
                                                                $thLast = App\Models\TH::where(
                                                                    'entry_id',
                                                                    $entryLast->id,
                                                                )
                                                                    ->where('tanaman_id', $value->id)
                                                                    ->first();
                                                            @endphp
                                                            Status Entry :
                                                            @if ($entryNow != null)
                                                                @php
                                                                    $th = App\Models\TH::where(
                                                                        'entry_id',
                                                                        $entryNow->id,
                                                                    )
                                                                        ->where('tanaman_id', $value->id)
                                                                        ->first();

                                                                @endphp
                                                                @if ($th != null)
                                                                    @if ($th->status == 6)
                                                                        <small class="badge badge-success">Disetujui
                                                                            BPS</small>
                                                                    @elseif($th->status == 5)
                                                                        <small class="badge badge-warning">Revisi
                                                                            BPS</small>
                                                                        @if ($thLast->status_tanaman == 2)
                                                                            <button class="badge badge-danger badge-sm"
                                                                                data-toggle="modal"
                                                                                data-target="#delete{{ $thLast->tanaman_id }}"><i
                                                                                    class="fas fa-trash"></i></button>
                                                                        @endif
                                                                    @elseif($th->status == 4)
                                                                        <small class="badge badge-success">Disetujui
                                                                            Dinas</small>
                                                                    @elseif($th->status == 3)
                                                                        <small class="badge badge-warning">Revisi
                                                                            Dinas </small>
                                                                        @if ($thLast->status_tanaman == 2)
                                                                            <button class="badge badge-danger badge-sm"
                                                                                data-toggle="modal"
                                                                                data-target="#delete{{ $thLast->tanaman_id }}"><i
                                                                                    class="fas fa-trash"></i></button>
                                                                        @endif
                                                                    @elseif($th->status == 2)
                                                                        <small class="badge badge-success">Terkirim</small>
                                                                    @elseif($th->status == 1)
                                                                        <small class="badge badge-success"><i
                                                                                class="fas fa-check"></i></small>
                                                                        @if ($thLast->status_tanaman == 2)
                                                                            <button class="badge badge-danger badge-sm"
                                                                                data-toggle="modal"
                                                                                data-target="#delete{{ $thLast->tanaman_id }}"><i
                                                                                    class="fas fa-trash"></i></button>
                                                                        @endif
                                                                    @else
                                                                        <small class="badge badge-danger"><i
                                                                                class="fas fa-times"></i></small>
                                                                    @endif
                                                                @else
                                                                    @if ($thLast->status_tanaman == 2)
                                                                        <small class="badge badge-danger"><i
                                                                                class="fas fa-times"></i></small>
                                                                        <button class="badge badge-danger badge-sm"
                                                                            data-toggle="modal"
                                                                            data-target="#delete{{ $thLast->tanaman_id }}"><i
                                                                                class="fas fa-trash"></i></button>
                                                                    @else
                                                                        <small class="badge badge-danger"><i
                                                                                class="fas fa-times"></i></small>
                                                                    @endif
                                                                @endif
                                                            @else
                                                                <small class="badge badge-danger"><i
                                                                        class="fas fa-times"></i></small>
                                                                @if ($thLast->status_tanaman == 2)
                                                                    <button class="badge badge-danger badge-sm"
                                                                        data-toggle="modal"
                                                                        data-target="#delete{{ $thLast->tanaman_id }}"><i
                                                                            class="fas fa-trash"></i></button>
                                                                @endif
                                                            @endif
                                                        </small>
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-md-12 ">
                                                @if ($entryNow != null)
                                                    @if ($th != null)
                                                        @if ($th->status == 3)
                                                            <small class="text-center text-info">Note:
                                                                {{ $th->catatan_dinas }}</small>
                                                        @endif
                                                        @if ($th->status == 5)
                                                            <small class="text-center text-info">Note:
                                                                {{ $th->catatan_BPS }}</small>
                                                        @endif
                                                    @elseif($thLast->catatan_dinas !== null && $thLast->status_tanaman == 2)
                                                        <small class="text-center text-info">Note:
                                                            {{ $thLast->catatan_dinas }}</small>
                                                    @elseif($thLast->catatan_BPS !== null && $thLast->status_tanaman == 2)
                                                        <small class="text-center text-info">Note:
                                                            {{ $thLast->catatan_BPS }}</small>
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
                <form method="post"action="{{ url('/thentry') }}">
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
                    <form method="post" action="{{ url('/thentry/' . $value->id) }}">
                        @method('delete')
                        @csrf
                        <div class="modal-body">
                            <input type="hidden" value="{{ $twLalu }}" name="twLalu">
                            <input type="hidden" value="{{ $tahunLalu }}" name="tahunLalu">
                            <input type="hidden" value="{{ $twNow }}" name="twNow">
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
