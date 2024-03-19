@extends('layouts.backEnd.main')

@section('content')
    <div class="col-lg-12">

        <div class="card">
            <div class="card-header d-flex justify-content-between">
                {{-- <b>Kecamatan {{ $kecamatan->kecamatan }}</b> --}}
                {{-- <button class="btn btn-primary ml-auto" data-toggle="modal" data-target="#tambah"><i class="fa fa-plus">
                    </i> </button> --}}
            </div>
            <div class="card-body">
                <form method="post" action="{{ url('/entry') }}">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="tanaman_id" value="{{ $tanaman->id }}">

                        <input type="hidden" name="r5" value="{{ $sbsNow->r5 }}">
                        <input type="hidden" name="r6" value="{{ $sbsNow->r6 }}">
                        <input type="hidden" name="min_harga" value="{{ $tanaman->min_harga }}">
                        <input type="hidden" name="max_harga" value="{{ $tanaman->max_harga }}">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="pekerjaan">R9: Luas Tanaman Akhir Bulan Laporan</label>
                                    <input type="number" class="form-control" readonly value="{{ $sbsNow->r9 }}">
                                </div>
                            </div>
                        </div>
                        @if ($sbsNow->r5 != 0 || $sbsNow->r6 != 0)
                            <p class="text-center text-bold my-3">Produksi (Kuintal)</p>
                        @endif
                        <div class="row">
                            @if ($tanaman->belum_habis == 1)
                                @if ($sbsNow->r5 != 0)
                                    <div @if ($sbsNow->r6 != 0) class="col-lg-6" @else class="col-lg-12" @endif
                                        id="conditionalInputDiv1">
                                        <div class="form-group">
                                            <label for="conditionalInput">R10: Produksi Dipanen Habis/Dibongkar</label>
                                            <input type="number" class="form-control @error('r10') is-invalid  @enderror"
                                                id="r10" name="r10"
                                                @if ($sbsNow->r10 != 0) value="{{ old('r10', $sbsNow->r10) }}" @else  value="{{ old('r10') }}" @endif
                                                step=".01" min="0">
                                            @error('r10')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                @endif
                                @if ($sbsNow->r6 != 0)
                                    <div @if ($sbsNow->r5 != 0) class="col-lg-6" @else class="col-lg-12" @endif
                                        id="conditionalInputDiv2">
                                        <div class="form-group">
                                            <label for="pekerjaan">R11: Produksi Belum Habis</label>
                                            <input type="number" class="form-control @error('r11') is-invalid  @enderror"
                                                id="r11" name="r11"
                                                @if ($sbsNow->r11 != 0) value="{{ old('r11', $sbsNow->r11) }}" @else  value="{{ old('r11') }}" @endif
                                                step=".01" min="0">
                                            @error('r11')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                @endif
                            @else
                                @if ($sbsNow->r5 != 0)
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="pekerjaan">R10: Produksi Dipanen Habis/Dibongkar</label>
                                            <input type="number"class="form-control @error('r10') is-invalid  @enderror"
                                                name="r10"
                                                @if ($sbsNow->r10 != 0) value="{{ old('r10', $sbsNow->r10) }}" @else  value="{{ old('r10') }}" @endif
                                                step=".01" min="0">
                                            @error('r10')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                @endif
                            @endif
                            {{-- <p>{{ $tanaman->min_produktivitas }} -{{ $tanaman->max_produktivitas }}</p> --}}

                        </div>
                        <hr>
                        @if ($sbsNow->r5 != 0 || $sbsNow->r6 != 0)
                            <div class="form-group">
                                <label for="r12">R12: Harga Jual Petani Per Kilogram (Rupiah)</label>
                                <input type="number" class="form-control @error('r12') is-invalid  @enderror"
                                    id="r12" name="r12"
                                    @if ($sbsNow->r12 != 0) value="{{ old('r12', $sbsNow->r12) }}" @else  value="{{ old('r12') }}" @endif
                                    min="{{ $tanaman->min_harga }}" max="{{ $tanaman->max_harga }}">
                                @error('r12')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                                {{-- <p class="text-info">Note: Harga Jual Harus diantara {{ $tanaman->min_harga }} dan
                                    {{ $tanaman->max_harga }} (Rupiah)</p> --}}
                            </div>
                        @endif
                        <div class="form-group">
                            <label for="note">r13: Keterangan</label>
                            <input type="text" class="form-control @error('note') is-invalid  @enderror" id="note"
                                name="note" value="{{ old('note', $sbsNow->note) }}">
                            @error('note')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="modal-footer justify-content-between">
                        <a type="button" href="{{ url('/entry/pertLuas/' . $tanaman->id) }}" class="btn btn-default"
                            data-dismiss="modal">Sebelumnya</a>
                        <button type="submit" name="submit2" value="submit2" class="btn btn-primary">Simpan</button>
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

    @if ($errors->any())
        <script>
            Toast.fire({
                icon: 'error',
                title: "Terdapat Kesalahan Pengisian!!",
                // text: "{{ implode(' , ', $errors->all()) }}",
                text: "Harap Perhatikan Isian Yang berwarna merah",
                type: "error"
            });
        </script>
        <br>
    @endif
    <br>
    @if (Session::has('success'))
        <script>
            Toast.fire({
                icon: 'success',
                title: "{{ Session::get('success') }}"
            })
        </script>
    @endif
@endsection
