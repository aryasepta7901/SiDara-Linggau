<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-light-lightblue elevation-4">
    <!-- Brand Logo -->
    <a href="" class="brand-link">
        {{-- <img src="{{ asset('img') }}/bps-logo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"> --}}
        <span class="brand-text font-weight-bold">SIDARA Linggau</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ asset('img/default.jpg') }}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">

                <a href="{{ url('profile') }}" class="d-block">{{ Str::limit(auth()->user()->name, 20) }}</a>
            </div>
        </div>

        <!-- Sidebar Menu -->

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                                                    with font-awesome or any other icon font library -->
                <li class="nav-item">
                    <a href="{{ url('/dashboard') }}" class="nav-link {{ Request::is('dashboard*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
                @can('pcl')
                    <li
                        class="nav-item {{ Request::is('sbs*') || Request::is('tbf*') || Request::is('bst*') || Request::is('th*') ? 'menu-open' : '' }}">
                        <a href="#"
                            class="nav-link {{ Request::is('sbs*') || Request::is('tbf*') || Request::is('bst*') || Request::is('th*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-file-import"></i>
                            <p>
                                Entry
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ url('/sbsentry') }}" class="nav-link {{ Request::is('sbs*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-leaf"></i>
                                    <p>
                                        SBS
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('/bstentry') }}"
                                    class="nav-link {{ Request::is('bst*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-leaf"></i>
                                    <p>
                                        BST
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('/tbfentry') }}"
                                    class="nav-link {{ Request::is('tbf*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-leaf"></i>
                                    <p>
                                        TBF
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('/thentry') }}" class="nav-link {{ Request::is('th*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-leaf"></i>
                                    <p>
                                        TH
                                    </p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan
                {{-- Akses Untuk Admin dinas --}}
                @can('dinas')
                    <li
                        class="nav-item {{ Request::is('sbsvalidasi*') || Request::is('tbfvalidasi*') || Request::is('bstvalidasi*') || Request::is('thvalidasi*') ? 'menu-open' : '' }}">
                        <a href="#"
                            class="nav-link {{ Request::is('sbsvalidasi*') || Request::is('tbfvalidasi*') || Request::is('bstvalidasi*') || Request::is('thvalidasi*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-check"></i>
                            <p>
                                Validasi
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ url('/sbsvalidasi/dinas') }}"
                                    class="nav-link {{ Request::is('sbsvalidasi*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-leaf"></i>
                                    <p>
                                        SBS
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('/bstvalidasi/dinas') }}"
                                    class="nav-link {{ Request::is('bstvalidasi*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-leaf"></i>
                                    <p>
                                        BST
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('/tbfvalidasi/dinas') }}"
                                    class="nav-link {{ Request::is('tbfvalidasi*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-leaf"></i>
                                    <p>
                                        TBF
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('/thvalidasi/dinas') }}"
                                    class="nav-link {{ Request::is('thvalidasi*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-leaf"></i>
                                    <p>
                                        TH
                                    </p>
                                </a>
                            </li>
                        </ul>
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
                    <li
                        class="nav-item {{ Request::is('sbsvalidasi*') || Request::is('tbfvalidasi*') || Request::is('bstvalidasi*') || Request::is('thvalidasi*') ? 'menu-open' : '' }}">
                        <a href="#"
                            class="nav-link {{ Request::is('sbsvalidasi*') || Request::is('tbfvalidasi*') || Request::is('bstvalidasi*') || Request::is('thvalidasi*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-chart-pie"></i>
                            <p>
                                Validasi
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ url('/sbsvalidasi/bps') }}"
                                    class="nav-link {{ Request::is('sbsvalidasi*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-check"></i>
                                    <p>
                                        SBS
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('/bstvalidasi/bps') }}"
                                    class="nav-link {{ Request::is('bstvalidasi*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-check"></i>
                                    <p>
                                        BST
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('/tbfvalidasi/bps') }}"
                                    class="nav-link {{ Request::is('tbfvalidasi*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-check"></i>
                                    <p>
                                        TBF
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('/thvalidasi/bps') }}"
                                    class="nav-link {{ Request::is('thvalidasi*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-check"></i>
                                    <p>
                                        TH
                                    </p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/users') }}" class="nav-link {{ Request::is('users*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users"></i>
                            <p>
                                users
                            </p>
                        </a>
                    </li>
                    {{-- <li class="nav-item">
                        <a href="{{ url('/admin/entrysbs') }}"
                            class="nav-link {{ Request::is('admin*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-git"></i>
                            <p>
                                Admin
                            </p>
                        </a>
                    </li> --}}
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
