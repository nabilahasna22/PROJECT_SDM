<div class="sidebar">
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
            <img src="{{ asset('image/profile.jpg') }}" alt="Profile Picture" class="brand-image img-circle elevation-3" style="opacity: .8; width: 35px; height: 35px;">
        </div>
        <div class="info">
            <!-- Mengambil data nama dan NIP pengguna dari Auth -->
            <a class="d-block" href="#">{{ Auth::user()->nip }} - {{ Auth::user()->nama }}</a>
        </div>        
    </div>

    <!-- SidebarSearch Form -->
    <div class="form-inline mt-2">
        <div class="input-group" data-widget="sidebar-search">
            <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
            <div class="input-group-append">
                <button class="btn btn-sidebar">
                    <i class="fas fa-search fa-fw"></i>
                </button>
            </div>
        </div>
    </div>
    <!-- Sidebar Menu -->
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-item">
                <a href="{{ url('/') }}" class="nav-link {{ ($activeMenu == 'dashboard') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-tachometer-alt"></i>
                    <p>Dashboard</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ url('/profile') }}" class="nav-link {{ $activeMenu == 'profile' ? 'active' : '' }}">
                    <i class="nav-icon fas fa-user"></i>
                    <p>Edit Profile</p>
                </a>
            </li>

            <!-- Admin: Akses semua menu -->
            @if(auth()->user()->level_id == 3)
                
                <li class="nav-header">Rekap Kegiatan</li>
                <li class="nav-item">
                    <a href="{{ url('/progres') }}" class="nav-link {{ $activeMenu == 'progres' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-chart-line"></i>
                        <p>Progres Kegiatan</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('/') }}" class="nav-link {{ $activeMenu == '' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-chart-line"></i>
                        <p>Rekap Partisipasi</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('/') }}" class="nav-link {{ $activeMenu == '' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-chart-line"></i>
                        <p>Manajemen Agenda</p>
                    </a>
                </li>
            <li class="nav-header">Manajemen Pengguna</li>
            <li class="nav-item">
                <a href="{{ url('/level') }}" class="nav-link {{ ($activeMenu == 'level') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-layer-group"></i>
                    <p>Level User</p>
                </a>
            </li> 
            <li class="nav-item">
                <a href="{{ url('/user') }}" class="nav-link {{ ($activeMenu == 'user') ? 'active' : '' }}">
                    <i class="nav-icon far fa-user"></i>
                    <p>Data User</p>
                </a>
            </li>

            <!-- Data Kegiatan -->
            <li class="nav-header">Manajemen Kegiatan</li>
            <li class="nav-item">
                <a href="{{ url('/kategori') }}" class="nav-link {{ $activeMenu == 'kategori' ? 'active' : '' }}">
                    <i class="nav-icon fas fa-list"></i>
                    <p>Kategori Kegiatan</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ url('/periode') }}" class="nav-link {{ $activeMenu == 'periode' ? 'active' : '' }}">
                    <i class="nav-icon fas fa-list"></i>
                    <p>Periode Kegiatan</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ url('/kegiatan') }}" class="nav-link {{ $activeMenu == 'kegiatan' ? 'active' : '' }}">
                    <i class="nav-icon fas fa-calendar-alt"></i>
                    <p>Kelola Kegiatan</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ url('/managejabatan') }}" class="nav-link {{ $activeMenu == 'managejabatan' ? 'active' : '' }}">
                    <i class="nav-icon fas fa-calendar-alt"></i>
                    <p>Manajemen Jabatan</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ url('/detailkegiatan') }}" class="nav-link {{ $activeMenu == 'detailkegiatan' ? 'active' : '' }}">
                    <i class="nav-icon fas fa-book-open"></i>
                    <p>Detail Kegiatan</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ url('/statistik_dosen') }}" class="nav-link {{ ($activeMenu == 'statistik_dosen') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-user-tie"></i>
                    <p>Statistik Dosen</p>
                </a>
            </li>
            @endif
            
            <!-- Dosen: Akses hanya Data Kegiatan, Dashboard, dan Edit Profile -->
            @if(auth()->user()->level_id == '2')
                <li class="nav-header">Informasi Kegiatan</li>
                <li class="nav-item">
                    <a href="{{ url('/daftar_kegiatan') }}" class="nav-link {{ $activeMenu == 'daftar_kegiatan' ? 'active' : '' }}">
                        <i class="nav-icon far fa-calendar"></i>
                        <p>Daftar Kegiatan</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('/progres') }}" class="nav-link {{ $activeMenu == 'progres' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-chart-line"></i>
                        <p>Progres Kegiatan</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('/') }}" class="nav-link {{ $activeMenu == '' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-chart-line"></i>
                        <p>Kegiatan Diikuti</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('/kegiatan') }}" class="nav-link {{ $activeMenu == 'kegiatan' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-chart-line"></i>
                        <p>Manajemen Agenda</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('/agenda_progres') }}" class="nav-link {{ $activeMenu == 'agenda_progres' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-calendar-check"></i>
                        <p>Update Progress Agenda</p>
                    </a>
                </li>
            @endif

            <!-- Pimpinan: Akses semua menu kecuali Manajemen Pengguna dan Kategori Kegiatan -->
            @if(auth()->user()->level_id == '1')
                li class="nav-header">Draft Surat Tugas</li>
                <li class="nav-item">
                    <a href="{{ url('/laporan') }}" class="nav-link {{ $activeMenu == 'laporan' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-file-alt"></i>
                        <p>Laporan Kegiatan</p>
                    </a>
                </li> 
                <li class="nav-header">Data Pengguna</li>
            <li class="nav-item">
                <a href="{{ url('/user') }}" class="nav-link {{ ($activeMenu == 'user') ? 'active' : '' }}">
                    <i class="nav-icon far fa-user"></i>
                    <p>Data User</p>
                </a>
            </li>
                <li class="nav-header">Rekap Kegiatan</li>
                <li class="nav-item">
                    <a href="{{ url('/daftar_kegiatan') }}" class="nav-link {{ $activeMenu == 'daftar_kegiatan' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-calendar-alt"></i>
                        <p>Daftar Kegiatan</p>
                    </a>
                </li>
            <!-- Data Kegiatan -->
            <li class="nav-header">Kinerja Dosen</li>
            <li class="nav-item">
                <a href="{{ url('/statistik_dosen') }}" class="nav-link {{ ($activeMenu == 'statistik_dosen') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-user-tie"></i>
                    <p>Statistik Dosen</p>
                </a>
            </li>
            @endif

            <!-- Logout: Akses untuk semua pengguna -->
            <li class="nav-header">Logout</li>
            <li class="nav-item">
                <a href="{{ url('logout') }}" class="nav-link"
                   style="background-color: red; color: white;"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="nav-icon fas fa-sign-out-alt"></i>
                    <p>Logout</p>
                </a>
                <form id="logout-form" action="{{ url('logout') }}" method="GET" style="display: none;">
                    @csrf
                </form>
            </li>
        </ul>
    </nav>
</div>
