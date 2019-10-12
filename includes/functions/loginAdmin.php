<?php

    require_once "functions.php";

    session_start();

    $_SESSION['user'] = '';
    $_SESSION['message'] = '';

    if( isset( $_POST['login']) && isset( $_POST['pass'] ) && !empty($_POST['login']) && !empty($_POST['pass']) )
    {
        $login = $_POST['login'];
        $pass = md5($_POST['pass']);
        $user = login($login, $pass);

        if( $user != 0 )
        {
            if ( $user['active'] == 0 )
            {
                $_SESSION['message'] = 'Votre compte n\'est pas active !';
                header('location:../../login.php');
            }
            else
            {
                if( $user['user'] == 'gestionnaire' )
                {
                    $user['user'] = 'gestionnaire';
                    $_SESSION['user'] = $user;
                    header('location:../../acceuilGestionnaire.php');
                }
                else if( $user['user'] == 'admin' )
                {
                    $user['user'] = 'admin';
                    $_SESSION['user'] = $user;
                    header('location:../../acceuilAdmin.php');
                }
                else
                {
                    header('location:login.php');
                }
            }
        }
        else
        {
            $_SESSION['message'] = 'Login d\'utilisateur ou mot de passe incorrect !';
            header('location:../../login.php');
        }
    }
    else
    {
        $_SESSION['message'] = 'Le login et le mot de pass est obligatoire !';
        header('location:../../login.php');
    }

?>