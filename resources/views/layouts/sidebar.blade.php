<header class="main-nav">
    <div class="sidebar-user text-center">
        <a class="setting-primary" href="javascript:void(0)">
            <i data-feather="settings"></i>
        </a>
        <img class="img-90 rounded-circle" src="{{ asset('assets/images/dashboard/1.png') }}" alt="">
        <div class="badge-bottom">
            <span class="badge badge-primary">New</span>
        </div>
        <a href="">
            <h6 class="mt-3 f-14 f-w-600">{{ Auth::user()->name ?? 'Emay Walter' }}</h6>
        </a>
        <p class="mb-0 font-roboto">{{ Auth::user()->department ?? 'Human Resources Department' }}</p>
        <ul>
            <li>
                <span><span class="counter">19.8</span>k</span>
                <p>Follow</p>
            </li>
            <li>
                <span>2 year</span>
                <p>Experience</p>
            </li>
            <li>
                <span><span class="counter">95.2</span>k</span>
                <p>Follower</p>
            </li>
        </ul>
    </div>

    <nav>
        <div class="main-navbar">
            <div class="left-arrow" id="left-arrow">
                <i data-feather="arrow-left"></i>
            </div>

            <div id="mainnav">
                <ul class="nav-menu custom-scrollbar">
                    <li class="back-btn">
                        <div class="mobile-back text-end">
                            <span>Back</span>
                            <i class="fa fa-angle-right ps-2" aria-hidden="true"></i>
                        </div>
                    </li>

                    <li class="sidebar-main-title">
                        <div>
                            <h6>General</h6>
                        </div>
                    </li>

                    <!-- Dashboard Menu Only -->
                    <li class=" {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <a class="nav-link menu-title link-nav {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                            href="{{ route('dashboard') }}"
                            @if (request()->routeIs('dashboard')) style="background:#24695c;color:#fff;" @endif>
                            <i data-feather="home"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>

                    <!-- Data Siswa Menu with Submenu -->
                    <li
                        class="dropdown {{ request()->routeIs('kelas.*') || request()->routeIs('keahlian.*') || request()->routeIs('konsentrasi.*') || request()->routeIs('siswa.*') ? 'active open' : '' }}">
                        <a class="nav-link menu-title link-nav d-flex justify-content-between align-items-center"
                            href="javascript:void(0)">
                            <span>
                                <i data-feather="users"></i>
                                Data Siswa
                            </span>
                            <i class="chevron-icon chevron-right{{ request()->routeIs('kelas.*') || request()->routeIs('keahlian.*') || request()->routeIs('konsentrasi.*') || request()->routeIs('siswa.*') ? ' chevron-down' : '' }}"
                                style="transition:transform .2s"></i>
                        </a>
                        <ul class="nav-submenu menu-content">
                            <li>
                                <a href="{{ route('keahlian.index') }}"
                                    class="{{ request()->routeIs('keahlian.*') ? 'text-success fw-bold' : '' }}">Data
                                    Keahlian</a>
                            </li>
                            <li>
                                <a href="{{ route('konsentrasi.index') }}"
                                    class="{{ request()->routeIs('konsentrasi.*') ? 'text-success fw-bold' : '' }}">Konsentrasi</a>
                            </li>
                            <li>
                                <a href="{{ route('kelas.index') }}"
                                    class="{{ request()->routeIs('kelas.*') ? 'text-success fw-bold' : '' }}">Data
                                    Kelas</a>
                            </li>
                            <li>
                                <a href="{{ route('siswa.index') }}"
                                    class="{{ request()->routeIs('siswa.*') ? 'text-success fw-bold' : '' }}">Siswa</a>
                            </li>
                        </ul>
                    </li>

                    <!-- Data Penelitian Menu with Submenu -->
                    <li class="dropdown">
                        <a class="nav-link menu-title link-nav d-flex justify-content-between align-items-center" href="javascript:void(0)">
                            <span>
                                <i data-feather="book-open"></i>
                                Data Penelitian
                            </span>
                            <i class="chevron-icon chevron-right" style="transition:transform .2s"></i>
                        </a>
                        <ul class="nav-submenu menu-content">
                            <li>
                                <a href="#">Angket Minat</a>
                            </li>
                            <li>
                                <a href="#">Angket Motivasi</a>
                            </li>
                            <li>
                                <a href="#">Hasil Belajar</a>
                            </li>
                            <li>
                                <a href="#">Perhitungan AHP</a>
                            </li>
                        </ul>
                    </li>

                                <style>
                                    .chevron-icon {
                                        display: inline-block;
                                        width: 1em;
                                        height: 1em;
                                        vertical-align: middle;
                                        transition: transform .2s;
                                    }

                                    .chevron-right {
                                        background: url('data:image/svg+xml;utf8,<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>') center/1em 1em no-repeat;
                                    }

                                    .chevron-down {
                                        background: url('data:image/svg+xml;utf8,<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>') center/1em 1em no-repeat;
                                    }
                                </style>
                                <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        var dropdown = document.querySelector('.dropdown');
                                        var chevron = dropdown.querySelector('.chevron-icon');
                                        var submenu = dropdown.querySelector('.nav-submenu');
                                        // Initial state: if open by route, show submenu and chevron-down, else hide submenu and show chevron-right
                                        if (dropdown.classList.contains('open')) {
                                            chevron.classList.remove('chevron-right');
                                            chevron.classList.add('chevron-down');
                                            submenu.style.display = 'block';
                                        } else {
                                            chevron.classList.remove('chevron-down');
                                            chevron.classList.add('chevron-right');
                                            submenu.style.display = 'none';
                                        }
                                        dropdown.querySelector('.nav-link').addEventListener('click', function(e) {
                                            // Toggle open/close and chevron state
                                            if (e.target.closest('.nav-link')) {
                                                e.preventDefault();
                                                if (!dropdown.classList.contains('open')) {
                                                    dropdown.classList.add('open');
                                                    chevron.classList.remove('chevron-right');
                                                    chevron.classList.add('chevron-down');
                                                    submenu.style.display = 'block';
                                                } else {
                                                    dropdown.classList.remove('open');
                                                    chevron.classList.remove('chevron-down');
                                                    chevron.classList.add('chevron-right');
                                                    submenu.style.display = 'none';
                                                }
                                            }
                                        });
                                    });
                                </script>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>

            <div class="right-arrow" id="right-arrow">
                <i data-feather="arrow-right"></i>
            </div>
        </div>
    </nav>
</header>
