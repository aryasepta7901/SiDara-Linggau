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
                    <input type="hidden" name="tanaman_id" value="{{ $tanaman->id }}">
                    <input type="hidden" name="kecamatan_id" value="{{ Auth()->user()->kec_id }}">
                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">

                    {{-- Cek Status Entry Bulan Kemarin --}}
                    @if ($sbsLast->status_tanaman == 2)
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="pekerjaan">R8: Luas Penamaan Baru / Tambah Tanam
                                    ({{ $tanaman->satuan_luas }}) </label>
                                <input type="number" class="form-control @error('r8') is-invalid  @enderror" id="r8"
                                    name="r8"
                                    @if ($sbsNow != null) value="{{ old('r8', $sbsNow->r8) }}" @else  value="{{ old('r8', 0.0) }}" @endif
                                    step=".01" min="0">
                                @error('r8')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="note">r13: Keterangan</label>
                                <input type="text" class="form-control @error('note') is-invalid  @enderror"
                                    id="note" name="note"
                                    @if ($sbsNow != null) value="{{ old('note', $sbsNow->note) }}" @else  value="{{ old('note') }}" @endif>
                                @error('note')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    @else
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="pekerjaan">R4: Luas Tanaman Akhir Bulan Yang Lalu
                                    ({{ $tanaman->satuan_luas }})</label>
                                <input type="number" class="form-control" name="r4" id="r4"
                                    @if ($sbsLast != null) value="{{ $sbsLast->r9 }}" readonly 
                                    
                                @else value="{{ old('r4', $sbsNow->r4) }}" @endif>
                            </div>

                            <hr>
                            {{-- <p class="text-center text-bold my-3">Luas Panen ({{ $tanaman->satuan_luas }})</p> --}}
                            <div class="row">
                                @if ($tanaman->belum_habis == 1)
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="input1">R5: Luas Panen Habis/Dibongkar
                                                ({{ $tanaman->satuan_luas }})</label>
                                            <input type="number" id="input1" oninput="showHideInput1()"
                                                class="form-control @error('r5') is-invalid  @enderror @if (Session::has('error1')) is-invalid @endif"
                                                id="r5" name="r5"
                                                @if ($sbsNow != null) value="{{ old('r5', $sbsNow->r5) }}" @else  value="{{ old('r5', 0.0) }}" @endif
                                                step=".01" min="0">
                                            @error('r5')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="input2">R6: Luas Panen Belum Habis
                                                ({{ $tanaman->satuan_luas }})</label>
                                            <input type="number" id="input2"
                                                oninput="showHideInput2()"class="form-control @error('r6') is-invalid  @enderror @if (Session::has('error1')) is-invalid @endif"
                                                id="r6" name="r6"
                                                @if ($sbsNow != null) value="{{ old('r6', $sbsNow->r6) }}" @else  value="{{ old('r6', 0.0) }}" @endif
                                                step=".01" min="0">
                                            @error('r6')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="pekerjaan">R7: Luas Rusak / Tidak Berhasil / Puso
                                                ({{ $tanaman->satuan_luas }})</label>
                                            <input type="number"
                                                class="form-control @error('r7') is-invalid  @enderror @if (Session::has('error1')) is-invalid @endif"
                                                id="r7" name="r7"
                                                @if ($sbsNow != null) value="{{ old('r7', $sbsNow->r7) }}" @else  value="{{ old('r7', 0.0) }}" @endif
                                                step=".01" min="0">
                                            @error('r7')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    @if (Session::has('error1'))
                                        <span class=" text-danger">
                                            {{ Session::get('error1') }}
                                        </span>
                                    @endif
                                    <hr>
                                    {{-- @if ($sbsLast != null) --}}
                                    <span class=" text-info">Note: Total nilai R5, R6, dan R7 harus kurang
                                        dari/sama dengan
                                        {{ $sbsLast->r9 }} ({{ $tanaman->satuan_luas }}) </span>
                                    {{-- @else --}}
                                    {{-- <span class="text-center text-info" id="result"></span> --}}
                                    {{-- @endif --}}
                                @else
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="pekerjaan">R5: Luas Panen Habis/Dibongkar
                                                ({{ $tanaman->satuan_luas }})</label>
                                            <input type="number"
                                                class="form-control @error('r5') is-invalid  @enderror @if (Session::has('error1')) is-invalid @endif"
                                                id="r5" name="r5"
                                                @if ($sbsNow != null) value="{{ old('r5', $sbsNow->r5) }}" @else  value="{{ old('r5', 0.0) }}" @endif
                                                step=".01" min="0">
                                            @error('r5')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                            <input type="hidden" value="0" name="r6">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="pekerjaan">R7: Luas Rusak / Tidak Berhasil / Puso
                                                ({{ $tanaman->satuan_luas }})
                                            </label>
                                            <input type="number"
                                                class="form-control @error('r7') is-invalid  @enderror @if (Session::has('error1')) is-invalid @endif"
                                                id="r7" name="r7"
                                                @if ($sbsNow != null) value="{{ old('r7', $sbsNow->r7) }}" @else  value="{{ old('r7', 0.0) }}" @endif
                                                step=".01" min="0">
                                            @error('r7')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    @if (Session::has('error1'))
                                        <span class=" text-danger">
                                            {{ Session::get('error1') }}
                                        </span>
                                    @endif
                                    <span class=" text-info">Note: Total nilai R5 dan R7 harus kurang
                                        dari/sama dengan
                                        {{ $sbsLast->r9 }} ({{ $tanaman->satuan_luas }}) </span>
                                @endif
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="pekerjaan">R8: Luas Penamaan Baru / Tambah Tanam
                                            ({{ $tanaman->satuan_luas }})
                                        </label>
                                        <input type="number" class="form-control @error('r8') is-invalid  @enderror"
                                            id="r8" name="r8"
                                            @if ($sbsNow != null) value="{{ old('r8', $sbsNow->r8) }}" @else  value="{{ old('r8', 0.0) }}" @endif
                                            step=".01" min="0">
                                        @error('r8')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <hr>
                            @if ($entryNow != null)
                                @if ($entryNow->status == 2 || $entryNow->status == 4 || $entryNow->status == 6)
                                    {{-- Readonly --}}
                                    <p class="text-center text-bold my-3">Produksi (Kuintal)</p>
                                    <div class="row">
                                        @if ($tanaman->belum_habis == 1)
                                            @if ($sbsNow->r5 != null)
                                                <div @if ($sbsNow->r6 != null) class="col-lg-6" @else class="col-lg-12" @endif
                                                    id="conditionalInputDiv1">
                                                    <div class="form-group">
                                                        <label for="conditionalInput">R10: Produksi Dipanen
                                                            Habis/Dibongkar</label>
                                                        <input type="number"
                                                            class="form-control @error('r10') is-invalid  @enderror"
                                                            id="r10" name="r10"
                                                            value="{{ old('r10', $sbsNow->r10) }}" step=".01"
                                                            min="0">
                                                    </div>
                                                </div>
                                            @endif
                                            @if ($sbsNow->r6 != null)
                                                <div @if ($sbsNow->r5 != null) class="col-lg-6" @else class="col-lg-12" @endif
                                                    id="conditionalInputDiv2">
                                                    <div class="form-group">
                                                        <label for="pekerjaan">R11: Produksi Belum Habis</label>
                                                        <input type="number"
                                                            class="form-control @error('r11') is-invalid  @enderror"
                                                            id="r11" name="r11"
                                                            value="{{ old('r11', $sbsNow->r11) }}" step=".01"
                                                            min="0">
                                                    </div>
                                                </div>
                                            @endif
                                        @else
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label for="pekerjaan">R10: Produksi Dipanen Habis/Dibongkar</label>
                                                    <input
                                                        type="number"class="form-control @error('r10') is-invalid  @enderror"
                                                        name="r10" value="{{ old('r10', $sbsNow->r10) }}"
                                                        step=".01" min="0">
                                                </div>
                                            </div>
                                        @endif
                                        <hr>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="r12">R12: Harga Jual Petani Per Kilogram (Rupiah)</label>
                                                <input type="number"
                                                    class="form-control @error('r12') is-invalid  @enderror"
                                                    id="r12" name="r12"
                                                    value="{{ old('r12', $sbsNow->r12) }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="note">r13: Keterangan</label>
                                                <input type="text"
                                                    class="form-control @error('note') is-invalid  @enderror"
                                                    id="note" name="note"
                                                    value="{{ old('note', $sbsNow->note) }}">
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endif
                        </div>
                    @endif
                    @if ($sbsLast->status_tanaman == 2)
                        <div class="modal-footer justify-content-between">

                            <a type="button" href="{{ url('/entry') }}" class="btn btn-default"
                                data-dismiss="modal">Close</a>
                            @if ($entryNow != null)
                                @if ($entryNow->status != 2 && $entryNow->status != 4 && $entryNow->status != 6)
                                    <button type="submit" name="submit3" value="submit3"
                                        class="btn btn-primary">Simpan</button>
                                @endif
                            @else
                                <button type="submit" name="submit3" value="submit3"
                                    class="btn btn-primary">Simpan</button>
                            @endif
                        </div>
                    @else
                        <div class="modal-footer justify-content-between">
                            <a type="button" href="{{ url('/entry') }}" class="btn btn-default"
                                data-dismiss="modal">Close</a>
                            @if ($entryNow != null)
                                @if ($entryNow->status != 2 && $entryNow->status != 4 && $entryNow->status != 6)
                                    <button type="submit" name="submit1" value="submit1"
                                        class="btn btn-primary">Selanjutnya</button>
                                @endif
                            @else
                                <button type="submit" name="submit1" value="submit1"
                                    class="btn btn-primary">Selanjutnya</button>
                            @endif


                        </div>
                    @endif

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
