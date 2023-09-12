<?php

class gig
{

public static function create($uid, $username, $gig_name, $total_income, $fixed_income)
{
    if(!$conn)
    {
        $conn = database::getConnection();
    }
    $gig_id = rand(11111, 99999);

    $emergency_savings = $total_income * 15 / 100;
    $rem1 = $total_income - $emergency_savings;

    $general_savings = $rem1 * 40 / 100;
    $rem2 = $rem1 - $general_savings;

    $updated_exp_amt = $rem2;

    $sql = "INSERT INTO `gig` (`uid`, `username`, `gig_id`, `gig_name`, `total_income`, `emergency_savings`, `general_savings`, `fixed_income`, `updated_remaining_amt`) 
    VALUES ('$uid', '$username', '$gig_id', '$gig_name', '$total_income', '$emergency_savings', '$general_savings', '$fixed_income', '$updated_exp_amt' )";

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
    $result = $conn->query($sql);

    if($result->num_rows == 1)
    {
        $row = $result->fetch_assoc();
        $this->gig_id = $row['gig_id'];
        $this->gig_name = $row['gig_name'];
        $this->total_income = $row['total_income'];
        $this->emergency_savings = $row['emergency_savings'];
        $this->general_savings = $row['general_savings'];
        $this->updated_remaining_amt = $row['updated_remaining_amt'];
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

public function addExpenseHistory($uid, $gig_id, $gig_name, $total_amount, $amount_spent, $remarks)
{
        $conn = database::getConnection();
        $updated_amount = $total_amount - $amount_spent;
        if($total_amount < $amount_spent)
        {
            die("Amount Spent is higher than available amount!");
        }
        $conn = database::getConnection();

        $sql1 = "UPDATE `gig` SET 
        `updated_remaining_amt` = '$updated_amount'
        WHERE `gig_id` = '$gig_id'";

        $result = $conn->query($sql1);
        if($result == true)
        {
            $sql2 = "INSERT INTO `expenditure_history` (`uid`, `gig_id`, `gig_name`, `amount_spent`, `remarks`)
            VALUES ('$uid', '$gig_id', '$gig_name', '$amount_spent', '$remarks')";
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