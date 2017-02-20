
<?php
$pageTitle = isset($pageTitle) ? $pageTitle : "Авторизация на сайте HABB.KZ";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags always come first -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <meta name="robots" content="noindex, nofollow">
    <meta name="googlebot" content="noindex">

    <title><?= $pageTitle ?></title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/custom.css">
    <link rel="stylesheet" href="../assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="../assets/css/select2.min.css"  />
</head>
<body id="body-login">







    <div class="container">

        <div class="row">
            <div class="offset-sm-3 col-sm-6">

                <div class="card margin-50 box-shadow">
                    <div class="card-block">
                        <div class="mt-2 text-sm-center">
                            <h1>Авторизация</h1>
                        </div>

                        <?php
                        if (isset($_SESSION["errors"])) {
                            ?>
                            <div class="alert alert-danger alert-dismissible fade in" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <?php
                                foreach ($_SESSION["errors"] as $key => $value){
                                    echo $value."<br>";
                                }
                                ?>
                            </div>
                            <?php
                            unset($_SESSION["errors"]);
                        }

                        if (isset($_SESSION["success"])) {
                            ?>
                            <div class="alert alert-success alert-dismissible fade in">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                <?php
                                foreach ($_SESSION["success"] as $key => $value){
                                    echo $value."<br>";
                                }
                                ?>
                            </div>
                            <?php
                            unset($_SESSION["success"]);
                        }
                        ?>

                        <form method="post" action="../session/login.php" class="form-horizontal">
                            <input type="hidden" name="performed" value="true">
                            <div class="form-group">
                                <input type="text" class="form-control" id="login" name="login" placeholder="Введите свой логин"  maxlength="30" minlength="3" required autofocus>
                            </div>

                            <div class="form-group">
                                <input type="password" class="form-control" id="password" name="password" placeholder="Введите пароль" required>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-block">Авторизоваться</button>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>


    </div>

    <script src="../assets/js/tether.min.js"></script>
    <script src="../assets/js/jquery-3.1.1.min.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>
    <script src="../assets/js/select2.min.js"></script>
</body>
</html>
