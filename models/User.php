<?php
require_once ('models/Database.php');
class User extends PDO
{

    //declare dbhandle and instance
    protected $_dbHandle, $_dbInstance;

    //constructs database using __construct
    function __construct($_dbhandle)
    {
        $this->_dbHandle = $_dbhandle;
    }

    //registers the user after inputs have been validated, also hashes password
    public function register($username, $email, $pass, $address, $phone)
    {
        try {
            $hashedpassword = password_hash($pass, PASSWORD_DEFAULT);
            $sqlQuery = $this->_dbHandle->prepare("INSERT INTO Users(Username,Email,Password, Address, Phone) VALUES(:username, :email, :pass, :address, :phone)");
            $sqlQuery->bindparam(":username", $username);
            $sqlQuery->bindparam(":email", $email);
            $sqlQuery->bindparam(":pass", $hashedpassword);
            $sqlQuery->bindparam(":address", $address);
            $sqlQuery->bindparam(":phone", $phone);
            $sqlQuery->execute();

        } catch (PDOException $exception) {
            echo $exception->getMessage();
        }
    }

    //login function that returns a single row and verifies hashed password, returns true if login details match records, and false if not
    public function login($username, $email, $pass)
    {
        try {
            $sqlQuery = $this->_dbHandle->prepare("SELECT * FROM Users WHERE Username=:username OR Email=:email LIMIT 1");
            $sqlQuery->execute(array(':username' => $username, ':email' => $email));
            $userRow = $sqlQuery->fetch(PDO::FETCH_ASSOC);
            if ($sqlQuery->rowCount() > 0) {
                if (password_verify($pass, $userRow['Password'])) {
//                    $_SESSION['user_session'] = $userRow['username'];
//                    $_SESSION['email'] = $email;
                    return true;
                } else {
                    return false;
                }
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    //simple check to see if user is logged in
    public function is_loggedin()
    {
        if (isset($_SESSION['UserID'])) {
            return true;
        }
    }

    //simple function for easy redirection of users
    public function redirect($url)
    {
        header("Location: $url");
    }

    //destroys and unsets all session variables, logging the user out. Also redirects the user to the homepage
    public function logout()
    {
        session_unset();
        session_destroy();
        $this->redirect('index.php');
    }

    //returns a single users information when given the set session variable that has been set
    public function fetchAUser()
    {
        $username = $_SESSION['Username'];
        $sqlQuery = $this->_dbHandle->prepare("SELECT * FROM Users WHERE Username LIKE '$username'");
        $sqlQuery->execute();
        $result = $sqlQuery->fetch(PDO::FETCH_ASSOC);
        $_SESSION['Address'] = $result['Address'];
        $_SESSION['Phone'] = $result['Phone'];
        $_SESSION['UserID'] = $result['UserID'];
    }

    //returns all users from the user table
    public function fetchAllUsers()
    {
        $sqlQuery = $this->_dbHandle->prepare("SELECT * FROM Users");
        $sqlQuery->execute();

        $dataSet = [];
        while ($row = $sqlQuery->fetch()) {
            $dataSet[] = new UserData($row);
        }
        return $dataSet;
    }

    //checks the users table for any users that have the same details inputted and returns an alert if any matches are made
    public function checkDuplicateDetails($username, $email)
    {
        //prepare statement to find matching username and emails
        $sqlQuery = $this->_dbHandle->prepare("SELECT Username, Email FROM Users WHERE Username=:username OR Email=:email");
        //execute statement and enter values into an array for easy access
        $sqlQuery->execute(array(':username' => $username, ':email' => $email));
        //fetches the associated rows
        $sqlQuery->fetchAll(PDO::FETCH_OBJ);
        //if any users are found return true if none are found return false
        if($sqlQuery->rowCount() > 0) {
            return true;
        } else return false;
    }

}

