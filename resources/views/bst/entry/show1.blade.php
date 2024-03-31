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
                <form method="post" action="{{ url('/bstentry') }}">
                    @csrf
                    <input type="hidden" name="tanaman_id" value="{{ $tanaman->id }}">
                    <input type="hidden" name="kecamatan_id" value="{{ Auth()->user()->kec_id }}">
                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">

                    {{-- Cek Status Entry Bulan Kemarin --}}
                    @if ($bstLast->status_tanaman == 2)
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="pekerjaan">R5: Luas Tanaman Baru / Penanaman Baru
                                        ({{ $tanaman->satuan_luas }}) </label>
                                    <input type="number" class="form-control @error('r5') is-invalid  @enderror"
                                        id="r5" name="r5"
                                        @if ($bstNow != null) value="{{ old('r5', $bstNow->r5) }}" @endif
                                        min="0"
                                        @if ($entryNow != null && $bstNow != null) @if ($bstNow->status == 2 || $bstNow->status == 4 || $bstNow->status == 6) readonly @endif
                                        @endif>
                                    @error('r5')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="pekerjaan">R6: Jumlah Tanaman Akhir Triwulan Laporan
                                        ({{ $tanaman->satuan_luas }})</label>
                                    <input type="number" class="form-control" readonly id="r6" name="r6"
                                        @if ($bstNow != null) value="{{ old('r6', $bstNow->r6) }}" @else value="{{ old('r6') }}" @endif>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="pekerjaan">R7: Tanaman Belum Menghasilkan
                                        ({{ $tanaman->satuan_luas }})</label>
                                    <input type="number" class="form-control" readonly id="r7_khusus" name="r7"
                                        @if ($bstNow != null) value="{{ old('r7', $bstNow->r7) }}" @else value="{{ old('r7') }}" @endif>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="note">r12: Keterangan</label>
                                    <input type="text" class="form-control @error('note') is-invalid  @enderror"
                                        id="note" name="note"
                                        @if ($bstNow != null) value="{{ old('note', $bstNow->note) }}" @else  value="{{ old('note') }}" @endif
                                        @if ($entryNow != null && $bstNow != null) @if ($bstNow->status == 2 || $bstNow->status == 4 || $bstNow->status == 6) readonly @endif
                                        @endif
                                    >
                                    @error('note')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="pekerjaan">R3: Jumlah Tanaman Akhir Triwulan Yang Lalu
                                    ({{ $tanaman->satuan_luas }})</label>
                                <input type="number" class="form-control  @error('r3') is-invalid  @enderror"
                                    name="r3" id="r3"
                                    @if ($bstLast != null && $twNow == 1 && $bstNow == null) value="{{ old('r3', $bstLast->r6) }}"  
                                    @elseif($bstNow != null && $twNow == 1) value="{{ old('r3', $bstNow->r3) }}" 
                                    @elseif($bstLast != null)  value="{{ $bstLast->r6 }}" readonly @endif
                                    min="0"
                                    @if ($entryNow != null && $bstNow != null) @if ($bstNow->status == 2 || $bstNow->status == 4 || $bstNow->status == 6) readonly @endif
                                    @endif>
                                @error('r3')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <hr>
                            <p class="text-center text-bold my-3">Selama Triwulan</p>
                            <div class="row">

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="r4">R4: Tanaman yang Dibongkar/Ditebang
                                            ({{ $tanaman->satuan_luas }})</label>
                                        <input type="number" id="r4" oninput="showHideInput1()"
                                            class="form-control @error('r4') is-invalid  @enderror " id="r4"
                                            name="r4"
                                            @if ($bstNow != null) value="{{ old('r4', $bstNow->r4) }}" @else  value="{{ old('r4') }}" @endif
                                            min="0"
                                            @if ($entryNow != null && $bstNow != null) @if ($bstNow->status == 2 || $bstNow->status == 4 || $bstNow->status == 6) readonly @endif
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
                                        <label for="r5">R5: Tanaman Baru/Penanaman Baru
                                            ({{ $tanaman->satuan_luas }})</label>
                                        <input type="number" id="r5" oninput="showHideInput2()"
                                            class="form-control @error('r5') is-invalid  @enderror "
                                            @if ($bstNow != null) value="{{ old('r5', $bstNow->r5) }}" @else  value="{{ old('r5') }}" @endif
                                            id="r5" name="r5" undefined min="0"
                                            @if ($entryNow != null && $bstNow != null) @if ($bstNow->status == 2 || $bstNow->status == 4 || $bstNow->status == 6) readonly @endif
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
                                        <label for="pekerjaan">R6: Jumlah Tanaman Akhir Triwulan Laporan (3)-(4)+(5)
                                            ({{ $tanaman->satuan_luas }})</label>
                                        <input type="number" class="form-control" readonly id="r6" name="r6"
                                            @if ($bstNow != null) value="{{ old('r6', $bstNow->r6) }}" @else value="{{ old('r6') }}" @endif>
                                    </div>

                                </div>
                            </div>

                            <hr>
                            <div id="akhirTriwulan">
                                <p class="text-center text-bold my-3">Di Akhir Triwulan</p>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="pekerjaan">R7: Tanaman Belum Menghasilkan
                                                ({{ $tanaman->satuan_luas }})</label>
                                            <input type="number"
                                                class="form-control @error('r7') is-invalid  @enderror @if (Session::has('error1')) is-invalid @endif"
                                                @if ($bstNow != null) value="{{ old('r7', $bstNow->r7) }}" @else value="{{ old('r7') }}" @endif
                                                id="r7" name="r7" undefined min="0"
                                                @if ($entryNow != null && $bstNow != null) @if ($bstNow->status == 2 || $bstNow->status == 4 || $bstNow->status == 6) readonly @endif
                                                @endif>
                                            @error('r7')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="pekerjaan">R8: Tanaman Produktif yang Sedang Menghasilkan
                                                ({{ $tanaman->satuan_luas }})</label>
                                            <input type="number"
                                                class="form-control @error('r8') is-invalid  @enderror @if (Session::has('error1')) is-invalid @endif"
                                                @if ($bstNow != null) value="{{ old('r8', $bstNow->r8) }}" @else value="{{ old('r8') }}" @endif
                                                id="r8" name="r8" undefined min="0"
                                                @if ($entryNow != null && $bstNow != null) @if ($bstNow->status == 2 || $bstNow->status == 4 || $bstNow->status == 6) readonly @endif
                                                @endif>
                                            @error('r8')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="pekerjaan">R9: Tanaman Tua/Rusak
                                                ({{ $tanaman->satuan_luas }})</label>
                                            <input type="number"
                                                class="form-control @error('r9') is-invalid  @enderror @if (Session::has('error1')) is-invalid @endif"
                                                @if ($bstNow != null) value="{{ old('r9', $bstNow->r9) }}" @else value="{{ old('r9') }}" @endif
                                                id="r9" name="r9" undefined min="0"
                                                @if ($entryNow != null && $bstNow != null) @if ($bstNow->status == 2 || $bstNow->status == 4 || $bstNow->status == 6) readonly @endif
                                                @endif>
                                            @error('r9')
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
                                    <span class="text-center text-info" id="result4"></span>
                                </div>
                            </div>

                            @if ($entryNow != null && $bstNow != null)
                                <hr>
                                @if ($bstNow->status == 2 || $bstNow->status == 4 || $bstNow->status == 6)
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="r10">R10: Produksi
                                                    ({{ $tanaman->satuan_produksi }})</label>
                                                <input type="number"
                                                    class="form-control @error('r10') is-invalid  @enderror"
                                                    id="r10" name="r10" value="{{ old('r10', $bstNow->r10) }}"
                                                    readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="r11">R11: Rata-rata Harga Jual di Petani per Kilogram
                                                    (Rupiah)
                                                </label>
                                                <input type="number"
                                                    class="form-control @error('r11') is-invalid  @enderror"
                                                    id="r11" name="r11" value="{{ old('r11', $bstNow->r11) }}"
                                                    readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="note">R12: Keterangan</label>
                                                <input type="text"
                                                    class="form-control @error('note') is-invalid  @enderror"
                                                    id="note" name="note"
                                                    value="{{ old('note', $bstNow->note) }}" readonly>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endif
                        </div>
                    @endif
                    @if ($bstLast->status_tanaman == 2)
                        <div class="modal-footer justify-content-between">

                            <a type="button" href="{{ url('/bstentry') }}" class="btn btn-default"
                                data-dismiss="modal">Close</a>
                            @if ($entryNow != null && $bstNow != null)
                                @if ($bstNow->status != 2 && $bstNow->status != 4 && $bstNow->status != 6)
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
                            <a type="button" href="{{ url('/bstentry') }}" class="btn btn-default"
                                data-dismiss="modal">Close</a>
                            @if ($entryNow != null && $bstNow != null)
                                @if ($bstNow->status != 2 && $bstNow->status != 4 && $bstNow->status != 6)
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
    {{-- BST --}}
    <script>
        $(document).ready(function() {

            let r3_value = 0;
            let r4_value = 0;
            let r5_value = 0;
            let r6_value = 0;
            let r7_value = 0;
            let r8_value = 0;
            let r9_value = 0;

            $('#r3,#r4,#r5').on('input', () => {
                r3_value = isNaN(parseInt($('#r3').val())) ? 0 : parseInt($('#r3').val());
                r4_value = isNaN(parseInt($('#r4').val())) ? 0 : parseInt($('#r4').val());
                r5_value = isNaN(parseInt($('#r5').val())) ? 0 : parseInt($('#r5').val());

                r6_value = (r3_value - r4_value + r5_value) < 0 ? 0 : (r3_value - r4_value + r5_value);

                $('#r6 ,#r7_khusus').val(r6_value);
                if (r6_value == 0) {
                    $('#akhirTriwulan').hide();
                    $('#r7').val(0);
                    $('#r8').val(0);
                    $('#r9').val(0);

                } else {
                    $('#akhirTriwulan').show();
                }
            });

            // $('#r7,#r8,#r9').on('input', () => {
            //     r7_value = isNaN(parseInt($('#r7').val())) ? 0 : parseInt($('#r7').val());
            //     r8_value = isNaN(parseInt($('#r8').val())) ? 0 : parseInt($('#r8').val());
            //     r9_value = isNaN(parseInt($('#r9').val())) ? 0 : parseInt($('#r9').val());

            //     let resultText = '';

            //     if ((r7_value + r8_value + r9_value) > r6_value) {
            //         resultText =
            //             `<p> Note: Total nilai R7, R8, dan R9 harus lebih kecil/ sama dengan ${r6_value}</p>`;
            //     }
            //     $('#result4').html(resultText);
            // })

        });
    </script>
@endsection
