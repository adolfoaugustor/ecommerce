<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="/libs/bootstrap/4.1.0/bootstrap.min.css" />
    <title><?php echo $this->page_title; ?></title>
    <link rel="stylesheet" href="/css/style.css"/>
</head>
<body>

    <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
        <a class="navbar-brand" href="/">Home</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault"
                aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarsExampleDefault">
            <ul class="navbar-nav mr-auto">
<!--                <li class="nav-item">-->
<!--                    <a class="nav-link" href="/products">Products</a>-->
<!--                </li>-->
                <?php if (authIsLogged()): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/products">Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/products/create">Create Product</a>
                    </li>
                <?php endif; ?>
            </ul>
            <ul class="navbar-nav ml-auto">
                <?php if (authIsLogged()): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle"
                           href="#"
                           id="dropdown01"
                           data-toggle="dropdown"
                           aria-haspopup="true"
                           aria-expanded="false">
                            <?php echo authUser()->name ?>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown01">
                            <a class="dropdown-item" href="/logout">Logout</a>
                        </div>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/login">Login</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <main role="main" class="container">
        <?php $flashMessages = $this->flashes(); ?>
        <?php echo count($flashMessages) > 0 ? '<br/>' : '' ?>
        <?php foreach($flashMessages as $type => $messages): ?>
            <?php foreach($messages as $msg): ?>
                <div class="alert alert-<?php echo $type ?>">
                    <?php echo $msg ?>
                </div>
            <?php endforeach; ?>
        <?php endforeach; ?>

        <?php $this->yieldView() ?>
    </main><!-- /.container -->

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="/libs/jquery/3.3.1/jquery.min.js" type="text/javascript"></script>
    <script src="/libs/popper/1.14.0/popper.min.js" type="text/javascript"></script>
    <script src="/libs/bootstrap/4.1.0/bootstrap.min.js" type="text/javascript"></script>

    <script src="/libs/moment/2.22.2/moment.min.js" type="text/javascript"></script>

    <script src="/libs/jquery.validate/1.17.0/jquery.validate.min.js" type="text/javascript"></script>
    <script src="/libs/jquery.validate/1.17.0/additional-methods.min.js" type="text/javascript"></script>

    <script src="/libs/jquery.mask/1.14.15/jquery.mask.min.js" type="text/javascript"></script>

    <script src="/js/javascript.js" type="text/javascript"></script>
</body>
</html>
