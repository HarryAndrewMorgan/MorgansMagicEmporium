<?php
/**
 * Created by PhpStorm.
 * User: Rayek
 * Date: 18/01/2018
 * Time: 01:05
 */

class user extends PDO
{
    private $db;

    function   __construct($connection)
    {
        $this->db = $connection;
    }

    public function register($username, $email, $pass)
    {
        try
        {
            $hashedpassword = password_hash($pass, PASSWORD_DEFAULT);
            $sqlQuery = $this->db->prepare("INSERT INTO Users(Username,Email,Password) VALUES(:username, :email, :pass)");
            $sqlQuery->execute();

        }
        catch(PDOException $exception)
        {
            echo $exception->getMessage();
        }
    }
    //simple check to see if user is logged in
    public function is_loggedin()
    {
        if(isset($_SESSION['user_session']))
        {
            return true;
        }
    }
    //simple function for easy redirection of users
    public function redirect($url)
    {
        header("Location: $url");
    }
}