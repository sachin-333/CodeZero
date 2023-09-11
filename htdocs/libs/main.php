<?php

function get_config($key)
{
    $config = file_get_contents('/var/www/CodeZero/workspace/config.json');
    $data = json_decode($config, true);

    if(isset($data[$key]))
    {
        return $data[$key];
    }else{
        echo "error";
    }
}

function load_template($name)
{
    include $_SERVER['DOCUMENT_ROOT']."/_templates/$name.html";
}

include_once 'classes/database.class.php';
include_once 'classes/user.class.php';
include_once 'classes/session.class.php';
include_once 'classes/usersession.class.php';

session::start();

// user::signup('umar', 'umarfarooq07', 'umar@example.com', '123456789', 'pass', '123');
// user::login('umar@example.com', 'pass')
