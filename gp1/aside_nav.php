<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="home.php" class="app-brand-link">
                    <span class="app-brand-logo demo">
                        <!-- SVG logo code here -->
                    </span>
            <span class="app-brand-text demo menu-text fw-bolder ms-2">Dyna-Projects</span>
        </a>
        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>
    <div class="menu-inner-shadow"></div>
    <ul class="menu-inner py-1">
        <li class="menu-item">
            <a href="home.php" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div data-i18n="Analytics">Home</div>
            </a>
        </li>
        <li class="menu-item">
            <a href="dashboard.php" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div data-i18n="Analytics">Dashboard</div>
            </a>
        </li>
        <li class="menu-item">
            <a href="javascript:void(0)" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-copy"></i>
                <div data-i18n="Extended UI">Users </div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item">
                    <a href="listuser.php" class="menu-link">
                        <div data-i18n="Perfect Scrollbar">List user</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="ajoutuser.php" class="menu-link">
                        <div data-i18n="Perfect Scrollbar">Add user</div>
                    </a>
                </li>
            </ul>
        </li>
        <li class="menu-item">
            <a href="javascript:void(0)" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-copy"></i>
                <div data-i18n="Extended UI">Clients </div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item">
                    <a href="listclient.php" class="menu-link">
                        <div data-i18n="Perfect Scrollbar">List clients</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="addclient.php" class="menu-link">
                        <div data-i18n="Perfect Scrollbar">Add client</div>
                    </a>
                </li>
            </ul>
        </li>
        <li class="menu-item">
            <a href="javascript:void(0)" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-copy"></i>
                <div data-i18n="Extended UI">Projects </div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item">
                    <a href="listprojet.php" class="menu-link">
                        <div data-i18n="Perfect Scrollbar">List projects</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="addprojet.php" class="menu-link">
                        <div data-i18n="Perfect Scrollbar">Add project</div>
                    </a>
                </li>
            </ul>
        </li>
        <li class="menu-item">
            <a href="javascript:void(0)" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-copy"></i>
                <div data-i18n="Extended UI">Tasks </div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item">
                    <a href="consult_tache.php" class="menu-link">
                        <div data-i18n="Perfect Scrollbar">Consult Tasks</div>
                    </a>
                </li>

            </ul>
        </li>
        <li class="menu-item">
            <a href="javascript:void(0)" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-copy"></i>
                <div data-i18n="Extended UI">Invoice </div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item">
                    <a href="listfactures.php" class="menu-link">
                        <div data-i18n="Perfect Scrollbar">List Invoice</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="addfacture.php" class="menu-link">
                        <div data-i18n="Perfect Scrollbar">Add Invoice</div>
                    </a>
                </li>
            </ul>
        </li>
    </ul>
</aside>

<div class="layout-page">
    <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar">
        <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
            <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                <i class="bx bx-menu bx-sm"></i>
            </a>
        </div>
        <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
            <div class="navbar-nav align-items-center">
                <div class="nav-item d-flex align-items-center">
                    <!-- Search bar or other navbar content here -->
                </div>
            </div>
            <ul class="navbar-nav flex-row align-items-center ms-auto">
                <li class="nav-item lh-1 me-3"></li>
                <li class="nav-item navbar-dropdown dropdown-user dropdown">
                    <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                        <div class="avatar avatar-online">
                            <p>Profil</p>
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="#">
                                <div class="d-flex">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="avatar avatar-online">
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <span></span>
                                        <span class="fw-semibold d-block">
                                                    <?php echo htmlspecialchars($_SESSION['firstname']); ?>
                                                </span>
                                        <small class="text-muted"><?php echo htmlspecialchars($_SESSION['role']); ?></small>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <div class="dropdown-divider"></div>
                        </li>
                        <li>
                            <a class="dropdown-item" href="myprofile.php">
                                <i class="bx bx-user me-2"></i>
                                <span class="align-middle">My Profile</span>
                            </a>
                        </li>
                        <li>
                            <div class="dropdown-divider"></div>
                        </li>
                        <li>
                            <a class="dropdown-item" href="logout.php">
                                <i class="bx bx-power-off me-2"></i>
                                <span class="align-middle">Log Out</span>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
