@extends('layouts.backEnd.main')

@section('content')
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">

            </div>
            <div class="card-body">
                <form method="post" action="{{ url('/bstentry') }}">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="tanaman_id" value="{{ $tanaman->id }}">
                        <input type="hidden" name="r8" value="{{ $bstNow->r8 }}">
                        <input type="hidden" name="min_harga" value="{{ $tanaman->min_harga }}">
                        <input type="hidden" name="max_harga" value="{{ $tanaman->max_harga }}">

                        @if ($bstNow->r8 != 0)
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="conditionalInput">R10: Produksi ({{ $tanaman->satuan_produksi }})
                                        </label>
                                        <input type="number" class="form-control @error('r10') is-invalid  @enderror"
                                            id="r10" name="r10"
                                            @if ($bstNow->r10 != 0) value="{{ old('r10', $bstNow->r10) }}" @else  value="{{ old('r10') }}" @endif
                                            step=".01" min="0">
                                        @error('r10')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                        <p class="text-info">Note :Isian dalam bilangan desimal dengan 2 angka di
                                            belakang koma</p>

                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="r11">R11: Harga Jual Petani Per Kilogram (Rupiah)</label>
                                        <input type="number" class="form-control @error('r11') is-invalid  @enderror"
                                            id="r11" name="r11"
                                            @if ($bstNow->r11 != 0) value="{{ old('r11', $bstNow->r11) }}" @else  value="{{ old('r11') }}" @endif
                                            min="{{ $tanaman->min_harga }}" max="{{ $tanaman->max_harga }}">
                                        @error('r11')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                        {{-- <p class="text-info">Note: Harga Jual Harus diantara {{ $tanaman->min_harga }} dan
                                            {{ $tanaman->max_harga }} (Rupiah)</p> --}}
                                    </div>
                                </div>
                            </div>
                        @endif

                        <hr>

                        <div class="form-group">
                            <label for="note">R12: Keterangan</label>
                            <input type="text" class="form-control @error('note') is-invalid  @enderror" id="note"
                                name="note" value="{{ old('note', $bstNow->note) }}">
                            @error('note')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="modal-footer justify-content-between">
                        <a type="button" href="{{ url('/bstentry/pertLuas/' . $tanaman->id) }}" class="btn btn-default"
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
