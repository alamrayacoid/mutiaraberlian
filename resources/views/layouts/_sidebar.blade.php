<header class="header">
    <div class="header-block header-block-collapse">
        <!-- <button class="collapse-btn icon" id="sidebar-collapse-btn">
            <i class="fa fa-bars"></i>
        </button> -->
    </div>
    <div class="menuThree collapse-btn icon" id="sidebar-collapse-btn">
        <span></span>
        <span></span>
        <span></span>
    </div>
    <!-- <div class="header-block header-block-buttons">
        <a href="https://github.com/modularcode/modular-admin-html" class="btn btn-sm header-btn">
            <i class="fa fa-github-alt"></i>
            <span>View on GitHub</span>
        </a>
        <a href="https://github.com/modularcode/modular-admin-html/stargazers" class="btn btn-sm header-btn">
            <i class="fa fa-star"></i>
            <span>Star Us</span>
        </a>
        <a href="https://github.com/modularcode/modular-admin-html/releases" class="btn btn-sm header-btn">
            <i class="fa fa-cloud-download"></i>
            <span>Download .zip</span>
        </a>
    </div> -->
    <div class="header-block header-block-nav">
        <ul class="nav-profile">
            <li class="notifications new">
            <a href="" data-toggle="dropdown">
                <i class="fa fa-bell-o" title="Notifikasi"></i>
                <sup>
                    <span class="counter">8</span>
                </sup>
            </a>
                <div class="dropdown-menu notifications-dropdown-menu">
                    <ul class="notifications-container">
                        <li>
                            <a href="" class="notification-item">
                                <div class="body-col">
                                    <p>
                                        <span class="accent">Zack Alien</span> pushed new commit:
                                        <span class="accent">Fix page load performance issue</span>. </p>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="" class="notification-item">
                                <div class="body-col">
                                    <p>
                                        <span class="accent">Amaya Hatsumi</span> started new task:
                                        <span class="accent">Dashboard UI design.</span>. </p>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="" class="notification-item">
                                <div class="body-col">
                                    <p>
                                        <span class="accent">Andy Nouman</span> deployed new version of
                                        <span class="accent">NodeJS REST Api V3</span>
                                    </p>
                                </div>
                            </a>
                        </li>
                    </ul>
                    <footer>
                        <ul>
                            <li>
                                <a href=""> View All </a>
                            </li>
                        </ul>
                    </footer>
                </div>
            </li>
            <li class="notifications new">
                <a href="" data-toggle="dropdown">

                <i><img src="{{ asset('assets/img/author-sign.png') }}" alt="" title="Otorisasi"></i>
                <sup>
                    <span class="counter">6</span>
                </sup>
                </a>
                <div class="dropdown-menu notifications-dropdown-menu">
                    <ul class="notifications-container">
                        <li>
                            <a href="" class="notification-item">
                                <div class="body-col">
                                    <p>
                                        <span class="accent">Alien</span> pushed new commit:
                                        <span class="accent">Fix page load performance issue</span>. </p>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="" class="notification-item">
                                <div class="body-col">
                                    <p>
                                        <span class="accent">Amaya Hatsumi</span> started new task:
                                        <span class="accent">Dashboard UI design.</span>. </p>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="" class="notification-item">
                                <div class="body-col">
                                    <p>
                                        <span class="accent">Andy Nouman</span> deployed new version of
                                        <span class="accent">NodeJS REST Api V3</span>
                                    </p>
                                </div>
                            </a>
                        </li>
                    </ul>
                    <footer>
                        <ul>
                            <li>
                                <a href=""> View All </a>
                            </li>
                        </ul>
                    </footer>
                </div>
            </li>
            <li class="profile dropdown">
                <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                    <div class="img" style="background-image: url('https://avatars3.githubusercontent.com/u/3959008?v=3&s=40')"> </div>
                    <span class="name"> {{ \App\d_username::getName() }} </span>
                </a>
                <div class="dropdown-menu profile-dropdown-menu" aria-labelledby="dropdownMenu1">
                    <a class="dropdown-item" href="#">
                        <i class="fa fa-user icon"></i> Profile </a>
                    <a class="dropdown-item" href="#">
                        <i class="fa fa-bell icon"></i> Notifications </a>
                    <a class="dropdown-item" href="#">
                        <i class="fa fa-gear icon"></i> Settings </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                        <i class="fa fa-power-off icon"></i> Logout </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
                </div>
            </li>
        </ul>
    </div>
</header>
<aside class="sidebar">
    <div class="sidebar-container">
        <div class="sidebar-header">
            <div class="brand">
                <img src="{{asset('assets/img/cv-mutiaraberlian-icon.png')}}" height="45px" width="45px">
                {{-- <div class="logo">
                    <span class="l l1"></span>
                    <span class="l l2"></span>
                    <span class="l l3"></span>
                    <span class="l l4"></span>
                    <span class="l l5"></span>
                </div>  --}}
                <div class="text-brand">Mutiara Berlian</div>
            </div>
        </div>
        <nav class="menu" id="sidebar">
            <ul class="sidebar-menu metismenu" id="sidebar-menu">
                <div id="search-container-bro" class="header-block header-block-search">
                    <form role="search">
                        <div class="input-container">
                            <div class="input-container-prepend">
                                <button class="btn btn-sm btn-secondary" id="btn-search-menu" type="button">
                                    <i class="fa fa-search icon-search"></i>
                                </button>
                            </div>
                            <input type="search" placeholder="Cari Menu" class="input-search" id="filterInput">
                            <button type="button" class="btn btn-secondary btn-sm d-none" id="btn-reset">
                                <i class="fa fa-times"></i>
                            </button>
                            <div class="underline"></div>
                        </div>
                    </form>
                </div>
                <li class="{{Request::is('home') ? 'active' : ''  || Request::is('/') ? 'active' : ''}}">
                    <a href="{{url('/')}}" class="dashboard">
                        <i class="fa fa-home"></i> <span class="menu-title">Dashboard </span>
                    </a>
                </li>
                <!-- MASTER DATA UTAMA -->
                <li class="{{Request::is('masterdatautama/*') ? 'active open' : ''}}">
                    <a href="#">
                        <i class="fa fa-th-large"></i> <span class="menu-title">Master Data Utama</span>
                        <i class="fa arrow"></i>
                    </a>
                    <ul class="sidebar-nav">
                        <li class="{{Request::is('masterdatautama/datapegawai/*') ? 'active' : ''}}">
                            <a href="{{route('pegawai.index')}}"> Master Pegawai</a>
                        </li>
                        <li class="{{Request::is('masterdatautama/produk/*') ? 'active' : ''}}">
                            <a href="{{route('dataproduk.index')}}"> Master Produk</a>
                        </li>
                        <!-- <li class="{{Request::is('masterdatautama/variasisatuanproduk/*') ? 'active' : ''}}">
                            <a href="{{route('variasisatuan.index')}}"> Variasi Satuan Produk</a>
                        </li> -->
                        <li class="{{Request::is('masterdatautama/harga/*') ? 'active' : ''}}">
                            <a href="{{route('dataharga.index')}}"> Master Harga</a>
                        </li>
                        <li class="{{Request::is('masterdatautama/suplier/*') ? 'active' : ''}}">
                            <a href="{{route('suplier.index')}}">Master Suplier</a>
                        </li>
                        <li class="{{Request::is('masterdatautama/cabang/*') ? 'active' : ''}}">
                            <a href="{{ route('cabang.index') }}">Master Cabang</a>
                        </li>
                        <li class="{{Request::is('masterdatautama/agen/*') ? 'active' : ''}}">
                            <a href="{{ route('agen.index')}}">Master Agen</a>
                        </li>
                       {{--  <li class="{{Request::is('masterdatautama/datasatuan/*') ? 'active' : ''}}">
                            <a href="{{route('datasatuan.index')}}">Master Satuan</a>
                        </li> --}}
                    </ul>
                </li>
                <!-- END MASTER DATA UTAMA -->
                <!-- AKTIVITAS PRODUKSI -->
                <li class="{{Request::is('produksi/*') ? 'active open' : ''}}">
                    <a href="#">
                        <i class="fa fa-product-hunt"></i> <span class="menu-title">Aktivitas Produksi</span>
                        <i class="fa arrow"></i>
                    </a>
                    <ul class="sidebar-nav">
                        <li class="{{Request::is('produksi/orderproduksi/*') ? 'active' : ''}}">
                            <a href="{{route('order.index')}}">Order Produksi</a>
                        </li>
                        <li class="{{Request::is('produksi/penerimaanbarang/*') ? 'active' : ''}}">
                            <a href="{{route('penerimaan.index')}}">Penerimaan Barang</a>
                        </li>
                        <li class="{{Request::is('produksi/pembayaran/*') ? 'active' : ''}}">
                            <a href="{{route('pembayaran.index')}}">Pembayaran</a>
                        </li>
                        <li class="{{Request::is('produksi/returnproduksi/*') ? 'active' : ''}}">
                            <a href="{{ route('return.index') }}">Return Produksi</a>
                        </li>
                    </ul>
                </li>
                <!-- END AKTIVITAS PRODUKSI -->
                <!-- AKTIVITAS INVENTORY -->
                <li class="{{Request::is('inventory/*') ? 'active open' : ''}}">
                    <a href="">
                        <i class="fa fa-desktop"></i><span class="menu-title"> Aktivitas Inventory</span>
                        <i class="fa arrow"></i>
                    </a>
                    <ul class="sidebar-nav">
                        <li class="{{Request::is('inventory/barangmasuk/*') ? 'aktif open' : ''}}">
                            <a href="{{route('barangmasuk.index')}}"> Pengelolaan Barang Masuk</a>
                        </li>
                        <li class="{{Request::is('inventory/barangkeluar/*') ? 'aktif open' : ''}}">
                            <a href="{{route('barangkeluar.index')}}"> Pengelolaan Barang Keluar</a>
                        </li>
                        <li class="{{Request::is('inventory/distribusibarang/*') ? 'aktif open' : ''}}">
                            <a href="{{route('distribusibarang.index')}}"> Pengelolaan Distribusi Barang</a>
                        </li>
                        <li class="{{Request::is('inventory/manajemenstok/*') ? 'aktif open' : ''}}">
                            <a href="{{route('manajemenstok.index')}}"> Pengelolaan Manajemen Stok</a>
                        </li>
                    </ul>
                </li>
                <!-- END AKTIVITAS INVENTORY -->
                <!-- AKTIVITAS SDM -->
                <li class="{{Request::is('sdm/*') ? 'active open' : ''}}">
                    <a href="">
                        <i class="fa fa-group"></i><span class="menu-title"> Aktivitas SDM</span>
                        <i class="fa arrow"></i>
                    </a>
                    <ul class="sidebar-nav">
                        <li class="{{Request::is('sdm/prosesrekruitmen/*') ? 'active' : ''}}">
                            <a href="{{route('rekruitmen.index')}}">Proses Rekruitmen</a>
                        </li>
                        <li class="{{Request::is('sdm/kinerjasdm/*') ? 'active' : ''}}">
                            <a href="{{route('kinerjasdm.index')}}">Kelola Kinerja SDM</a>
                        </li>
                        <li class="{{Request::is('sdm/absensisdm/*') ? 'active' : ''}}">
                            <a href="{{route('absensisdm.index')}}">Kelola Abesensi SDM</a>
                        </li>
                        <li class="{{Request::is('sdm/penggajian/*') ? 'active' : ''}}">
                            <a href="{{ route('penggajian.index') }}">Kelola Penggajian</a>
                        </li>
                    </ul>
                </li>
                <!-- END AKTIVITAS SDM -->
                <!-- AKTIVITAS MARKETING -->
                <li class="{{Request::is('marketing/*') ? 'active open' : ''}}">
                    <a href="#">
                        <i class="fa fa-shopping-cart"></i><span class="menu-title"> Aktivitas Marketing</span>
                        <i class="fa arrow"></i>
                    </a>
                    <ul class="sidebar-nav">
                        <li class="{{Request::is('marketing/manajemenmarketing/*') ? 'active' : ''}}">
                            <a href="{{route('mngmarketing.index')}}">Manajemen Marketing</a>
                        </li>
                        <li class="{{Request::is('marketing/targetrealisasipenjualan/*') ? 'active' : ''}}">
                            <a href="{{route('targetrealisasi.index')}}">Target dan Realisasi Penjualan</a>
                        </li>
                        <li class="{{Request::is('marketing/penjualanpusat/*') ? 'active' : ''}}">
                            <a href="{{route('penjualanpusat.index')}}">Penjualan Pusat</a>
                        </li>
                        <li class="{{Request::is('marketing/konsinyasipusat/*') ? 'active' : ''}}">
                            <a href="{{route('konsinyasipusat.index')}}">Konsinyasi Pusat</a>
                        </li>
                        <li class="{{Request::is('marketing/marketingarea/*') ? 'active' : ''}}">
                            <a href="{{ route('marketingarea.index') }}">Marketing Area</a>
                        </li>
                        <li class="{{Request::is('marketing/agen/*') ? 'active' : ''}}">
                            <a href="{{ route('mngagen.index') }}">Manajemen Agen</a>
                        </li>
                    </ul>
                </li>
                <!-- END AKTIVITAS MARKETING -->
                <li class="{{Request::is('keuangan/*') ? 'active open' : ''}}">
                    <a href="#">
                        <i class="fa fa-usd"></i><span class="menu-title"> Keuangan</span>
                        <i class="fa arrow"></i>
                    </a>
                    <ul class="sidebar-nav">
                        <li class="{{Request::is('keuangan/inputtransaksi/*') ? 'active' : ''}}">
                            <a href="{{route('inputtransaksi.index')}}">Manajemen Input Transaksi</a>
                        </li>
                        <li class="{{Request::is('marketing/penjualanpusat/*') ? 'active' : ''}}">
                            <a href="{{route('penjualanpusat.index')}}">Manajemen Hutang Piutang</a>
                        </li>
                        <li class="{{Request::is('marketing/kosinyasipusat/*') ? 'active' : ''}}">
                            <a href="{{route('penjualanpusat.index')}}">Manajemen Pajak</a>
                        </li>
                        <li class="{{Request::is('keuangan/laporankeuangan/*') ? 'active' : ''}}">
                            <a href="{{ route('laporankeuangan.index') }}">Laporan Keuangan</a>
                        </li>
                        <li>
                            <a href="#">
                                <i class="fa fa-bar-chart-o"></i>
                                Analisa
                                <i class="fa arrow"></i>
                            </a>
                            <ul class="sidebar-nav">
                                <li class="{{Request::is('marketing/agen/*') ? 'active' : ''}}">
                                    <a href="{{ route('mngagen.index') }}">Analisa Progress Terhadap Perencanaan</a>
                                </li>

                                <li class="{{Request::is('marketing/agen/*') ? 'active' : ''}}">
                                    <a href="{{ route('mngagen.index') }}">Analisa Net Profit Terhadap OCF</a>
                                </li>

                                <li class="{{Request::is('marketing/agen/*') ? 'active' : ''}}">
                                    <a href="{{ route('mngagen.index') }}">Analisa Pertumbuhan Aset Terhadap ETA</a>
                                </li>

                                <li class="{{Request::is('marketing/agen/*') ? 'active' : ''}}">
                                    <a href="{{ route('mngagen.index') }}">Analisa Cashflow</a>
                                </li>
                                <li class="{{Request::is('marketing/agen/*') ? 'active' : ''}}">
                                    <a href="{{ route('mngagen.index') }}">Analisa Common Size</a>
                                </li>

                                <li class="{{Request::is('marketing/agen/*') ? 'active' : ''}}">
                                    <a href="{{ route('mngagen.index') }}">Analisa Ratio Liquiditas</a>
                                </li>
                                <li class="{{Request::is('marketing/agen/*') ? 'active' : ''}}">
                                    <a href="{{ route('mngagen.index') }}">Analisa Return on Equity</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <!-- Notifikasi & Authorization -->
                <li class="{{ Request::is('notifikasiotorisasi/*') ? 'active open' : ''}}">
                    <a href="">
                        <i class="fa fa-bell"></i><span class="menu-title"> Notifikasi & Otorisasi</span>
                        <i class="fa arrow"></i>
                    </a>
                    <ul class="sidebar-nav">
                        <li class="{{Request::is('notifikasiotorisasi/otorisasi/*') ? 'open' : ''}}">
                            <a href="{{route('otorisasi')}}">Otorisasi</a>
                        </li>
                        <li class="{{Request::is('notifikasiotorisasi/notifikasi/*') ? 'open' : ''}}">
                            <a href="{{route('notifikasi')}}">Notifikasi</a>
                        </li>
                    </ul>
                </li>
                <!-- END Notifikasi & Authorization -->
                <!-- AKTIVITAS Setting -->
                <li class="{{Request::is('pengaturan/*') ? 'active open' : ''}}">
                    <a href="">
                        <i class="fa fa-cog"></i><span class="menu-title"> Pengaturan</span>
                        <i class="fa arrow"></i>
                    </a>
                    <ul class="sidebar-nav">
                        <li class="{{Request::is('pengaturan/pengaturanpengguna/*') ? 'active' : ''}}">
                            <a href="{{ route('pengaturanpengguna.index') }}">Pengaturan Pengguna</a>
                        </li>
                    </ul>
                </li>
                <!-- END AKTIVITAS Setting -->
            </ul>
        </nav>
    </div>
    <footer class="sidebar-footer">
        <ul class="sidebar-menu metismenu" id="customize-menu">
            <li>
                <ul>
                    <li class="customize">
                        <div class="customize-item">
                            <div class="row customize-header">
                                <div class="col-4"> </div>
                                <div class="col-4">
                                    <label class="title">fixed</label>
                                </div>
                                <div class="col-4">
                                    <label class="title">static</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-4">
                                    <label class="title">Sidebar:</label>
                                </div>
                                <div class="col-4">
                                    <label>
                                        <input class="radio" type="radio" name="sidebarPosition" value="sidebar-fixed">
                                        <span></span>
                                    </label>
                                </div>
                                <div class="col-4">
                                    <label>
                                        <input class="radio" type="radio" name="sidebarPosition" value="">
                                        <span></span>
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-4">
                                    <label class="title">Header:</label>
                                </div>
                                <div class="col-4">
                                    <label>
                                        <input class="radio" type="radio" name="headerPosition" value="header-fixed">
                                        <span></span>
                                    </label>
                                </div>
                                <div class="col-4">
                                    <label>
                                        <input class="radio" type="radio" name="headerPosition" value="">
                                        <span></span>
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-4">
                                    <label class="title">Footer:</label>
                                </div>
                                <div class="col-4">
                                    <label>
                                        <input class="radio" type="radio" name="footerPosition" value="footer-fixed">
                                        <span></span>
                                    </label>
                                </div>
                                <div class="col-4">
                                    <label>
                                        <input class="radio" type="radio" name="footerPosition" value="">
                                        <span></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="customize-item">
                            <ul class="customize-colors">
                                <li>
                                    <span class="color-item color-red" data-theme="red"></span>
                                </li>
                                <li>
                                    <span class="color-item color-orange" data-theme="orange"></span>
                                </li>
                                <li>
                                    <span class="color-item color-green active" data-theme=""></span>
                                </li>
                                <li>
                                    <span class="color-item color-seagreen" data-theme="seagreen"></span>
                                </li>
                                <li>
                                    <span class="color-item color-blue" data-theme="blue"></span>
                                </li>
                                <li>
                                    <span class="color-item color-purple" data-theme="purple"></span>
                                </li>
                            </ul>
                        </div>
                    </li>
                </ul>
                <!-- <a href="#">
                    <i class="fa fa-cog"></i> Customize </a>
            </li> -->
        </ul>
    </footer>
</aside>
<div class="sidebar-overlay" id="sidebar-overlay"></div>
<div class="sidebar-mobile-menu-handle" id="sidebar-mobile-menu-handle"></div>
<div class="mobile-menu-handle"></div>
