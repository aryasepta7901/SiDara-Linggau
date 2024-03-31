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
                <form method="post" action="{{ url('/tbfentry') }}">
                    @csrf
                    <input type="hidden" name="tanaman_id" value="{{ $tanaman->id }}">
                    <input type="hidden" name="kecamatan_id" value="{{ Auth()->user()->kec_id }}">
                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">

                    {{-- Cek Status Entry Bulan Kemarin --}}
                    @if ($tbfLast->status_tanaman == 2)
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="pekerjaan">R7: Luas Penamaan Baru / Tambah Tanaman
                                    ({{ $tanaman->satuan_luas }}) </label>
                                <input type="number" class="form-control @error('r7') is-invalid  @enderror" id="r7"
                                    name="r7"
                                    @if ($tbfNow != null) value="{{ old('r7', $tbfNow->r7) }}" @endif
                                    step=".01" min="0"
                                    @if ($entryNow != null && $tbfNow != null) @if ($tbfNow->status == 2 || $tbfNow->status == 4 || $tbfNow->status == 6) readonly @endif
                                    @endif
                                >
                                @error('r7')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="note">r12: Keterangan</label>
                                <input type="text" class="form-control @error('note') is-invalid  @enderror"
                                    id="note" name="note"
                                    @if ($tbfNow != null) value="{{ old('note', $tbfNow->note) }}" @else  value="{{ old('note') }}" @endif
                                    @if ($entryNow != null && $tbfNow != null) @if ($tbfNow->status == 2 || $tbfNow->status == 4 || $tbfNow->status == 6) readonly @endif
                                    @endif
                                >
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
                                <label for="pekerjaan">R3: Luas Tanaman Akhir Triwulan Yang Lalu
                                    ({{ $tanaman->satuan_luas }})</label>
                                <input type="number" class="form-control  @error('r3') is-invalid  @enderror"
                                    name="r3" id="r3"
                                    @if ($tbfLast != null && $twNow == 1 && $tbfNow == null) value="{{ old('r3', $tbfLast->r8) }}"  
                                    @elseif($tbfNow != null && $twNow == 1) value="{{ old('r3', $tbfNow->r3) }}" 
                                    @elseif($tbfLast != null)  value="{{ $tbfLast->r8 }}" readonly @endif
                                    step=".01" min="0"
                                    @if ($entryNow != null && $tbfNow != null) @if ($tbfNow->status == 2 || $tbfNow->status == 4 || $tbfNow->status == 6) readonly @endif
                                    @endif>
                                @error('r3')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <hr>
                            {{-- <p class="text-center text-bold my-3">Luas Panen ({{ $tanaman->satuan_luas }})</p> --}}
                            <div class="row">
                                @if ($tanaman->belum_habis == 1)
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="input1">R4: Luas Panen Habis/Dibongkar
                                                ({{ $tanaman->satuan_luas }})</label>
                                            <input type="number" id="input1" oninput="showHideInput1()"
                                                class="form-control @error('r4') is-invalid  @enderror @if (Session::has('error1')) is-invalid @endif"
                                                id="r4" name="r4"
                                                @if ($tbfNow != null) value="{{ old('r4', $tbfNow->r4) }}" @else  value="{{ old('r4') }}" @endif
                                                step=".01" min="0"
                                                @if ($entryNow != null && $tbfNow != null) @if ($tbfNow->status == 2 || $tbfNow->status == 4 || $tbfNow->status == 6) readonly @endif
                                                @endif>
                                            @error('r4')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="input2">R5: Luas Panen Belum Habis
                                                ({{ $tanaman->satuan_luas }})</label>
                                            <input type="number" id="input2" oninput="showHideInput2()"
                                                class="form-control @error('r5') is-invalid  @enderror @if (Session::has('error1')) is-invalid @endif"
                                                @if ($tbfNow != null) value="{{ old('r5', $tbfNow->r5) }}" @else  value="{{ old('r5') }}" @endif
                                                id="r5" name="r5" undefined step=".01" min="0"
                                                @if ($entryNow != null && $tbfNow != null) @if ($tbfNow->status == 2 || $tbfNow->status == 4 || $tbfNow->status == 6) readonly @endif
                                                @endif>
                                            @error('r5')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="pekerjaan">R6: Luas Rusak / Tidak Berhasil / Puso
                                                ({{ $tanaman->satuan_luas }})</label>
                                            <input type="number"
                                                class="form-control @error('r6') is-invalid  @enderror @if (Session::has('error1')) is-invalid @endif"
                                                @if ($tbfNow != null) value="{{ old('r6', $tbfNow->r6) }}" @else value="{{ old('r6') }}" @endif
                                                id="r6" name="r6" undefined step=".01" min="0"
                                                @if ($entryNow != null && $tbfNow != null) @if ($tbfNow->status == 2 || $tbfNow->status == 4 || $tbfNow->status == 6) readonly @endif
                                                @endif>
                                            @error('r6')
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
                                    {{-- @if ($tbfLast != null) --}}
                                    {{-- <span class=" text-info">Note: Total nilai R5, R6, dan R7 harus kufrang
                                        dari/sama dengan
                                        {{ $tbfLast->r9 }} ({{ $tanaman->satuan_luas }}) </span> --}}
                                    {{-- @else --}}
                                    <span class="text-center text-info" id="result2"></span>
                                    {{-- @endif --}}
                                @else
                                    <input type="hidden" value="0" name="r6">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="input1">r4: Luas Panen Habis/Dibongkar
                                                ({{ $tanaman->satuan_luas }})</label>
                                            <input type="number" id="input1" oninput="showHideInput1()"
                                                class="form-control @error('r4') is-invalid  @enderror @if (Session::has('error1')) is-invalid @endif"
                                                id="r4" name="r4"
                                                @if ($tbfNow != null) value="{{ old('r4', $tbfNow->r4) }}" @else  value="{{ old('r4') }}" @endif
                                                step=".01" min="0"
                                                @if ($entryNow != null && $tbfNow != null) @if ($tbfNow->status == 2 || $tbfNow->status == 4 || $tbfNow->status == 6) readonly @endif
                                                @endif>
                                            @error('r4')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="pekerjaan">R6: Luas Rusak / Tidak Berhasil / Puso
                                                ({{ $tanaman->satuan_luas }})</label>
                                            <input type="number"
                                                class="form-control @error('r6') is-invalid  @enderror @if (Session::has('error1')) is-invalid @endif"
                                                @if ($tbfNow != null) value="{{ old('r6', $tbfNow->r6) }}" @else value="{{ old('r6') }}" @endif
                                                id="r6" name="r6" undefined step=".01" min="0"
                                                @if ($entryNow != null && $tbfNow != null) @if ($tbfNow->status == 2 || $tbfNow->status == 4 || $tbfNow->status == 6) readonly @endif
                                                @endif>
                                            @error('r6')
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
                                    <span class="text-center text-info" id="result2"></span>

                                @endif
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="pekerjaan">R7: Luas Penamaan Baru / Tambah Tanam
                                            ({{ $tanaman->satuan_luas }})
                                        </label>
                                        <input type="number" class="form-control @error('r7') is-invalid  @enderror"
                                            id="r7" name="r7"
                                            @if ($tbfNow != null) value="{{ old('r7', $tbfNow->r7) }}" @else  value="{{ old('r7') }}" @endif
                                            step=".01" min="0"
                                            @if ($entryNow != null && $tbfNow != null) @if ($tbfNow->status == 2 || $tbfNow->status == 4 || $tbfNow->status == 6) readonly @endif
                                            @endif>
                                        @error('r7')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <hr>

                            @if ($entryNow != null && $tbfNow != null)
                                @if ($tbfNow->status == 2 || $tbfNow->status == 4 || $tbfNow->status == 6)
                                    {{-- Readonly --}}
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label for="pekerjaan">R8: Luas Tanaman Akhir Triwulan Laporan</label>
                                                <input class="form-control" type="number" readonly
                                                    value="{{ $tbfNow->r8 }}">
                                            </div>
                                        </div>
                                    </div>
                                    <p class="text-center text-bold my-3">Produksi ({{ $tanaman->satuan_produksi }})</p>
                                    <div class="row">
                                        @if ($tanaman->belum_habis == 1)
                                            @if ($tbfNow->r4 != null)
                                                <div @if ($tbfNow->r5 != null) class="col-lg-6" @else class="col-lg-12" @endif
                                                    id="conditionalInputDiv1">
                                                    <div class="form-group">
                                                        <label for="conditionalInput">R9: Produksi Dipanen
                                                            Habis/Dibongkar</label>
                                                        <input type="number"
                                                            class="form-control @error('r9') is-invalid  @enderror"
                                                            id="r9" name="r9"
                                                            value="{{ old('r9', $tbfNow->r9) }}" step=".01"
                                                            min="0" readonly>
                                                    </div>
                                                </div>
                                            @endif
                                            @if ($tbfNow->r5 != null)
                                                <div @if ($tbfNow->r4 != null) class="col-lg-6" @else class="col-lg-12" @endif
                                                    id="conditionalInputDiv2">
                                                    <div class="form-group">
                                                        <label for="pekerjaan">R10: Produksi Belum Habis</label>
                                                        <input type="number"
                                                            class="form-control @error('r10') is-invalid  @enderror"
                                                            id="r10" name="r10"
                                                            value="{{ old('r10', $tbfNow->r10) }}" step=".01"
                                                            min="0" readonly>
                                                    </div>
                                                </div>
                                            @endif
                                        @else
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label for="pekerjaan">R9: Produksi Dipanen Habis/Dibongkar</label>
                                                    <input
                                                        type="number"class="form-control @error('r9') is-invalid  @enderror"
                                                        name="r9" value="{{ old('r9', $tbfNow->r9) }}"
                                                        step=".01" min="0" readonly>
                                                </div>
                                            </div>
                                        @endif
                                        <hr>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="r11">R11: Harga Jual Petani Per Kilogram (Rupiah)</label>
                                                <input type="number"
                                                    class="form-control @error('r11') is-invalid  @enderror"
                                                    id="r11" name="r11" value="{{ old('r11', $tbfNow->r11) }}"
                                                    readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="note">R12: Keterangan</label>
                                                <input type="text"
                                                    class="form-control @error('note') is-invalid  @enderror"
                                                    id="note" name="note"
                                                    value="{{ old('note', $tbfNow->note) }}" readonly>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endif
                        </div>
                    @endif
                    @if ($tbfLast->status_tanaman == 2)
                        <div class="modal-footer justify-content-between">

                            <a type="button" href="{{ url('/tbfentry') }}" class="btn btn-default"
                                data-dismiss="modal">Close</a>
                            @if ($entryNow != null && $tbfNow != null)
                                @if ($tbfNow->status != 2 && $tbfNow->status != 4 && $tbfNow->status != 6)
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
                            <a type="button" href="{{ url('/tbfentry') }}" class="btn btn-default"
                                data-dismiss="modal">Close</a>
                            @if ($entryNow != null && $tbfNow != null)
                                @if ($tbfNow->status != 2 && $tbfNow->status != 4 && $tbfNow->status != 6)
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
