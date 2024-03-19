<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Si-Dara | {{ $title }}</title>
    {{-- CSS Utama --}}
    <link rel="stylesheet" href="{{ asset('/css/style.css') }}">

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ asset('template/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('template/dist/css/adminlte.min.css') }}">

    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('template') }}/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet"
        href="{{ asset('template') }}/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="{{ asset('template') }}/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('template') }}/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="{{ asset('template') }}/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{ asset('template') }}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <link href="{{ asset('landingPage') }}/img/bps.png" rel="icon">
    <!-- jQuery -->
    <script src="{{ asset('template/plugins/jquery/jquery.min.js') }}"></script>
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/fixedheader/3.1.2/css/fixedHeader.dataTables.min.css">


    <style>
        .plain-button {
            background: none;
            border: none;
            padding: 0;
            font: inherit;
            cursor: pointer;
            color: #007bff;
        }
    </style>

</head>

<body
    class="hold-transition sidebar-mini {{ Request::is('tpi*') || Request::is('satker*') || Request::is('lke*') || Request::is('prov/evaluasi*') ? 'sidebar-collapse' : '' }}">
    <div class="wrapper">
        <!-- Preloader -->
        <div id="my-element" class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="{{ asset('img') }}/bps.png" alt="AdminLTELogo" height="80px">
        </div>

        {{-- Navbar --}}
        @include('layouts.backEnd.navbar')
        {{-- EndNavbar --}}

        {{-- Sidebar --}}
        @include('layouts.backEnd.sidebar')
        {{-- EndSidebar --}}
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div id="header" class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-12">
                            <h1 class="m-0">{{ $title }}</h1>
                        </div><!-- /.col -->
                        {{-- <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                @can('admin')
                                    <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                                @endcan
                                @isset($master)
                                    <li class="breadcrumb-item"><a href="{{ $link }}">{{ $master }}</a>
                                    </li>
                                @endisset
                                <li class="breadcrumb-item active">{{ $title }}</li>


                            </ol>
                        </div><!-- /.col --> --}}
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        {{-- Content --}}
                        @yield('content')
                        {{-- EndContent --}}

                    </div>
                    <!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->


        <!-- Main Footer -->
        <footer class="main-footer">
            <!-- To the right -->
            {{-- <div class="float-right d-none d-sm-inline">
                <b href="https://adminlte.io">SIDara</b>.
            </div> --}}
            <!-- Default to the left -->
            <div class="text-center copyright">
                &copy; Copyright <strong><span>Fungsi Integrasi Pengolahan Data (IPD) BPS Kota Lubuk
                        Linggau</span></strong>.
                2024
            </div>
        </footer>
    </div>
    <!-- ./wrapper -->

    {{-- Scroll to Top --}}
    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="fas fa-arrow-up"></i></a>
    <script src="{{ asset('') }}js/main.js"></script>


    {{-- Font Awesome updated --}}
    <script src="https://kit.fontawesome.com/48de642077.js" crossorigin="anonymous"></script>

    <!-- REQUIRED SCRIPTS -->

    <!-- Bootstrap 4 -->
    <script src="{{ asset('template/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('template/dist/js/adminlte.min.js') }}"></script>
    <!-- DataTables  & Plugins -->
    <script src="{{ asset('template') }}/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="{{ asset('template') }}/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="{{ asset('template') }}/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="{{ asset('template') }}/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="{{ asset('template') }}/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script src="{{ asset('template') }}/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
    <script src="{{ asset('template') }}/plugins/jszip/jszip.min.js"></script>
    <script src="{{ asset('template') }}/plugins/pdfmake/pdfmake.min.js"></script>
    <script src="{{ asset('template') }}/plugins/pdfmake/vfs_fonts.js"></script>
    <script src="{{ asset('template') }}/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
    <script src="{{ asset('template') }}/plugins/datatables-buttons/js/buttons.print.min.js"></script>
    <script src="{{ asset('template') }}/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="{{ asset('template') }}/plugins/sweetalert2/sweetalert2.min.js"></script>
    <!-- Select2 -->
    <script src="{{ asset('template') }}/plugins/select2/js/select2.full.min.js"></script>


    {{-- toast --}}
    <script>
        var Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });
        if (Session::has('success')) {
            Toast.fire({
                icon: 'success',
                title: "{{ Session::get('success') }}"
            })
        }
    </script>
    {{-- File input --}}
    <script>
        $('.custom-file input').change(function(e) {
            var files = [];
            for (var i = 0; i < $(this)[0].files.length; i++) {
                files.push($(this)[0].files[i].name);
            }
            $(this).next('.custom-file-label').html(files.join(', '));
        });
    </script>

    <script>
        $(function() {
            //Initialize Select2 Elements
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            })

            $("#example1").DataTable({
                "responsive": false,
                "lengthChange": false,
                "autoWidth": false,
                "paging": true,
                // "ordering": true,
                "info": false,
                // "autoWidth": false,
                // "buttons": ["csv", "excel"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
            $("#example2").DataTable({
                "responsive": false,
                "fixedHeader": true,
                "lengthChange": false,
                "autoWidth": false,
                "paging": false,
                // "ordering": true,
                "info": false,
                // "autoWidth": false,
                "buttons": ["excel"]
            }).buttons().container().appendTo('#example2_wrapper .col-md-6:eq(0)');
        });
    </script>
    <script>
        //script foto
        function bacaGambar(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#gambar_load').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        $('#preview_gambar').change(function() {
            bacaGambar(this);
        });
    </script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
    <script>
        $(document).ready(function() {
            // Mendapatkan nilai awal dari input #r4
            var initialValue = $('#r4').val();

            // Fungsi ini dijalankan saat nilai input berubah
            $('#r4').on('input', function() {
                // Mendapatkan nilai dari input
                var inputValue = $(this).val();
                // Membuat teks dengan tag <p> dan mengisi dengan nilai input
                var resultText = '<p> Note: Total nilai R5, R6 dan R7 harus lebih kecil/sama dengan ' +
                    inputValue +
                    ' </p>';
                // Menetapkan teks pada elemen dengan id 'result'
                $('#result').html(resultText);
                // Menetapkan nilai pada input readonly
                $('#r5Input').val(inputValue);
            });
            // Memastikan nilai awal diambil saat dokumen siap
            // dan mengisi elemen dengan id 'result' dengan nilai awal
            if (initialValue !== '') {
                var resultText = '<p> Note: Total nilai R5, R6 dan R7 harus lebih kecil/ sama dengan ' +
                    initialValue + ' </p>';
                $('#result').html(resultText);
                $('#r5Input').val(initialValue);
            } else {
                $('#result').html('');
            }
        });
    </script>

    {{-- <script>
        function showHideInput1() {
            var triggerInputValue = document.getElementById('input1').value;
            var conditionalInputDiv = document.getElementById('conditionalInputDiv1');


            // Jika nilai triggerInput tidak kosong, tampilkan conditionalInputDiv; jika tidak, sembunyikan.
            if (parseFloat(triggerInputValue) > 0) {
                conditionalInputDiv.style.display = 'block';
            } else {
                conditionalInputDiv.style.display = 'none';

            }
            // Simpan status ke sessionStorage
            sessionStorage.setItem('input1Status', conditionalInputDiv.style.display);
        }
        // Panggil fungsi ini saat halaman dimuat untuk memulihkan status
        function restoreInput1Status() {
            var conditionalInputDiv = document.getElementById('conditionalInputDiv1');

            // Dapatkan status dari sessionStorage
            var savedStatus = sessionStorage.getItem('input1Status');

            // Setel kembali status
            if (savedStatus) {
                conditionalInputDiv.style.display = savedStatus;
            }
            // Hapus status dari sessionStorage setelah dipulihkan
            sessionStorage.removeItem('input1Status');
        }



        function showHideInput2() {
            var triggerInputValue = document.getElementById('input2').value;
            var conditionalInputDiv = document.getElementById('conditionalInputDiv2');


            // Jika nilai triggerInput tidak kosong, tampilkan conditionalInputDiv; jika tidak, sembunyikan.
            if (triggerInputValue.trim() > 0) {
                conditionalInputDiv.style.display = 'block';

            } else {
                conditionalInputDiv.style.display = 'none';

            }
            sessionStorage.setItem('input2Status', conditionalInputDiv.style.display);

        }
        // Panggil fungsi ini saat halaman dimuat untuk memulihkan status
        function restoreInput2Status() {
            var conditionalInputDiv = document.getElementById('conditionalInputDiv2');

            // Dapatkan status dari sessionStorage
            var savedStatus = sessionStorage.getItem('input2Status');

            // Setel kembali status
            if (savedStatus) {
                conditionalInputDiv.style.display = savedStatus;
            }
            // Hapus status dari sessionStorage setelah dipulihkan
            sessionStorage.removeItem('input2Status');
        }

        // Panggil fungsi restoreInput1Status saat halaman dimuat
        window.onload = function() {
            restoreInput1Status();
            restoreInput2Status();
        };
    </script> --}}



    {{-- <script>
        function simpanKeLocalStorage() {
            var bulan = $('#bulan').val();
            var tahun = $('#tahun').val();

            // Membuat objek untuk disimpan di localStorage
            var dataBulanTahun = {
                bulan: bulan,
                tahun: tahun
            };

            // Mengonversi objek ke string JSON dan menyimpannya di localStorage
            localStorage.setItem('bulanTahun', JSON.stringify(dataBulanTahun));

            alert('Data berhasil disimpan di localStorage!');
        }
    </script> --}}
    <script>
        $(document).ready(function() {
            var tableContainer = $(".table-container");

            tableContainer.on("scroll", function() {
                var offset = $(this).scrollLeft();
                $(".sticky-column").css("transform", "translateX(" + offset + "px)");
            });
        });
    </script>
    <script>
        // Tambahkan script JavaScript untuk menangani perubahan isi tabel dan memperbarui gulirannya
        var tableContainer = document.getElementById('scrollTable');
        tableContainer.addEventListener('scroll', function() {
            // Logika penanganan scroll jika diperlukan
        });
    </script>
    {{-- <script>
        // Tambahkan script JavaScript untuk menangani scroll dan memperbarui posisi header
        var tableContainer = document.querySelector('.custom-scroll');
        var header = document.querySelector('.sticky-header');

        tableContainer.addEventListener('scroll', function() {
            header.style.transform = 'translateY(' + tableContainer.scrollTop + 'px)';
        });
    </script> --}}

</body>

</html>
