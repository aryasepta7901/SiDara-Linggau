@extends('layouts.backEnd.main')

@section('content')
    <div class="col-lg-12">
        <div class="card">
            <form method="post" action="{{ url('/entry/storeMonthYearSelection') }}">
                <div class="card-body">
                    @csrf

                    <?php
                    // Daftar nama bulan dalam Bahasa Indonesia
                    $bulanIndonesia = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
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
            {{-- <div class="card-header d-flex justify-content-between">

            </div> --}}
            <div class="card-body">
                <table id="example1" class="table table-bordered table-striped ">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kecamatan</th>
                            <th>Status</th>
                        </tr>

                    </thead>
                    <tbody>
                        @foreach ($kecamatan as $value)
                            @php
                                $entryNow = App\Models\EntrySBS::where('kec_id', $value->id)
                                    ->where('bulan', $bulanNow)
                                    ->where('tahun', $tahunNow)
                                    ->first();
                            @endphp
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $value->kecamatan }}</td>
                                @if ($entryNow !== null)
                                    @if ($entryNow->status === 2)
                                        <td class="text-center">
                                            <button class="btn btn-success btn-sm"><i class="fas fa-check"></i>
                                            </button>
                                            <a type="button" href="{{ url('/validasi/bps/' . $entryNow->id) }}"
                                                class="btn btn-primary btn-sm"><i class="fas fa-pen"></i>
                                            </a>
                                        </td>
                                    @elseif($entryNow->status === 4)
                                        <td class="text-center">
                                            <button class="btn btn-success btn-sm">Dinas Setuju</i>
                                            </button>
                                            <a type="button" href="{{ url('/validasi/bps/' . $entryNow->id) }}"
                                                class="btn btn-primary btn-sm"><i class="fas fa-pen"></i>
                                            </a>
                                        </td>
                                    @elseif($entryNow->status === 6)
                                        <td class="text-center">
                                            <button class="btn btn-success btn-sm">BPS Setuju</i>
                                            </button>
                                            <a type="button" href="{{ url('/validasi/bps/' . $entryNow->id) }}"
                                                class="btn btn-primary btn-sm"><i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    @elseif($entryNow->status === 3)
                                        <td class="text-center"><button class="btn btn-warning btn-sm">Revisi Dinas</button>
                                        </td>
                                    @elseif($entryNow->status === 5)
                                        <td class="text-center"><button class="btn btn-warning btn-sm">Revisi BPS</button>
                                        </td>
                                    @elseif($entryNow->status === 0)
                                        <td class="text-center"><button class="btn btn-info btn-sm"><i
                                                    class="fas fa-spinner"></i></button></td>
                                    @endif
                                @else
                                    <td class="text-center"><button class="btn btn-danger btn-sm"><i
                                                class="fas fa-times"></i></button></td>
                                @endif
                            </tr>
                        @endforeach


                    </tbody>
                </table>

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
