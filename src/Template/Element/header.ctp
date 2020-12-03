<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="<?= $this->Url->build(array('controller'=>'Home','action'=>'index')) ?>">HamamatsuOri</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Link</a>
            </li>
            <li class="nav-item">
                <a class="nav-link disabled" href="#">Disabled</a>
            </li>
            </ul>
            <li class="nav-item dropdown">
                <div class="btn-group">
                    <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-user"></i>&nbsp;&nbsp;<?= $userData['username'] ?>
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="<?= $this->Url->build(array('controller'=>'Users','action'=>'index')) ?>" >User control</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="<?= $this->Url->build(array('controller'=>'Users','action'=>'logout')) ?>">
                            <i class="fa fa-sign-out-alt"></i>Logout
                        </a>
                    </div>
                </div>
            </li>
        </div>
    </div>
</nav>