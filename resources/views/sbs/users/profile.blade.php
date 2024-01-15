@extends('layouts.backEnd.main')

@section('content')
    <div class="col-lg-12">
        <div class="card">

        </div>
        <div class="card">
            <form method="post" action="/profile">
                @csrf
                <p class="text-center text-bold my-3">Profile</p>

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="nip"> NIP
                                </label>
                                <input type="text" class="form-control @error('nip') is-invalid  @enderror"
                                    id="nip" name="nip" value="{{ old('nip', $user->id) }}" readonly>
                                @error('nip')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="name"> Nama Lengkap
                                </label>
                                <input type="text" class="form-control @error('name') is-invalid  @enderror"
                                    id="name" name="name" value="{{ old('name', $user->name) }}">
                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="email"> Email
                                </label>
                                <input type="text" class="form-control @error('email') is-invalid  @enderror"
                                    id="email" name="email" value="{{ old('email', $user->email) }}">
                                @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                    </div>

                </div>
                <!-- /.card-body -->
                <div class="card-footer justify-content-between">
                    <button type="submit" name="changeProfile" value="changeProfile"
                        class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
        <div class="card">
            <form method="post" action="/profile">
                @csrf
                <div class="card-body">


                    <p class="text-center text-bold my-3">Ubah Password</p>

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="passLama"> Password Lama
                                </label>
                                <input type="text"
                                    class="form-control @error('passLama') is-invalid  @enderror  @if (Session::has('error1')) is-invalid @endif"
                                    id="passLama" name="passLama" value="{{ old('passLama') }}">
                                @error('passLama')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                                @if (Session::has('error1'))
                                    <span class=" text-danger">
                                        {{ Session::get('error1') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="passBaru"> Password Baru
                                </label>
                                <input type="text" class="form-control @error('passBaru') is-invalid  @enderror"
                                    id="passBaru" name="passBaru" value="{{ old('passBaru') }}">
                                @error('passBaru')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="confirmPassBaru">Konfirmasi Password Baru
                                </label>
                                <input type="text" class="form-control @error('confirmPassBaru') is-invalid  @enderror"
                                    id="confirmPassBaru" name="confirmPassBaru" value="{{ old('confirmPassBaru') }}">
                                @error('confirmPassBaru')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer justify-content-between">
                    <button type="submit" name="changePass" value="changePass" class="btn btn-primary">Ubah
                        Password</button>
                </div>
            </form>
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

    @if (Session::has('success'))
        <script>
            Toast.fire({
                icon: 'success',
                title: "{{ Session::get('success') }}"
            })
        </script>
    @endif
@endsection
