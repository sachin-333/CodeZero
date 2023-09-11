<?php

class user
{

    public static function signup($name, $username, $email, $phone, $pass, $regid)
    {
        $password = password_hash($pass, PASSWORD_BCRYPT);
        
        $conn = database::getConnection();

        $sql1 = "SET @@session.time_zone = '+05:30'";

        $sql2 = "INSERT INTO `users` (`name`,`username`, `email`,`phone`,`password`,`reg_id`, `signup_time`)
    VALUES ('$name','$username', '$email', '$phone', '$password', '$regid', now())";

        if ($conn->query($sql1) and $conn->query($sql2) === true) {
             return true;
            } 
            else 
            {
            return false;
             }

    }

    public static function login($username, $password)
    {
        $conn = database::getConnection();

        $sql = "SELECT * FROM `users` WHERE `username` = '$username' OR `email` = '$username'";
        $result = $conn->query($sql);

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $pass_verify = password_verify($password, $row['password']);
            if ($pass_verify === true) {
                return true;
            } else {
                return false;
            }

        } else {

            return false;

        }

    }

    public function __construct($id)
    {
        $conn = database::getConnection();
        $sql = "SELECT * FROM `users` WHERE `id` = '$id'";

        $result = $conn->query($sql);

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $this->id = $row['id'];
            $this->name = $row['name'];
            $this->username = $row['username'];
            $this->email = $row['email'];
            $this->phone = $row['phone'];
            $this->signup_time = $row['signup_time'];
            $this->reg_id = $row['reg_id'];

        } else {

            die();

        }
    }

    public static function no_of_users()
    {
        $conn = database::getConnection();

        $sql = "SELECT * FROM `users`";

        $result = $conn->query($sql);

        if ($result) {
            echo $result->num_rows;
        } else {
            return false;
        }
    }

    public static function signout_all($uid)
    {
        $conn = database::getConnection();
        $sql = "DELETE FROM `sessions` WHERE `uid` = '$uid'";

        $result = $conn->query($sql);

        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function update_profile($name, $username, $age, $gender, $dob, $email, $phone)
    {
        if(!$conn)
        {
            $conn = database::getConnection();
        }
        $sql1 = "UPDATE `users` SET
         `name` = '$name',
         `username` = '$username',
         `age` = '$age',
          `gender` = '$gender',
          `dob` = '$dob',
           `email` = '$email',
            `phone` = '$phone'
          WHERE `id` = '$this->id'";

          $sql2 = "UPDATE `login` SET 
            `name` = '$name',
            `username` = '$username'
            WHERE `id` = '$this->id'";
        
        if($conn->query($sql1) && $conn->query($sql2) == true)
        {
            return true;
        }
        else
        {
            return false;
        }

    }

    public static function changePassword($old, $new, $re_enter, $token, $uid)
    {
        if(usersession::authorize($token) === true)
        {
            if(!$conn)
        {
            $conn = database::getConnection();
        }
            $sql = "SELECT `password` FROM `users` WHERE `id` = '$uid'";
            $result = $conn->query($sql);
            if($result)
            {
                $data = $result->fetch_assoc();
                if(password_verify($old, $data['password']) === true)
                {
                    if($new == $re_enter)
                    {
                        $new_hash = password_hash($new, PASSWORD_BCRYPT);
                        $sql1 = "UPDATE `users` SET 
                            `password` = '$new_hash'
                            WHERE `id` = '$uid'";
                        $result = $conn->query($sql1);
                        if($result)
                        {
                            return true;
                        }
                    }
                    else{
                       return false;
                    }
                }
                else
                {
                    return false;
                }
            }
        }
        else
        {
            echo ("Unauthorized API request detected!");
        }

}
}