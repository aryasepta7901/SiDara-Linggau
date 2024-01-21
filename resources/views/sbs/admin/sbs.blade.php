@extends('layouts.backEnd.main')

@section('content')
    <div class="col-lg-12">

        <div class="card">
            <div class="card-header">
                Status:
                <ul>
                    <li style="display: inline">0: Belum Entry;</li>
                    <li style="display: inline">1: Sudah Entry;</li>
                    <li style="display: inline">2: Terkirim;</li>
                    <li style="display: inline">3: Revisi Dinas;</li>
                    <li style="display: inline">4: Dinas Setuju;</li>
                    <li style="display: inline">5: Revisi BPS;</li>
                    <li style="display: inline">6: BPS Setuju;</li>
                </ul>
                Status Tanaman:
                <ul>
                    <li style="display: inline">0: Tanaman Ada</li>
                    <li style="display: inline">1: Tanaman Habis</li>
                    <li style="display: inline">2: Tanaman Baru</li>
                </ul>
            </div>
            <div class="card-body">
                <table id="example1" class="table table-bordered table-striped table-responsive">
                    <thead>
                        <tr>
                            <th>tanaman</th>
                            <th>r4</th>
                            <th>r5</th>
                            <th>r6</th>
                            <th>r7</th>
                            <th>r8</th>
                            <th>r9</th>
                            <th>r10</th>
                            <th>r11</th>
                            <th>r12</th>
                            <th>Keterangan</th>
                            <th>Komentar Dinas</th>
                            <th>Komentar BPS</th>
                            <th>Status</th>
                            <th>Status Tanaman</th>
                        </tr>

                    </thead>
                    <tbody>
                        @foreach ($SBS as $value)
                            <tr>
                                <td>{{ $value->tanaman->nama_tanaman }}</td>
                                <td>{{ $value->r4 }}</td>
                                <td>{{ $value->r5 }}</td>
                                <td>{{ $value->r6 }}</td>
                                <td>{{ $value->r7 }}</td>
                                <td>{{ $value->r8 }}</td>
                                <td>{{ $value->r9 }}</td>
                                <td>{{ $value->r10 }}</td>
                                <td>{{ $value->r11 }}</td>
                                <td>{{ $value->r12 }}</td>
                                <td>{{ $value->note }}</td>
                                <td>{{ $value->catatan_dinas }}</td>
                                <td>{{ $value->catatan_BPS }}</td>
                                <td>{{ $value->status }}</td>
                                <td>{{ $value->status_tanaman }}</td>
                                {{-- <td>
                                    <button class="btn btn-primary btn-sm ml-2" data-toggle="modal"
                                        data-target="#entry{{ $value->id }}"><i class="fa fa-pen">
                                        </i>
                                    </button>
                                    <button class="btn btn-danger btn-sm ml-2" data-toggle="modal"
                                        data-target="#entry{{ $value->id }}"><i class="fa fa-trash">
                                        </i> </button>
                                </td> --}}
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
                <a type="button" class="btn btn-default" href="/admin/entrysbs">Close</a>

            </div>
        </div>
        <!-- /.card -->
    </div>
    {{-- @foreach ($entrySBS as $value)
        <div class="modal fade" id="entry{{ $value->id }}">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Edit EntrySBS</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="post" action="/admin/entrysbs/{{ $value->id }}">
                        @csrf
                        @method('put')
                        <div class="modal-body">
                            <div class="form-group">
                                <label class="@error('status') text-danger  @enderror" for="status">Status Entry</label>
                                @error('status')
                                    <small class="badge badge-danger"> *{{ $message }}
                                    </small>
                                @enderror
                                <select class="form-control select2bs4" name="status">
                                    <option value="{{ $value->status }}">{{ $value->status }}</option>
                                    <option value="0">0</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
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
    @endforeach --}}



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
