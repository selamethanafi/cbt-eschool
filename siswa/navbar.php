<nav class="navbar navbar-expand navbar-light navbar-bg">
    <a class="sidebar-toggle js-sidebar-toggle">
        <i class="hamburger align-self-center"></i>
    </a>

    <div class="navbar-collapse collapse">
        <ul class="navbar-nav navbar-align ms-auto">
            <!-- User Info -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-user-circle me-1"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                    <li><span class="dropdown-item-text"><strong><?php echo $nama_siswa; ?></strong></span></li>
                    <li><span class="dropdown-item-text"><?php echo $kelas_siswa; ?><?php echo $rombel_siswa; ?></span></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger btnLogout" href="logout.php"><i class="fas fa-sign-out-alt me-1"></i> Logout</a></li>
                </ul>
            </li>
        </ul>
    </div>
</nav>
