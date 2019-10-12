<?php

    session_start();
    $errors = '';

    if(isset( $_SESSION['user'] ) && ( $_SESSION['user'] != '' ) && ( empty($_SESSION['message']) ) )
    {
        if( $_SESSION['user']['user'] == 'admin' )
        {
            header('location:acceuilAdmin.php');
        }
        else if( $_SESSION['user']['user'] == 'gestionnaire' )
        {
            header('location:acceuilGestionnaire.php');
        }
        else
        {
            header('location:acceuilGestionnaire.php');
        }
    }

    if( isset( $_SESSION['message'] ) )
    {
        $errors = $_SESSION['message'];
    }

?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>login | Gestion cabinet medical</title>
    <link rel="stylesheet" href="layout/css/bootstrap.min.css">
    <link rel="stylesheet" href="layout/css/font-awesome.min.css">
    <link rel="stylesheet" href="layout/css/animate.css">
    <link rel="stylesheet" href="layout/css/AdminLTE.min.css">
    <link rel="stylesheet" href="layout/css/_all-skins.min.css">
    <link rel="stylesheet" href="layout/css/style.css">
    <style>
        body{
            position: relative;
            background-image: url("layout/img/bg_login.jpg");
            background-attachment: fixed;
            background-origin: border-box;
            background-size: cover;
        }
        #fromLogin{
            position: absolute;
            top: 50%;
            left: 50%;
            padding: 50px 20px;
            width: 450px;
            transform: translate(-50%, -50%);
            background-color: #fff;
            box-shadow: 0 4px 5px 0 rgba(0,0,0,0.14),0 1px 10px 0 rgba(0,0,0,0.12),0 2px 4px -1px rgba(0,0,0,0.3);
        }

        #fromLogin h4{
            margin-bottom: 25px;
        }

        #fromLogin button{
            margin-top: 10px;
        }
    </style>
</head>
<body>

    <form id="fromLogin" action="includes/functions/loginAdmin.php" method="post">
        <h4 class="text-center"><i class="fa fa-user"></i> Connexion</h4>
        <?php if(!empty( $errors )): ?>
            <div class="alert alert-danger">
                <ul>
                    <li> <?= $errors; ?> </li>
                </ul>
            </div>
        <?php endif ?>
        <div class="form-group has-feedback">
            <input class="form-control" name="login" placeholder="Login d'utilisateur " type="text" >
            <span class="glyphicon glyphicon-user form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
            <input class="form-control" name="pass" placeholder="Mot de passe" type="password" >
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <button class="btn btn-primary btn-block btn-flat" > <i class="fa fa-lock"></i> Connexion </button>
            </div>
        </div>
    </form>

    <script src="layout/js/jquery.min.js"></script>
    <script src="layout/js/bootstrap.min.js"></script>
    <script src="layout/js/app.js"></script>

</body>
</html>
<?php $_SESSION['message'] = ''; ?>