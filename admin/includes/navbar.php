<nav class="navbar navbar-expand-md navbar-dark bg-dark">
    <!-- <a href="/" class="navbar-brand brand">bondgpsn.com</a> -->
    <a href="/" class="navbar-brand brand" target="_blank"><img src="/dist/img/gpsn.jpg" class="brand-logo" alt=""></a>

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#toggle">
        <span class="navbar-toggler-icon"></span>
    </button>
    

    <div class="collapse navbar-collapse" id="toggle">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" href="/" target="_blank"><i class="fa fa-fw fa-external-link"></i></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/admin/">OSCE Night</a>
            </li>
        </ul>
        <?php
            if(isset($_SESSION['adminID'])) {
                echo '
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="/admin/logout">Logout</a>
                        </li>
                    </ul>
                ';
            }
        ?>
    </div>
</nav>