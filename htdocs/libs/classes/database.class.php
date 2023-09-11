<?php

class database
{
    static $conn;

    public static function getConnection()
    {
        if(!self::$conn)
        {
            $servername = get_config('servername');
            $username = get_config('username');
            $password = get_config('password');
            $dbname = get_config('dbname');

            $connection = new mysqli($servername, $username, $password, $dbname);

            if($connection)
            {
                self::$conn = $connection;
                // echo "success";
                return self::$conn;
            }
            else{
                die($connection->connect_error);
            }
        }
        else
        {
           return self::$conn;
        }
    }
}