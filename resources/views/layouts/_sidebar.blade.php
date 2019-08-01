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
                        <span class="counter" id="counter">0</span>
                    </sup>
                </a>
                <div class="dropdown-menu notifications-dropdown-menu">
                    <ul class="notifications-container" id="showotorisasi">

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
                <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true"
                   aria-expanded="false">
                    <div class="img" style="background-image: url('https://i.mydramalist.com/O5OvYc.jpg')"></div>
                    <span class="name"> {{ \App\d_username::getName() }} </span>
                </a>
                <div class="dropdown-menu profile-dropdown-menu" aria-labelledby="dropdownMenu1">
                    <a class="dropdown-item" href="{{route('profile')}}">
                        <i class="fa fa-user icon"></i> Profile </a>

                    <a class="dropdown-item" href="{{route('pengaturanpengguna.index')}}">
                        <i class="fa fa-gear icon"></i> Settings </a>
                    <div class="dropdown-divider"></div>

                    <a class="dropdown-item" href="{{ route('auth.logout') }}"
                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                        <i class="fa fa-power-off icon"></i> Logout </a>
                    <form id="logout-form" action="{{ route('auth.logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
                </div>
            </li>
        </ul>
    </div>
</header>

<?php $sidebar = App\Http\Controllers\AksesUser::aksesSidebar() ?>

<aside class="sidebar">
    <div class="sidebar-container">
        <div class="sidebar-header">
            <div class="brand">
                <img src="{{asset('assets/img/mutiaraberlian.svg')}}" height="45px" width="45px">

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
                @if ($sidebar[0]->ua_read == 'Y' || $sidebar[1]->ua_read == 'Y' || $sidebar[2]->ua_read == 'Y' || $sidebar[3]->ua_read == 'Y' ||
                $sidebar[4]->ua_read == 'Y' || $sidebar[5]->ua_read == 'Y'|| $sidebar[6]->ua_read == 'Y' || $sidebar[49]->ua_read == 'Y' ||
                $sidebar[50]->ua_read == 'Y' || $sidebar[51]->ua_read == 'Y' || $sidebar[52]->ua_read == 'Y' || $sidebar[53]->ua_read == 'Y')
                    <li class="{{Request::is('masterdatautama/*') || Request::is('keuangan/masterdatautama/*') ? 'active open' : ''}}">
                        <a href="#">
                            <i class="fa fa-th-large"></i> <span class="menu-title">Master Data Utama</span>
                            <i class="fa arrow"></i>
                        </a>
                        <ul class="sidebar-nav">
                            @if ($sidebar[1]->ua_read == 'Y')
                                <li class="{{Request::is('masterdatautama/datapegawai/*') ? 'active' : ''}}">
                                    <a href="{{route('pegawai.index')}}"> Master Pegawai</a>
                                </li>
                            @endif
                            @if ($sidebar[2]->ua_read == 'Y')
                                <li class="{{Request::is('masterdatautama/produk/*') ? 'active' : ''}}">
                                    <a href="{{route('dataproduk.index')}}"> Master Produk</a>
                                </li>
                            @endif

                            @if ($sidebar[3]->ua_read == 'Y')
                                <li class="{{Request::is('masterdatautama/harga/*') ? 'active' : ''}}">
                                    <a href="{{route('dataharga.index')}}"> Master Harga</a>
                                </li>
                            @endif
                            @if ($sidebar[4]->ua_read == 'Y')
                                <li class="{{Request::is('masterdatautama/suplier/*') || Request::is('masterdatautama/suplier') ? 'active' : ''}}">
                                    <a href="{{route('suplier.index')}}">Master Suplier</a>
                                </li>
                            @endif
                            @if ($sidebar[5]->ua_read == 'Y')
                                <li class="{{Request::is('masterdatautama/cabang/*') ? 'active' : ''}}">
                                    <a href="{{ route('cabang.index') }}">Master Cabang</a>
                                </li>
                            @endif
                            @if ($sidebar[6]->ua_read == 'Y')
                                <li class="{{Request::is('masterdatautama/agen/*') ? 'active' : ''}}">
                                    <a href="{{ route('agen.index')}}">Master Agen</a>
                                </li>
                            @endif
                            @if ($sidebar[49]->ua_read == 'Y')
                                <li class="{{Request::is('masterdatautama/member/*') ? 'active' : ''}}">
                                    <a href="{{ route('member.index')}}">Master Member</a>
                                </li>
                            @endif
                            @if ($sidebar[50]->ua_read == 'Y')
                            <li class="{{Request::is('masterdatautama/ekspedisi/*') || Request::is('masterdatautama/ekspedisi') ? 'active' : ''}}">
                                <a href="{{route('ekspedisi.index')}}"> Master Ekspedisi</a>
                            </li>
                            @endif
                            @if ($sidebar[51]->ua_read == 'Y')
                            <li class="{{Request::is('keuangan/masterdatautama/akun-utama') || Request::is('keuangan/masterdatautama/akun-utama/*') ? 'active' : ''}}">
                                <a href="{{route('keuangan.akun-utama.index')}}">Master COA Utama</a>
                            </li>
                            @endif
                            @if ($sidebar[52]->ua_read == 'Y')
                            <li class="{{Request::is('keuangan/masterdatautama/akun-keuangan') || Request::is('keuangan/masterdatautama/akun-keuangan/*') ? 'active' : ''}}">
                                <a href="{{route('keuangan.akun.index')}}">Master COA Keuangan</a>
                            </li>
                            @endif
                            @if ($sidebar[53]->ua_read == 'Y')
                            <li class="{{Request::is('masterdatautama/masterpembayaran/*') || Request::is('masterdatautama/masterpembayaran') ? 'active' : ''}}">
                                <a href="{{route('masterdatautama.masterpembayaran')}}">Master Pembayaran</a>
                            </li>
                            @endif
                            {{-- @if ($sidebar[53]->ua_read == 'Y') --}}
                            <li class="{{Request::is('masterdatautama/mastercashflow/*') || Request::is('masterdatautama/mastercashflow') ? 'active' : ''}}">
                                <a href="{{route('masterdatautama.mastercashflow')}}">Master Cashflow</a>
                            </li>
                            {{-- @endif --}}
                        </ul>
                    </li>
                @endif
            <!-- END MASTER DATA UTAMA -->
                <!-- AKTIVITAS PRODUKSI -->
                @if ($sidebar[7]->ua_read == 'Y' || $sidebar[8]->ua_read == 'Y' || $sidebar[9]->ua_read == 'Y' || $sidebar[10]->ua_read == 'Y' || $sidebar[11]->ua_read == 'Y')
                    <li class="{{Request::is('produksi/*') ? 'active open' : ''}}">
                        <a href="#">
                            <i class="fa fa-product-hunt"></i> <span class="menu-title">Aktivitas Produksi</span>
                            <i class="fa arrow"></i>
                        </a>
                        <ul class="sidebar-nav">
                            @if ($sidebar[8]->ua_read == 'Y')
                                <li class="{{Request::is('produksi/orderproduksi/*') ? 'active' : ''}}">
                                    <a href="{{route('order.index')}}">Order Produksi</a>
                                </li>
                            @endif
                            @if ($sidebar[9]->ua_read == 'Y')
                                <li class="{{Request::is('produksi/penerimaanbarang/*') ? 'active' : ''}}">
                                    <a href="{{route('penerimaan.index')}}">Penerimaan Barang</a>
                                </li>
                            @endif
                            @if ($sidebar[10]->ua_read == 'Y')
                                <li class="{{Request::is('produksi/pembayaran/*') ? 'active' : ''}}">
                                    <a href="{{route('pembayaran.index')}}">Pembayaran</a>
                                </li>
                            @endif
                            @if ($sidebar[11]->ua_read == 'Y')
                                <li class="{{Request::is('produksi/returnproduksi/*') ? 'active' : ''}}">
                                    <a href="{{ route('return.index') }}">Return Produksi</a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif
            <!-- END AKTIVITAS PRODUKSI -->
                <!-- AKTIVITAS INVENTORY -->
                @if ($sidebar[12]->ua_read == 'Y' || $sidebar[13]->ua_read == 'Y' || $sidebar[14]->ua_read == 'Y' || $sidebar[15]->ua_read == 'Y' || $sidebar[16]->ua_read == 'Y')
                    <li class="{{Request::is('inventory/*') ? 'active open' : ''}}">
                        <a href="">
                            <i class="fa fa-desktop"></i><span class="menu-title"> Aktivitas Inventory</span>
                            <i class="fa arrow"></i>
                        </a>
                        <ul class="sidebar-nav">
                            @if ($sidebar[13]->ua_read == 'Y')
                                <li class="{{Request::is('inventory/barangmasuk/*') ? 'aktif open' : ''}}">
                                    <a href="{{route('barangmasuk.index')}}"> Pengelolaan Barang Masuk</a>
                                </li>
                            @endif
                            @if ($sidebar[14]->ua_read == 'Y')
                                <li class="{{Request::is('inventory/barangkeluar/*') ? 'aktif open' : ''}}">
                                    <a href="{{route('barangkeluar.index')}}"> Pengelolaan Barang Keluar</a>
                                </li>
                            @endif
                            @if ($sidebar[15]->ua_read == 'Y')
                                <li class="{{Request::is('inventory/distribusibarang/*') ? 'aktif open' : ''}}">
                                    <a href="{{route('distribusibarang.index')}}"> Pengelolaan Distribusi Barang</a>
                                </li>
                            @endif
                            @if ($sidebar[16]->ua_read == 'Y')
                                <li class="{{Request::is('inventory/manajemenstok/*') ? 'aktif open' : ''}}">
                                    <a href="{{route('manajemenstok.index')}}"> Pengelolaan Manajemen Stok</a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif
            <!-- END AKTIVITAS INVENTORY -->
                <!-- AKTIVITAS MARKETING -->
                @if ($sidebar[17]->ua_read == 'Y' || $sidebar[18]->ua_read == 'Y' || $sidebar[19]->ua_read == 'Y' || $sidebar[20]->ua_read == 'Y' || $sidebar[21]->ua_read == 'Y' || $sidebar[22]->ua_read == 'Y')
                    <li class="{{Request::is('marketing/*') ? 'active open' : ''}}">
                        <a href="#">
                            <i class="fa fa-shopping-cart"></i><span class="menu-title"> Aktivitas Marketing</span>
                            <i class="fa arrow"></i>
                        </a>
                        <ul class="sidebar-nav">
                            @if ($sidebar[18]->ua_read == 'Y')
                                <li class="{{Request::is('marketing/manajemenmarketing/*') ? 'active' : ''}}">
                                    <a href="{{route('mngmarketing.index')}}">Manajemen Marketing</a>
                                </li>
                            @endif
                            {{-- <li class="{{Request::is('marketing/targetrealisasipenjualan/*') ? 'active' : ''}}">
                                <a href="{{route('targetrealisasi.index')}}">Target dan Realisasi Penjualan</a>
                            </li> --}}
                            @if ($sidebar[19]->ua_read == 'Y')
                                <li class="{{Request::is('marketing/penjualanpusat/*') ? 'active' : ''}}">
                                    <a href="{{route('penjualanpusat.index')}}">Manajemen Penjualan Pusat</a>
                                </li>
                            @endif
                            @if ($sidebar[20]->ua_read == 'Y')
                                <li class="{{Request::is('marketing/konsinyasipusat/*') ? 'active' : ''}}">
                                    <a href="{{route('konsinyasipusat.index')}}">Manajemen Konsinyasi Pusat</a>
                                </li>
                            @endif
                            @if ($sidebar[21]->ua_read == 'Y')
                                <li class="{{Request::is('marketing/marketingarea/*') ? 'active' : ''}}">
                                    <a href="{{ route('marketingarea.index') }}">Manajemen Marketing Area</a>
                                </li>
                            @endif
                            @if ($sidebar[22]->ua_read == 'Y')
                                <li class="{{Request::is('marketing/agen/*') ? 'active' : ''}}">
                                    <a href="{{ route('manajemenagen.index') }}">Manajemen Agen</a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif
            <!-- END AKTIVITAS MARKETING -->
                <!-- AKTIVITAS SDM -->
                @if ($sidebar[23]->ua_read == 'Y' || $sidebar[24]->ua_read == 'Y' || $sidebar[25]->ua_read == 'Y' || $sidebar[26]->ua_read == 'Y' || $sidebar[27]->ua_read == 'Y')
                    <li class="{{Request::is('sdm/*') ? 'active open' : ''}}">
                        <a href="">
                            <i class="fa fa-group"></i><span class="menu-title"> Aktivitas SDM</span>
                            <i class="fa arrow"></i>
                        </a>
                        <ul class="sidebar-nav">
                            @if ($sidebar[24]->ua_read == 'Y')
                                <li class="{{Request::is('sdm/prosesrekruitmen/*') ? 'active' : ''}}">
                                    <a href="{{route('rekruitmen.index')}}">Proses Rekruitmen</a>
                                </li>
                            @endif
                            @if ($sidebar[25]->ua_read == 'Y')
                                <li class="{{Request::is('sdm/kinerjasdm/*') ? 'active' : ''}}">
                                    <a href="{{route('kinerjasdm.index')}}">Kelola Kinerja SDM</a>
                                </li>
                            @endif
                            @if ($sidebar[26]->ua_read == 'Y')
                                <li class="{{Request::is('sdm/absensisdm/*') ? 'active' : ''}}">
                                    <a href="{{route('absensisdm.index')}}">Kelola Abesensi SDM</a>
                                </li>
                            @endif
                            @if ($sidebar[27]->ua_read == 'Y')
                                <li class="{{Request::is('sdm/penggajian/*') ? 'active' : ''}}">
                                    <a href="{{ route('penggajian.index') }}">Kelola Penggajian</a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif
            <!-- END AKTIVITAS SDM -->
                @if ($sidebar[28]->ua_read == 'Y' || $sidebar[29]->ua_read == 'Y' || $sidebar[30]->ua_read == 'Y')
                    <li class="">
                        <a href="">
                            <i class="fa fa-money"></i><span class="menu-title"> Budgeting</span>
                            <i class="fa arrow"></i>
                        </a>
                        <ul class="sidebar-nav">
                            @if ($sidebar[29]->ua_read == 'Y')
                                <li>
                                    <a href="#">Manajemen Perencanaan</a>
                                </li>
                            @endif
                            @if ($sidebar[30]->ua_read == 'Y')
                                <li>
                                    <a href="#">Manajemen Penganggaran</a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif

                @if ($sidebar[31]->ua_read == 'Y' || $sidebar[32]->ua_read == 'Y' || $sidebar[33]->ua_read == 'Y' || $sidebar[34]->ua_read == 'Y' || $sidebar[35]->ua_read == 'Y' || $sidebar[36]->ua_read == 'Y')
                    <li class="{{Request::is('keuangan/*') && !Request::is('keuangan/masterdatautama/*') ? 'active open' : ''}}">
                        <a href="#">
                            <i class="fa fa-usd"></i><span class="menu-title"> Keuangan</span>
                            <i class="fa arrow"></i>
                        </a>
                        <ul class="sidebar-nav">
                            @if ($sidebar[32]->ua_read == 'Y')
                                <li>
                                    <a href="#">Dashboard</a>
                                </li>
                            @endif
                            @if ($sidebar[33]->ua_read == 'Y')
                                <li class="{{Request::is('keuangan/inputtransaksi/*') ? 'active' : ''}}">
                                    <a href="{{route('inputtransaksi.index')}}">Manajemen Input Transaksi</a>
                                </li>
                            @endif
                            @if ($sidebar[55]->ua_read == 'Y')
                                <li class="{{Request::is('keuangan/penerimaanpiutang/*') || Request::is('keuangan/penerimaanpiutang') ? 'active' : ''}}">
                                    <a href="{{route('penerimaanpiutang.index')}}">Penerimaan Piutang</a>
                                </li>
                            @endif
                            @if ($sidebar[34]->ua_read == 'Y')
                                <li class="{{Request::is('marketing/penjualanpusat/*') ? 'active' : ''}}">
                                    <a href="{{route('penjualanpusat.index')}}">Manajemen Hutang Piutang</a>
                                </li>
                            @endif
                            @if ($sidebar[35]->ua_read == 'Y')
                                <li class="{{Request::is('marketing/kosinyasipusat/*') ? 'active' : ''}}">
                                    <a href="{{route('penjualanpusat.index')}}">Manajemen Pajak</a>
                                </li>
                            @endif
                            @if ($sidebar[36]->ua_read == 'Y')
                                <li class="{{Request::is('keuangan/laporankeuangan/*') ? 'active' : ''}}">
                                    <a href="{{ route('laporankeuangan.index') }}">Laporan Keuangan</a>
                                </li>
                            @endif
                            @if ($sidebar[37]->ua_read == 'Y' || $sidebar[38]->ua_read == 'Y' || $sidebar[39]->ua_read == 'Y' || $sidebar[40]->ua_read == 'Y' || $sidebar[41]->ua_read == 'Y' || $sidebar[42]->ua_read == 'Y' || $sidebar[43]->ua_read == 'Y')
                                <li>
                                    <a href="#">
                                        <i class="fa fa-bar-chart-o"></i>
                                        Analisa
                                        <i class="fa arrow"></i>
                                    </a>
                                    <ul class="sidebar-nav">
                                        @if ($sidebar[37]->ua_read == 'Y')
                                            <li class="{{Request::is('marketing/agen/*') ? 'active' : ''}}">
                                                <a href="{{ route('manajemenagen.index') }}">Analisa Progress Terhadap
                                                    Perencanaan</a>
                                            </li>
                                        @endif
                                        @if ($sidebar[38]->ua_read == 'Y')
                                            <li class="{{Request::is('marketing/agen/*') ? 'active' : ''}}">
                                                <a href="{{ route('manajemenagen.index') }}">Analisa Net Profit Terhadap
                                                    OCF</a>
                                            </li>
                                        @endif
                                        @if ($sidebar[39]->ua_read == 'Y')
                                            <li class="{{Request::is('marketing/agen/*') ? 'active' : ''}}">
                                                <a href="{{ route('manajemenagen.index') }}">Analisa Pertumbuhan Aset
                                                    Terhadap ETA</a>
                                            </li>
                                        @endif
                                        @if ($sidebar[40]->ua_read == 'Y')
                                            <li class="{{Request::is('marketing/agen/*') ? 'active' : ''}}">
                                                <a href="{{ route('manajemenagen.index') }}">Analisa Cashflow</a>
                                            </li>
                                        @endif
                                        @if ($sidebar[41]->ua_read == 'Y')
                                            <li class="{{Request::is('marketing/agen/*') ? 'active' : ''}}">
                                                <a href="{{ route('manajemenagen.index') }}">Analisa Common Size</a>
                                            </li>
                                        @endif
                                        @if ($sidebar[42]->ua_read == 'Y')
                                            <li class="{{Request::is('marketing/agen/*') ? 'active' : ''}}">
                                                <a href="{{ route('manajemenagen.index') }}">Analisa Ratio
                                                    Liquiditas</a>
                                            </li>
                                        @endif
                                        @if ($sidebar[43]->ua_read == 'Y')
                                            <li class="{{Request::is('marketing/agen/*') ? 'active' : ''}}">
                                                <a href="{{ route('manajemenagen.index') }}">Analisa Return on
                                                    Equity</a>
                                            </li>
                                        @endif
                                    </ul>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif
            <!-- Notifikasi & Authorization -->
                @if ($sidebar[44]->ua_read == 'Y' || $sidebar[45]->ua_read == 'Y' || $sidebar[46]->ua_read == 'Y')
                    <li class="{{ Request::is('notifikasiotorisasi/*') ? 'active open' : ''}}">
                        <a href="">
                            <i class="fa fa-bell"></i><span class="menu-title"> Notifikasi & Otorisasi</span>
                            <i class="fa arrow"></i>
                        </a>
                        <ul class="sidebar-nav">
                            @if ($sidebar[46]->ua_read == 'Y')
                                <li class="{{Request::is('notifikasiotorisasi/notifikasi/*') ? 'open' : ''}}">
                                    <a href="{{route('notifikasi')}}">Notifikasi</a>
                                </li>
                            @endif
                            @if ($sidebar[45]->ua_read == 'Y')
                                <li class="{{Request::is('notifikasiotorisasi/otorisasi/*') ? 'open' : ''}}">
                                    <a href="{{route('otorisasi')}}">Otorisasi</a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif
            <!-- END Notifikasi & Authorization -->
                <!-- AKTIVITAS Setting -->
                @if ($sidebar[47]->ua_read == 'Y')
                    <li class="{{Request::is('pengaturan/*') || Request::is('keuangan/pengaturan/*') ? 'active open' : ''}}">
                        <a href="">
                            <i class="fa fa-cog"></i><span class="menu-title"> Pengaturan</span>
                            <i class="fa arrow"></i>
                        </a>
                        <ul class="sidebar-nav">
                            @if ($sidebar[48]->ua_read == 'Y')
                            <li class="{{Request::is('pengaturan/pengaturanpengguna/*') ? 'active' : ''}}">
                                <a href="{{ route('pengaturanpengguna.index') }}">Pengaturan Pengguna</a>
                            </li>
                            @endif
                            @if ($sidebar[49]->ua_read == 'Y')
                            <li class="{{Request::is('keuangan/pengaturan/hierarki-akun') ? 'active' : ''}}">
                                <a href="{{ route('keuangan.hierarki_akun.index') }}">Pengaturan Hierarki COA</a>
                            </li>
                            @endif
                            <li class="{{Request::is('keuangan/pengaturan/coa-pembukuan') ? 'active' : ''}}">
                                <a href="{{ route('keuangan.pembukuan.index') }}">Pengaturan COA Pembukuan</a>
                            </li>
                        </ul>
                    </li>
                @endif
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
                                <div class="col-4"></div>
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
