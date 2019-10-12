<?php

require_once 'Api.php';

if( isset( $_GET['action'] ) && !empty( $_GET['action'] ) )
{
    header('Content-Type: application/json');

    $action = $_GET['action'];
    $Api = new Api();

    switch( $action )
    {
        case 'getRdvToday':
            $data = $Api->getRdvToday();
            echo ( $data != null ) ? $data : 0 ;
        break;

        case 'getSallAttentToday':
            $data = $Api->getSallAttentToday();
            echo ( $data != null ) ? $data : 0 ;
        break;

        case 'getConsultationsToday':
            $data = $Api->getConsultationsToday();
            echo ( $data != null ) ? $data : 0 ;
        break;

        case 'getPaiementsToday':
            $data = $Api->getPaiementsToday();
            echo ( $data != null ) ? $data : 0 ;
        break;

        case 'getRdvsBetweenTwoDate':
            $data = $Api->getRdvIntervalDate();
            echo ( $data != null ) ? $data : 0 ;
        break;

        case 'getAllPatients':
            $data = $Api->getAllPatients();
            echo ( $data != null ) ? $data : 0 ;
        break;

        case 'getAllRdv':
            $data = $Api->getAllRdv();
            echo ( $data != null ) ? $data : 0 ;
        break;

        case 'loginAdmin':

            if( isset( $_GET['login']) && isset( $_GET['password'] ) && !empty( $_GET['login']) && !empty( $_GET['password'] )  )
            {
                $login = $_GET['login'];
                $password = md5( $_GET['password'] );

                $data = $Api->loginAdmin( $login, $password );
                echo ( $data != null ) ? $data : 0 ;
            }
            else
            {
                echo NULL;
            }
        break;

        default :

            echo NULL;

        break;
    }
}
else
{
    echo NULL;
}

