<?php
    $userConnect = [];
    if( isset( $_SESSION['user'] ) && !empty( $_SESSION['user'] ) )
    {
        $userLog = $_SESSION['user']['user'];
        if( !in_array($userLog, $acceUser) )
        {
            header('location:login.php');
        }
        else
        {
            $userConnect = $_SESSION['user'];
        }
    }
    else
    {
        header('location:login.php');
    }

?>