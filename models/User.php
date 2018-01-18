<?php
class User extends PDO
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
            $sqlQuery->bindparam(":username", $username);
            $sqlQuery->bindparam(":email", $email);
            $sqlQuery->bindparam(":pass", $hashedpassword);
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