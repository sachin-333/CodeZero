<?php

class gig
{

public static function create($username, $gig_name, $total_income, $fixed_income)
{
    if(!$conn)
    {
        $conn = database::getConnection();
    }
    $gig_id = rand(11111, 99999);
    $sql = "INSERT INTO `gig` (`uid`, `username`, `gig_id`, `gig_name`, `total_income`, `fixed_income`) 
    VALUES ('$this->id', '$this->username', '$gig_id', '$gig_name', '$total_income', '$fixed_income')";

    $result = $conn->query($sql);
    if($result == true)
    {
        return true;
    }
    else{
        return false;
    }
}

public function __construct($gig_id)
{
    $conn = database::getConnection();
    $sql = "SELECT * FROM `gig` WHERE `gig_id` = $gig_id";
    $result = query($sql);

    if($result->num_rows == 1)
    {
        $row = $result->fetch_assoc();
        $this->gig_id = $row['gig_id'];
        $this->gig_name = $row['gig_name'];
        $this->total_income = $row['total_income'];
        $this->fixed_income = $row['fixed_income'];
    }
}

public function calculateIncomeTaxReduction()
{
    
}

public function calculateSavings()
{

}


public function showSavingsHistory($gig_id)
{
    if(!$conn)
    {
        $conn = database::getConnection();
    }
    $sql = "SELECT * FROM `gig` WHERE `gig_id` = '$gig_id'";
    $result = $conn->query($sql);
    if($result == true)
    {
        return $row->fetch_assoc();
    }
    else{
        return false;
    }

}

public function addExpenseHistory($uid, $gig_id, $total_amount, $amount_spent, $remarks)
{
    if(!$conn)
    {
        $conn = database::getConnnection();
    }
        $updated_amount = $total_amount - $amount_spent;
        $conn = database::getConnection();

        $sql1 = "UPDATE `gig` SET 
        `updated_savings_amt` = '$updated_amt'
        WHERE `gig_id` = '$gig_id'";

        $result = $conn->query($sql1);
        if($result == true)
        {
            $sql2 = "INSERT INTO `expenditure_history` (`uid`, `gig_id`, `gig_name`, `amount_spent`, `remarks`)
            VALUES ('$uid', '$this->$gig_id', '$this->gig_name', '$amount_spent', '$remarks')";
            if($conn->query($sql2) == true)
            {
                return true;
            }
            else{
                return false;
            }
        }
        else{
            die("Error in updating gig amount!");
        }
    }
}