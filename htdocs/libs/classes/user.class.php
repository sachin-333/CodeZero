<?php

class user
{

    public static function signup($name, $username, $email, $phone, $pass, $regid)
    {
        $password = password_hash($pass, PASSWORD_BCRYPT);
        
        $conn = database::getConnection();

        $sql1 = "SET @@session.time_zone = '+05:30'";

        $sql2 = "INSERT INTO `users` (`name`,`username`, `email`,`phone`, `profession`, `income_range`, `job_type`, `reg_id`, `signup_time`)
        VALUES ('$name','$username', '$email', '$phone', '', '', '', '$regid', now())";

    if ($conn->query($sql1) and $conn->query($sql2))
    {
        $sql3 = "SELECT * FROM `users` WHERE `username` = '$username'";
        $result = $conn->query($sql3);
        if($result->num_rows == 1)
        {
            $row = $result->fetch_assoc();
            $id = $row['id'];
            $sql4 = "INSERT INTO `login` (`id`, `username`, `password`, `email`, `reg_id`) 
            VALUES ('$id', '$username', '$password', '$email', '$regid')";
            if($conn->query($sql4) == true)
            {
                return true;
                // echo "success";
            } else 
            {
                return false;
                // echo "fail";
            }
        }
    }
    else{
        die("database error");
    }
}

    public static function login($username, $password)
    {
        $conn = database::getConnection();

        $sql = "SELECT * FROM `login` WHERE `username` = '$username' OR `email` = '$username'";
        $result = $conn->query($sql);

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $pass_verify = password_verify($password, $row['password']);
            if ($pass_verify === true) {
              return true;  
            // echo "success";
            } else {
                return false;
                // echo "fail";
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

    public static function signout($session_token)
    {
        $conn = database::getConnection();
        $sql = "DELETE FROM `sessions` WHERE `session_token` = '$session_token'";

        $result = $conn->query($sql);

        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function update_profile($name, $username, $email, $phone, $profession, $income_range, $job_type)
    {
        if(!$conn)
        {
            $conn = database::getConnection();
        }
        $sql1 = "UPDATE `users` SET
         `name` = '$name',
         `username` = '$username',
           `email` = '$email',
            `phone` = '$phone',
            `profession` = '$profession',
            `income_range` = '$income_range',
            `job_type` = '$job_type'
          WHERE `id` = '$this->id'";

          $sql2 = "UPDATE `login` SET 
            `username` = '$username',
            `email` = '$email'
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

    public function changePassword($old, $new, $re_enter, $token)
    {
        if(usersession::authorize($token) === true)
        {
            if(!$conn)
        {
            $conn = database::getConnection();
        }
            $sql = "SELECT `password` FROM `login` WHERE `id` = '$this->uid'";
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
                            WHERE `id` = '$this->id'";
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

