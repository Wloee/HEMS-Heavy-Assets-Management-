    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif
            min-height: 120vh;
            padding-top: 75px;
            background: linear-gradient(150deg, #f5f7fa 0%, #c3cfe2 100%);
        }

        .custom-navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(25px) saturate(180%);
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
            padding: 0 clamp(20px, 3vw, 40px);
            display: flex;
            align-items: center;
            height: 105px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12), 0 2px 6px rgba(0, 0, 0, 0.08);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .custom-navbar.scrolled {
            height: 65px;
            background: rgba(255, 255, 255, 0.98);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .custom-navbar-brand {
            display: flex;
            align-items: center;
            margin-right: clamp(24px, 3vw, 40px);
            text-decoration: none;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .custom-navbar-brand:hover {
            transform: scale(1.03);
        }

        .custom-brand-icon {
            width: clamp(32px, 3vw, 36px);
            height: clamp(32px, 3vw, 36px);
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 800;
            font-size: clamp(12px, 1.2vw, 14px);
            margin-right: clamp(8px, 1vw, 12px);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
            transition: all 0.3s ease;
        }

        .custom-brand-icon:hover {
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }

        .custom-brand-text {
            color: #1e293b;
            font-weight: 800;
            font-size: clamp(16px, 1.5vw, 20px);
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: -0.3px;
        }

        .custom-navbar-nav {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
            flex: 1;
            gap: clamp(4px, 0.8vw, 8px);
            align-items: center;
            flex-wrap: wrap;
        }

        .custom-nav-item {
            position: relative;
        }

        .custom-nav-link {
            display: flex;
            align-items: center;
            padding: clamp(6px, 1vw, 10px) clamp(10px, 1.2vw, 16px);
            color: #475569;
            text-decoration: none;
            font-size: clamp(11px, 0.9vw, 13px);
            font-weight: 600;
            border-radius: 10px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            white-space: nowrap;
            position: relative;
            overflow: hidden;
        }

        .custom-nav-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg,
                transparent,
                rgba(255, 255, 255, 0.3),
                transparent
            );
            transition: left 0.5s ease;
        }

        .custom-nav-link:hover::before {
            left: 100%;
        }

        .custom-nav-link:hover {
            color: #667eea;
            background: rgba(255, 255, 255, 0.9);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.2);
            border-color: rgba(102, 126, 234, 0.3);
        }

        .custom-nav-link.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
            transform: translateY(-1px);
            border-color: transparent;
        }

        .custom-dropdown-arrow {
            margin-left: clamp(4px, 0.6vw, 8px);
            font-size: clamp(8px, 0.7vw, 10px);
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .custom-nav-item.show .custom-dropdown-arrow {
            transform: rotate(180deg);
        }

        .custom-dropdown-menu {
            position: absolute;
            top: calc(100% + 8px);
            left: 50%;
            transform: translateX(-50%) scale(0.9) translateY(-8px);
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(25px) saturate(180%);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12);
            min-width: clamp(180px, 18vw, 220px);
            opacity: 0;
            visibility: hidden;
            padding: 8px;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 1001;
        }

        .custom-nav-item:hover .custom-dropdown-menu,
        .custom-nav-item.show .custom-dropdown-menu,
        .custom-user-menu.show .custom-dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateX(-50%) scale(1) translateY(0);
        }

        /* User dropdown specific positioning */
        .custom-user-menu .custom-dropdown-menu {
            left: auto;
            right: 0;
            transform: translateX(0) scale(0.9) translateY(-8px);
        }

        .custom-user-menu.show .custom-dropdown-menu {
            transform: translateX(0) scale(1) translateY(0);
        }

        .custom-dropdown-item {
            display: block;
            padding: clamp(8px, 1vw, 12px) clamp(12px, 1.2vw, 16px);
            color: #475569;
            text-decoration: none;
            font-size: clamp(10px, 0.9vw, 12px);
            font-weight: 500;
            border-radius: 10px;
            margin: 4px 0;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .custom-dropdown-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg,
                transparent,
                rgba(102, 126, 234, 0.1),
                transparent
            );
            transition: left 0.4s ease;
        }

        .custom-dropdown-item:hover::before {
            left: 100%;
        }

        .custom-dropdown-item:hover {
            color: #667eea;
            background: rgba(102, 126, 234, 0.1);
            transform: translateX(4px);
            box-shadow: 0 2px 6px rgba(102, 126, 234, 0.12);
        }

        .custom-navbar-right {
            margin-left: auto;
            display: flex;
            align-items: center;
        }

        .custom-user-menu {
            display: flex;
            align-items: center;
            padding: clamp(6px, 1vw, 10px) clamp(12px, 1.5vw, 16px);
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .custom-user-menu::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg,
                transparent,
                rgba(255, 255, 255, 0.4),
                transparent
            );
            transition: left 0.5s ease;
        }

        .custom-user-menu:hover::before {
            left: 100%;
        }

        .custom-user-menu:hover {
            background: rgba(255, 255, 255, 0.95);
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(102, 126, 234, 0.2);
            border-color: rgba(102, 126, 234, 0.3);
        }

        .custom-user-avatar {
            width: clamp(24px, 2.2vw, 28px);
            height: clamp(24px, 2.2vw, 28px);
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: clamp(9px, 0.9vw, 11px);
            margin-right: clamp(6px, 1vw, 10px);
            border: 2px solid rgba(255, 255, 255, 0.9);
            box-shadow: 0 2px 8px rgba(102, 126, 234, 0.2);
        }

        .custom-user-name {
            color: #1e293b;
            font-size: clamp(11px, 1vw, 13px);
            font-weight: 600;
            margin-right: clamp(4px, 0.8vw, 8px);
        }

        /* Mobile hamburger menu */
        .mobile-menu-toggle {
            display: none;
            flex-direction: column;
            cursor: pointer;
            padding: 8px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .mobile-menu-toggle:hover {
            background: rgba(102, 126, 234, 0.1);
        }

        .hamburger-line {
            width: 24px;
            height: 3px;
            background: #475569;
            margin: 2px 0;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 2px;
        }

        .mobile-menu-toggle.active .hamburger-line:nth-child(1) {
            transform: rotate(45deg) translate(6px, 6px);
        }

        .mobile-menu-toggle.active .hamburger-line:nth-child(2) {
            opacity: 0;
        }

        .mobile-menu-toggle.active .hamburger-line:nth-child(3) {
            transform: rotate(-45deg) translate(6px, -6px);
        }

        /* Content area for demo */
        .content {
            padding: 40px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .demo-card {
            background: white;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        /* Enhanced responsive design */
        @media (max-width: 1440px) {
            .custom-navbar {
                padding: 0 clamp(20px, 3vw, 36px);
            }

            .custom-navbar-brand {
                margin-right: clamp(24px, 4vw, 48px);
            }

            .custom-navbar-nav {
                gap: clamp(6px, 1vw, 12px);
            }
        }

        @media (max-width: 1200px) {
            .custom-navbar-nav {
                gap: clamp(4px, 0.8vw, 8px);
            }

            .custom-nav-link {
                padding: clamp(8px, 1vw, 12px) clamp(12px, 1.5vw, 18px);
                font-size: clamp(12px, 1vw, 14px);
            }

            .custom-dropdown-menu {
                min-width: clamp(180px, 18vw, 220px);
            }
        }

            @media (max-width: 992px) {
            .custom-navbar {
                height: 80px;
                padding: 0 20px;
            }

            .custom-navbar-nav {
                display: none;
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: rgba(255, 255, 255, 0.98);
                backdrop-filter: blur(25px);
                flex-direction: column;
                padding: 20px;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
                border-radius: 0 0 20px 20px;
                gap: 8px;
            }

            .custom-navbar-nav.mobile-active {
                display: flex;
            }

            .custom-nav-item {
                width: 100%;
            }

            .custom-nav-link {
                width: 100%;
                justify-content: space-between;
                padding: 16px 20px;
                font-size: 16px;
            }

            .custom-dropdown-menu {
                position: static;
                transform: none;
                opacity: 1;
                visibility: visible;
                box-shadow: none;
                border: none;
                background: rgba(102, 126, 234, 0.05);
                margin-top: 8px;
                border-radius: 12px;
                display: none;
            }

            .custom-nav-item.show .custom-dropdown-menu {
                display: block;
            }

            /* User dropdown on mobile */
            .custom-user-menu .custom-dropdown-menu {
                position: absolute;
                right: 0;
                left: auto;
                top: 100%;
                background: rgba(255, 255, 255, 0.98);
                backdrop-filter: blur(25px);
                display: none;
            }

            .custom-user-menu.show .custom-dropdown-menu {
                display: block;
            }

            .mobile-menu-toggle {
                display: flex;
            }

            .custom-navbar-right {
                margin-left: 20px;
            }

            .custom-brand-icon {
                width: 36px;

        @media (max-width: 768px) {
            .custom-navbar {
                height: 60px;
                padding: 0 12px;
            }

            body {
                padding-top: 60px;
            }

            .custom-navbar.scrolled {
                height: 55px;
            }

            .custom-navbar-brand {
                margin-right: 12px;
            }

            .custom-brand-icon {
                width: 26px;
                height: 26px;
                font-size: 10px;
                margin-right: 6px;
            }

            .custom-brand-text {
                font-size: 16px;
            }

            .custom-user-menu {
                padding: 6px 10px;
            }

            .custom-user-avatar {
                width: 22px;
                height: 22px;
                font-size: 8px;
            }

            .custom-user-name {
                font-size: 10px;
            }
        }

        @media (max-width: 480px) {
            .custom-navbar {
                height: 55px;
                padding: 0 10px;
            }

            body {
                padding-top: 55px;
            }

            .custom-navbar.scrolled {
                height: 50px;
            }

            .custom-brand-text {
                display: none;
            }

            .custom-user-name {
                display: none;
            }

            .custom-user-menu {
                padding: 6px;
            }

            .custom-brand-icon {
                width: 24px;
                height: 24px;
                font-size: 9px;
            }
        }px)
         {
            .custom-navbar {
                height: 70px;
                padding: 0 16px;
            }

            .custom-navbar-brand {
                margin-right: 16px;
            }

            .custom-brand-icon {
                width: 32px;
                height: 32px;
                font-size: 12px;
                margin-right: 8px;
            }

            .custom-brand-text {
                font-size: 18px;
            }

            .custom-user-menu {
                padding: 8px 12px;
            }

            body {
                padding-top: 70px;
            }
        }

        @media (max-width: 480px) {
            .custom-navbar {
                height: 65px;
                padding: 0 12px;
            }

            .custom-brand-text {
                display: none;
            }

            .custom-user-name {
                display: none;
            }

            .custom-user-menu {
                padding: 8px;
            }

            body {
                padding-top: 65px;
            }
        }
        </style>
    <nav class="custom-navbar">
        <!-- Brand -->
        <a href="#" class="custom-navbar-brand">
            <div class="custom-brand-icon">HM</div>
            <span class="custom-brand-text">HEMS</span>
        </a>

        <!-- Mobile menu toggle -->
        <div class="mobile-menu-toggle" id="mobileMenuToggle">
            <div class="hamburger-line"></div>
            <div class="hamburger-line"></div>
            <div class="hamburger-line"></div>
        </div>

        <!-- Navigation Menu -->
        <ul class="custom-navbar-nav" id="navbarNav">
            <li class="custom-nav-item">
                <a href="{{ route('dashboard') }}" class="custom-nav-link active">
                    Home
                </a>
            </li>

            <li class="custom-nav-item"a>
                <a href="#" class="custom-nav-link">
                    Unit
                    <span class="custom-dropdown-arrow">▼</span>
                </a>
                <div class="custom-dropdown-menu">
                    <a href="{{ route('create_Unit') }}" class="custom-dropdown-item">Tambah Unit</a>
                    <a href="{{ route('data_Unit') }}" class="custom-dropdown-item">Data Unit baru</a>
                    <a href="{{ route('unit_lama') }}" class="custom-dropdown-item">Data Unit Lama</a>
                    <a href="{{ route('jenis-unit.index') }}" class="custom-dropdown-item">Jenis Unit</a>
                </div>
            </li>

            <li class="custom-nav-item">
                <a href="#" class="custom-nav-link">
                    Operational
                    <span class="custom-dropdown-arrow">▼</span>
                </a>
                <div class="custom-dropdown-menu">
                    <a href="#" class="custom-dropdown-item">Input Laporan Harian Operasional Alat</a>
                    <a href="#" class="custom-dropdown-item">Data Laporan Harian Operasional Alat</a>
                    <a href="#" class="custom-dropdown-item">Laporan Pemakaian Alat</a>
                    <a href="#" class="custom-dropdown-item">Laporan Operasional Alat</a>
                    <a href="#" class="custom-dropdown-item">Laporan Rekap Operasional Alat</a>
                    <a href="#" class="custom-dropdown-item">Laporan Rekap Operasional Alat per-periode</a>
                    <a href="#" class="custom-dropdown-item">Laporan Operasional Tahun Semua Alat</a>
                    <a href="#" class="custom-dropdown-item">Laporan Rekap Ops Alat Per-proyek</a>
                </div>
            </li>

            <li class="custom-nav-item">
                <a href="#" class="custom-nav-link">
                    Maintenance
                    <span class="custom-dropdown-arrow">▼</span>
                </a>
                <div class="custom-dropdown-menu">

                    <a href="{{ route('Maintanance.index') }}" class="custom-dropdown-item">Input Data Maintenance</a>
                    <a href="#" class="custom-dropdown-item">Data Maintenance</a>
                    <a href="#" class="custom-dropdown-item">Input Plan Maintenance</a>
                    <a href="#" class="custom-dropdown-item">Data Plan Maintenance</a>
                </div>
            </li>

            <li class="custom-nav-item">
                <a href="#" class="custom-nav-link">
                    Biaya
                    <span class="custom-dropdown-arrow">▼</span>
                </a>
                <div class="custom-dropdown-menu">
                    <a href="#" class="custom-dropdown-item">Biaya Maintanance</a>
                    <a href="#" class="custom-dropdown-item">Data Biaya Maintanance</a>
                </div>
            </li>

            <li class="custom-nav-item">
                <a href="#" class="custom-nav-link">
                    Proyek
                    <span class="custom-dropdown-arrow">▼</span>
                </a>
                <div class="custom-dropdown-menu">
                    <a href="{{route('proyek.create')}}" class="custom-dropdown-item">Input Data Proyek</a>
                    <a href="{{route('proyek.index')}}" class="custom-dropdown-item">Data Proyek</a>
                    <a href="#" class="custom-dropdown-item">Data Kontrak Proyek</a>
                    <a href="#" class="custom-dropdown-item">Addendum Proyek</a>
                    <a href="#" class="custom-dropdown-item">Input Invoice Operasional alat</a>
                    <a href="#" class="custom-dropdown-item">Data Invoice Operasional alat</a>
                    <a href="#" class="custom-dropdown-item">Input Mutasi Alat</a>
                    <a href="#" class="custom-dropdown-item">Data Mutasi Alat</a>
                    <a href="#" class="custom-dropdown-item">Riwayat Operasional Alat</a>
                    <a href="#" class="custom-dropdown-item">Rekap Riwayat Operasional Alat</a>
                </div>
            </li>

            <li class="custom-nav-item">
                <a href="#" class="custom-nav-link">
                    Sparepart
                    <span class="custom-dropdown-arrow">▼</span>
                </a>
                <div class="custom-dropdown-menu">
                    <a href="{{ route('pengadaan-sparepart.create') }}" class="custom-dropdown-item">Input barang / Sparepart baru</a>
                    <a href="{{ route('pengadaan-sparepart.index') }}" class="custom-dropdown-item">Data Barang Sparepart baru</a>
                    <a href="#" class="custom-dropdown-item">Import Data Barang</a>
                    <a href="#" class="custom-dropdown-item">inventory Sparepart</a>
                    <a href="#" class="custom-dropdown-item">Konsumsi Langsung</a>
                    <a href="#" class="custom-dropdown-item">Pembelian Sparepart</a>
                    <a href="{{route('supplier.index')}}" class="custom-dropdown-item">Suplier Sparepart</a>
                </div>
            </li>

            <li class="custom-nav-item">
                <a href="" class="custom-nav-link">
                    Karyawan
                    <span class="custom-dropdown-arrow">▼</span>
                </a>
                <div class="custom-dropdown-menu">
                    <a href="{{ route('input_karyawan') }}" class="custom-dropdown-item">input Data Karyawan</a>
                    <a href="{{ route('data_karyawan') }}" class="custom-dropdown-item">Data Gajih/karyawan</a>
                </div>
            </li>

            <li class="custom-nav-item">
                <a href="#" class="custom-nav-link">
                    Kas
                    <span class="custom-dropdown-arrow">▼</span>
                </a>
                <div class="custom-dropdown-menu">
                    <a href="#" class="custom-dropdown-item">Input Kas Harian</a>
                    <a href="#" class="custom-dropdown-item">laporan Kas harian</a>
                    <a href="#" class="custom-dropdown-item">Laporan Kas Bulanan</a>
                    <a href="#" class="custom-dropdown-item">laporang Bank harian</a>
                    <a href="#" class="custom-dropdown-item">laporang Bank bulanan</a>
                    <a href="#" class="custom-dropdown-item">laporang kas periode</a>
                </div>
            </li>

            <li class="custom-nav-item">
                <a href="#" class="custom-nav-link">
                    Laporan
                    <span class="custom-dropdown-arrow">▼</span>
                </a>
                <div class="custom-dropdown-menu">
                    <a href="#" class="custom-dropdown-item">Historial Service</a>
                    <a href="#" class="custom-dropdown-item">Laporan Bulanan</a>
                    <a href="#" class="custom-dropdown-item">Operational Reports</a>
                    <a href="#" class="custom-dropdown-item">Laporan bulanan Proyek</a>
                    <a href="#" class="custom-dropdown-item">laporan periode</a>
                    <a href="#" class="custom-dropdown-item">laporan Tahunan</a>
                    <a href="#" class="custom-dropdown-item">laporan Konsumsi Barang</a>
                    <a href="#" class="custom-dropdown-item">laporan Pembelian Barang</a>
                    <a href="#" class="custom-dropdown-item">laporan Pembayaran Barang</a>
                </div>
            </li>

        <!-- Right Side User Menu -->
        <div class="custom-navbar-right">
            <div class="custom-user-menu" id="userMenu">
                <div class="custom-user-avatar">JW</div>
                <span class="custom-user-name">{{ Auth::user()->name }}</span>
                <span class="custom-dropdown-arrow">▼</span>
                <!-- User Dropdown Menu -->
                <div class="custom-dropdown-menu" id="userDropdown">
                    <a href="#" class="custom-dropdown-item">
                        Profile Manager
                    </a>
                    <a href="#" class="custom-dropdown-item" id="logoutLink">
                        Logout
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Demo content -->

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const navItems = document.querySelectorAll('.custom-nav-item');
            const mobileMenuToggle = document.getElementById('mobileMenuToggle');
            const navbarNav = document.getElementById('navbarNav');
            const navbar = document.querySelector('.custom-navbar');

            // Handle scroll effect
            window.addEventListener('scroll', function() {
                if (window.scrollY > 20) {
                    navbar.classList.add('scrolled');
                } else {
                    navbar.classList.remove('scrolled');
                }
            });

            // Mobile menu toggle functionality
            mobileMenuToggle.addEventListener('click', function() {
                mobileMenuToggle.classList.toggle('active');
                navbarNav.classList.toggle('mobile-active');
            });

            // Handle dropdown menus
            navItems.forEach(function(item) {
                const dropdownMenu = item.querySelector('.custom-dropdown-menu');
                const navLink = item.querySelector('.custom-nav-link');

                if (dropdownMenu) {
                    // For desktop hover behavior
                    item.addEventListener('mouseenter', function() {
                        if (window.innerWidth > 992) {
                            item.classList.add('show');
                        }
                    });

                    item.addEventListener('mouseleave', function() {
                        if (window.innerWidth > 992) {
                            setTimeout(function() {
                                if (!item.matches(':hover')) {
                                    item.classList.remove('show');
                                }
                            }, 100);
                        }
                    });

                    // For mobile click behavior
                    navLink.addEventListener('click', function(e) {
                        if (window.innerWidth <= 992) {
                            e.preventDefault();
                            e.stopPropagation();

                            // Close other dropdowns
                            navItems.forEach(function(otherItem) {
                                if (otherItem !== item) {
                                    otherItem.classList.remove('show');
                                }
                            });

                            // Toggle current dropdown
                            item.classList.toggle('show');
                        }
                    });
                }
            });

            // Close dropdowns when clicking outside
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.custom-nav-item')) {
                    navItems.forEach(function(item) {
                        item.classList.remove('show');
                    });
                }
            });

            // Close mobile menu when clicking outside
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.custom-navbar') && navbarNav.classList.contains('mobile-active')) {
                    mobileMenuToggle.classList.remove('active');
                    navbarNav.classList.remove('mobile-active');
                }
            });

            // Handle window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth > 992) {
                    // Close mobile menu on desktop
                    mobileMenuToggle.classList.remove('active');
                    navbarNav.classList.remove('mobile-active');

                    // Reset all dropdowns
                    navItems.forEach(function(item) {
                        item.classList.remove('show');
                    });
                }
            });

            // User menu functionality
            const userMenu = document.querySelector('.custom-user-menu');
            const userDropdown = document.getElementById('userDropdown');
            const logoutLink = document.getElementById('logoutLink');

            if (userMenu && userDropdown) {
                userMenu.addEventListener('click', function(e) {
                    e.stopPropagation();
                    userMenu.classList.toggle('show');
                });

                // Close user dropdown when clicking outside
                document.addEventListener('click', function(e) {
                    if (!e.target.closest('.custom-user-menu')) {
                        userMenu.classList.remove('show');
                    }
                });
            }

            // Logout functionality
            if (logoutLink) {
                logoutLink.addEventListener('click', function(e) {
                    e.preventDefault();
                    // Create and submit logout form
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '/logout';

                    // Add CSRF token
                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = document.querySelector('meta[name="csrf-token"]') ?
                                     document.querySelector('meta[name="csrf-token"]').content :
                                     '{{ csrf_token() }}';

                    // Add method spoofing for Laravel
                    const methodField = document.createElement('input');
                    methodField.type = 'hidden';
                    methodField.name = '_method';
                    methodField.value = 'POST';

                    form.appendChild(csrfToken);
                    form.appendChild(methodField);
                    document.body.appendChild(form);
                    form.submit();
                });
            }

            // Add smooth scrolling for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });

            // Add loading animation for navigation items
            const navLinks = document.querySelectorAll('.custom-nav-link, .custom-dropdown-item');
            navLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    // Only add loading effect for actual navigation (not dropdowns)
                    if (!this.closest('.custom-dropdown-menu') || this.classList.contains('custom-dropdown-item')) {
                        this.style.opacity = '0.7';
                        this.style.transform = 'scale(0.98)';

                        setTimeout(() => {
                            this.style.opacity = '';
                            this.style.transform = '';
                        }, 200);
                    }
                });
            });

            // Enhanced keyboard navigation
            document.addEventListener('keydown', function(e) {
                // Escape key closes mobile menu and dropdowns
                if (e.key === 'Escape') {
                    mobileMenuToggle.classList.remove('active');
                    navbarNav.classList.remove('mobile-active');
                    navItems.forEach(function(item) {
                        item.classList.remove('show');
                    });
                }

                // Arrow key navigation for dropdowns
                const activeDropdown = document.querySelector('.custom-nav-item.show .custom-dropdown-menu');
                if (activeDropdown && (e.key === 'ArrowDown' || e.key === 'ArrowUp')) {
                    e.preventDefault();
                    const items = activeDropdown.querySelectorAll('.custom-dropdown-item');
                    const currentIndex = Array.from(items).indexOf(document.activeElement);

                    let nextIndex;
                    if (e.key === 'ArrowDown') {
                        nextIndex = currentIndex < items.length - 1 ? currentIndex + 1 : 0;
                    } else {
                        nextIndex = currentIndex > 0 ? currentIndex - 1 : items.length - 1;
                    }

                    items[nextIndex].focus();
                }
            });

            // Add focus management for accessibility
            navItems.forEach(function(item) {
                const navLink = item.querySelector('.custom-nav-link');
                const dropdownItems = item.querySelectorAll('.custom-dropdown-item');

                navLink.addEventListener('focus', function() {
                    if (window.innerWidth > 992) {
                        item.classList.add('show');
                    }
                });

                // Focus management for dropdown items
                dropdownItems.forEach(function(dropdownItem, index) {
                    dropdownItem.addEventListener('focus', function() {
                        item.classList.add('show');
                    });

                    dropdownItem.addEventListener('blur', function() {
                        setTimeout(function() {
                            if (!item.contains(document.activeElement)) {
                                item.classList.remove('show');
                            }
                        }, 100);
                    });
                });
            });
        });
    </script>
