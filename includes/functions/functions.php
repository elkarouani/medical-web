<?php

    require_once "dao.php";

    function login($login, $pass)
    {
        $req = "SELECT * FROM admin WHERE login = ? AND pass = ? AND active = 1 LIMIT 1 ";
        $params = [ $login, $pass ];
        $user = getData( $req, $params );

        if( !empty($user) )
        {
            $user['user'] = 'admin';
            return $user;
        }
        else
        {
            $req = "SELECT * FROM gestionaire WHERE login = ? AND pass = ? AND active = 1 LIMIT 1 ";
            $params = [ $login, $pass ];
            $user = getData( $req, $params );

            if( !empty($user) )
            {
                $user['user'] = 'gestionnaire';
                return $user;
            }
            else
            {
                return 0;
            }
        }
    }

    function getTitle()
    {
    	if(!isset($title) || (empty($title)))
    	{
    		$title = 'Default titre';
    	}
        global $siteName;
        global $title;
    	return $title . ' | ' . $siteName;
    }

    function UploadFile($tmp, $output_dir)
    {
        if ($tmp != '')
        {
            move_uploaded_file($tmp, $output_dir);
            return 1;
        }
        else
        {
            return 0;
        }
    }
?>