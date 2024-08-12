<style>
    /* Efek hover untuk gambar profil */
    .user-area .dropdown-toggle {
        position: relative;
        display: inline-block;
    }

    .user-area .dropdown-toggle .user-avatar {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .user-area .dropdown-toggle:hover .user-avatar {
        transform: scale(1.2);
        /* Memperbesar gambar */
        box-shadow: 0 0 0 5px rgba(0, 123, 255, 0.5);
        /* Efek lingkaran berwarna biru */
    }
</style>

<header id="header" class="header">

    <div class="header-menu">

        <div class="col-sm-7">
            <a id="menuToggle" class="menutoggle pull-left"><i class="fa fa fa-tasks"></i></a>
        </div>

        <div class="col-sm-5">
            <div class="user-area dropdown float-right">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img class="user-avatar rounded-circle" src="images/admin.jpg" alt="User Avatar">
                </a>

                <div class="user-menu dropdown-menu">
                    <a class="nav-link" href="#"><i class="fa fa-user"></i> Kelola Profil</a>
                    <a class="nav-link" href="#"><i class="fa fa-power-off"></i> Logout</a>
                </div>
            </div>
        </div>
    </div>

</header>