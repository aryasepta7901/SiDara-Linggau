<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-light-lightblue elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
        {{-- <img src="{{ asset('img') }}/bps-logo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"> --}}
        <span class="brand-text font-weight-bold">SI-Dara</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ asset('template/dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2"
                    alt="User Image">
            </div>
            <div class="info">

                <a href="#" class="d-block">{{ Str::limit(auth()->user()->name, 20) }}</a>
            </div>
        </div>

        <!-- Sidebar Menu -->

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                                                    with font-awesome or any other icon font library -->

                @can('pcl')
                    <li class="nav-item">
                        <a href="{{ url('/entry') }}" class="nav-link {{ Request::is('entry*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>
                                Entry
                            </p>
                        </a>
                    </li>
                @endcan
                {{-- Akses Untuk Admin dinas --}}
                @can('dinas')
                    <li class="nav-item">
                        <a href="{{ url('/validasi/dinas') }}"
                            class="nav-link {{ Request::is('validasi*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>
                                validasi
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/users') }}" class="nav-link {{ Request::is('users*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users"></i>
                            <p>
                                users
                            </p>
                        </a>
                    </li>
                @endcan
                @can('bps')
                    <li class="nav-item">
                        <a href="{{ url('/validasi/bps') }}"
                            class="nav-link {{ Request::is('validasi*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>
                                Validasi
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/users') }}" class="nav-link {{ Request::is('users*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users"></i>
                            <p>
                                users
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/admin/entrysbs') }}"
                            class="nav-link {{ Request::is('admin*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-git"></i>
                            <p>
                                Admin
                            </p>
                        </a>
                    </li>
                @endcan
                <li class="nav-item">
                    <a href="{{ url('/profile') }}" class="nav-link {{ Request::is('profile*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user"></i>
                        <p>
                            Profile
                        </p>
                    </a>
                </li>




                <li class="nav-item">
                    <a href="{{ url('/logout') }}" class="nav-link ">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>
                            Logout
                        </p>
                    </a>
                </li>

            </ul>
        </nav>

        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
