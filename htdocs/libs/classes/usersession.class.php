<?php

class usersession
{
    public static function authenticate($user, $pass, $fingerprint)
    {
        $result = user::login($user, $pass);
        if ($result) {

            $conn = database::getConnection();

            $sql = "SELECT * FROM `users` WHERE `username` = '$user' OR `email` = '$user'";

            $result1 = $conn->query($sql);
            if($result1->num_rows == 1)
            {
                $row = $result1->fetch_assoc();
                $uid = $row['id'];
            }
            else
            {
                return false;
            }
            $userobj = new user($uid);
            $username = $userobj->username;
            $id = $userobj->id;
            $user_ip = $_SERVER['REMOTE_ADDR'];
            $session_token = md5(rand(0, 9999) . $username . $user_ip);
            $user_agent = $_SERVER['HTTP_USER_AGENT'];

            $conn = database::getConnection();

            $sql = "INSERT INTO `sessions` (`uid`, `username`, `session_token`, `user_ip`,`login_time`, `user_agent`, `user_fingerprint`) VALUES ('$id', '$username', '$session_token','$user_ip', now(), '$user_agent')";

            $result = $conn->query($sql);

            if ($result) {
                session::set('session_token', $session_token);
                session::set('session_user', $userobj->username);
                session::set('user_fingerprint', $fingerprint);
                session::set('user_id', $userobj->id);

                return true;
            } else {
                return false;

            }
        } else {
           return false;
        }

    }

    public static function authorize($token, $fingerprint)
    {
        $host_ip = $_SERVER['REMOTE_ADDR'];
        $host_useragent = $_SERVER['HTTP_USER_AGENT'];
        $host_fingerprint = $_POST['fingerprint'];

        $conn = database::getConnection();

        $sql = "SELECT * FROM `sessions` WHERE `session_token` = '$token'";

        $result = $conn->query($sql);

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $session_ip = $row['user_ip'];
            $session_useragent = $row['user_agent'];
            $user_fingerprint = $row['user_fingerprint'];
        } else {
            session::unset_all();
            header('Location: /users/login');
        }

        if ($host_ip == $session_ip and $host_useragent == $session_useragent and $host_fingerprint == $user_fingerprint) {
            return true;
        } else {

            $sql = "DELETE FROM `sessions` WHERE ((`session_token` = '$token'))";
            $conn->query($sql);
            session::destroy();
            session::unset_all();
            header('Location: /users/login');

        }

    }

    public function __construct($token)
    {

        $conn = database::getConnection();

        $sql = "SELECT * FROM `sessions` WHERE `session_token` = '$token'";

        $result = $conn->query($sql);

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $this->data = $row;
            $this->id = $row['uid'];
            $this->username = $row['username'];
            $this->ip = $row['user_ip'];
            $this->user_agent = $row['user_agent'];
        } else {
           return false;
        }

    }

    public static function isValid($token)
    {
        $conn = database::getConnection();
        $sql = "SELECT * FROM `sessions` WHERE `session_token` = '$token'";

        $result = $conn->query($sql);
        {
            if ($result->num_rows == 1) {
                return true;
            } else {
               return false;
            }

        }

    }

}