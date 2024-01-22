@extends('layouts.backEnd.main')

@section('content')
    <div class="col-lg-12">
        <div class="card">

        </div>
        <div class="card">

            <div class="card-body">
                <table id="example1" class="table table-bordered table-striped table-responsive">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>NIP</th>
                            <th style="width: 170px">Nama User</th>
                            <th style="width: 200px">Kecamatan</th>
                            <th>Aksi</th>
                        </tr>

                    </thead>
                    <tbody>
                        @foreach ($users as $value)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $value->id }}</td>
                                <td>{{ $value->name }}</td>
                                <td>
                                    @if ($value->kecamatan == null)
                                        <small class="badge badge-primary">Tidak Ada Wilayah Tugas</small>
                                    @else
                                        <small class="badge badge-info">{{ $value->kecamatan->kecamatan }}</small>
                                    @endif
                                    @if ($value->role == 'PCL')
                                        <small class="badge badge-primary">PCL</small>
                                    @elseif($value->role == 'PML')
                                        <small class="badge badge-primary">Admin BPS</small>
                                    @elseif($value->role == 'AD')
                                        <small class="badge badge-primary">Admin Dinas</small>
                                    @endif

                                </td>
                                <td>
                                    @if ($value->role == 'PCL')
                                        <button class="btn btn-primary btn-sm ml-2" data-toggle="modal"
                                            data-target="#kecamatan{{ $value->id }}"><i class="fa fa-pen">
                                            </i>
                                        </button>
                                    @endif

                                    <button class="btn btn-success btn-sm ml-2" data-toggle="modal"
                                        data-target="#defaultPass{{ $value->id }}"><i class="fa fa-lock">
                                        </i> </button>
                                    @if (auth()->user()->role == 'PML')
                                        <button class="btn btn-secondary btn-sm ml-2" data-toggle="modal"
                                            data-target="#role{{ $value->id }}"><i class="fa fa-eye">
                                            </i> </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach

                    </tbody>

                </table>

            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->

    </div>
    {{-- Modal --}}
    {{-- Edit --}}

    @foreach ($users as $value)
        <div class="modal fade" id="kecamatan{{ $value->id }}">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Edit Wilayah Tugas</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="post" action="{{ url('/users/' . $value->id) }}">
                        @csrf
                        @method('put')
                        <div class="modal-body">
                            <div class="form-group">
                                <label class="@error('kecamatan') text-danger  @enderror" for="kecamatan">Wilayah
                                    Tugas</label>
                                @error('kecamatan')
                                    <small class="badge badge-danger"> *{{ $message }}
                                    </small>
                                @enderror
                                <select class="form-control select2bs4" name="kecamatan">
                                    @if ($value->kec_id == null)
                                        <option value="">--Pilih Kecamatan--</option>
                                    @else
                                        <option value="{{ $value->kec_id }}">{{ $value->kecamatan->kecamatan }}
                                        </option>
                                    @endif
                                    @foreach ($kecamatan as $item)
                                        <option value="{{ $item->id }}">{{ $item->kecamatan }}</option>
                                    @endforeach
                                    <option value="0">Non Aktifkan</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <a type="button" class="btn btn-default" data-dismiss="modal">Close</a>
                            <button type="submit" name="role" value="role" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
    @endforeach

    {{-- Default Password --}}
    @foreach ($users as $value)
        <div class="modal fade" id="defaultPass{{ $value->id }}">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Ubah Password Jadikan Default Password</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="post" action="{{ url('/users/' . $value->id) }}">
                        @csrf
                        @method('put')
                        <div class="modal-body">
                            Anda Akan mengubah Password <b>{{ $value->name }}</b> menjadi passsword default Yaitu: <small
                                class="badge badge-info">Password1234</small>
                            <hr>
                            Harap Beritahukan <b>{{ $value->name }}</b> untuk login menggunakan password default dan
                            mengubah password pada menu profile
                        </div>
                        <div class="modal-footer justify-content-between">
                            <a type="button" class="btn btn-default" data-dismiss="modal">Close</a>
                            <button type="submit" name="defaultPass" value="defaultPass"
                                class="btn btn-primary">Ubah</button>
                        </div>
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
    @endforeach

    @if (auth()->user()->role == 'PML')
        @foreach ($users as $value)
            <div class="modal fade" id="role{{ $value->id }}">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Ubah Role Pengguna</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form method="post" action="{{ url('/users/') }}">
                            @csrf

                            <div class="modal-body">
                                <div class="form-group">
                                    <input type="hidden" value="{{ $value->id }}" name="user_id">
                                    <label class="@error('role') text-danger  @enderror" for="role">Role</label>
                                    @error('role')
                                        <small class="badge badge-danger"> *{{ $message }}
                                        </small>
                                    @enderror
                                    <select class="form-control select2bs4" name="role">
                                        <option value="AD">Admin Dinas</option>
                                        <option value="PML">Admin BPS</option>
                                        <option value="PCL">Pencacah Lapangan</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer justify-content-between">
                                <a type="button" class="btn btn-default" data-dismiss="modal">Close</a>
                                <button type="submit" name="adminRole" value="adminRole"
                                    class="btn btn-primary">Ubah</button>
                            </div>
                        </form>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
        @endforeach
    @endif


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
                text: "{{ implode(' , ', $errors->all()) }}",
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
