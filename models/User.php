<?php
require_once ('Models/Database.php');
class User extends PDO
{

    protected $_dbHandle, $_dbInstance;

    function __construct($_dbhandle)
    {
        $this->_dbHandle = $_dbhandle;
    }

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

    //login function
    public function login($username, $email, $pass)
    {
        try {
            $sqlQuery = $this->_dbHandle->prepare("SELECT * FROM Users WHERE Username=:username OR Email=:email LIMIT 1");
            $sqlQuery->execute(array(':username' => $username, ':email' => $email));
            $userRow = $sqlQuery->fetch(PDO::FETCH_ASSOC);
            if ($sqlQuery->rowCount() > 0) {
                if (password_verify($pass, $userRow['Password'])) {
                    $_SESSION['user_session'] = $userRow['username'];
                    $_SESSION['email'] = $email;
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
        if (isset($_SESSION['user_session'])) {
            return true;
        }
    }

    //simple function for easy redirection of users
    public function redirect($url)
    {
        header("Location: $url");
    }

    public function logout()
    {
        session_unset();
        session_destroy();
        $this->redirect('index.php');
    }
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
    public function fetchAllUsers()
    {
        $sqlQuery = $this->_dbHandle->prepare("SELECT * FROM Users");
        $sqlQuery->execute();

        $dataSet = [];
        while($row = $sqlQuery->fetch()) {
            $dataSet[] = new UserData($row);
        }
        return $dataSet;
    }
}

