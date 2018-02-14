<?php
class Advert extends PDO
{
    private $db;

    function __construct($_dbhandle)
    {
        $this->db = $_dbhandle;
    }

    public function createAdvert($name, $price, $description, $type, $userID, $picture, $date, $expiry)
    {
        {
            try {
                $sqlQuery = $this->db->prepare("INSERT INTO Adverts(AdvertName,AdvertPrice,AdvertDescription, AdvertType, UserID, PhotoName, AdvertDate, AdvertExpiry) VALUES(:aname, :price, :description, :type, :userID, :picture, :advertDate, :advertExpiry)");
                $sqlQuery->bindparam(":aname", $name);
                $sqlQuery->bindparam(":price", $price);
                $sqlQuery->bindparam(":description", $description);
                $sqlQuery->bindparam(":type", $type);
                $sqlQuery->bindparam(":userID", $userID);
                $sqlQuery->bindparam(":picture", $picture);
                $sqlQuery->bindparam(":advertDate", $date);
                $sqlQuery->bindparam(":advertExpiry", $expiry);
                $sqlQuery->execute();


            } catch (PDOException $exception) {
                echo $exception->getMessage();
            }
        }


    }
    public function redirect($url)
    {
        header("Location: $url");
    }
    public function fetchAllAdverts()
    {
        $sqlQuery = $this->db->prepare("SELECT * FROM Adverts");
        $sqlQuery->execute();
        $advert = $sqlQuery->fetch(PDO::FETCH_ASSOC);
        while ($advert = $sqlQuery->fetch(PDO::FETCH_ASSOC)) {
            echo $advert['AdvertName'];
            echo $advert['AdvertPrice'];
            echo $advert['AdvertDescription'];
            echo $advert['AdvertType'];
            echo $advert['UserID'];
            echo $advert['PhotoName'];
        }

    }
    public function returnAdverts()
    {
        $sqlQuery = $this->db->prepare("SELECT * FROM Adverts");
        $sqlQuery->execute();
        $results = $sqlQuery->fetchAll(PDO::FETCH_OBJ);
        return $results;
    }
    public function getAdvertForUser($userID)
    {
        $sqlQuery = $this->db->prepare("SELECT * FROM Adverts WHERE UserID=:userID");
        $sqlQuery->bindparam(":userID", $userID);
        $sqlQuery->execute();
        $results = $sqlQuery->fetchAll(PDO::FETCH_OBJ);
        return $results;
    }
    public function returnAdvert($advertID)
    {
        $sqlQuery = $this->db->prepare("SELECT * FROM Adverts INNER JOIN Users ON Adverts.UserID = Users.UserID WHERE AdvertID='$advertID'");
        $sqlQuery->execute();
        $results = $sqlQuery->fetchAll(PDO::FETCH_OBJ);
        return $results;
    }

    public function filterAdverts($type)
    {
        $sqlQuery = $this->db->prepare("SELECT * FROM Adverts WHERE AdvertType='$type'");
        $sqlQuery->execute();
        $results = $sqlQuery->fetchAll(PDO::FETCH_OBJ);
        return $results;
    }
    public function fetchUserForAdvert($advertId, $userId)
    {
        $sqlQuery = $this->db->prepare("SELECT * FROM Adverts WHERE AdvertID='$advertId' AND UserID='$userId'");
        $sqlQuery->execute();
        $results = $sqlQuery->fetchAll(PDO::FETCH_OBJ);
        return $results;
    }
    public function searchAdverts($query)
    {
        $sqlQuery = $this->db->prepare("SELECT * FROM Adverts WHERE AdvertName LIKE '$query'");
        $sqlQuery->execute();
        $results = $sqlQuery->fetchAll(PDO::FETCH_OBJ);
        return $results;
    }
    public function saveAdvert($userID, $advertID)
    {
        $sqlQuery = $this->db->prepare("INSERT INTO SavedAdverts (UserID, AdvertID) VALUES ('$userID', '$advertID')");
        $results = $sqlQuery->execute();
        print_r($results);
        return $results;

    }
    public function returnSavedAdverts($userID)
    {
        $sqlQuery = $this->db->prepare("SELECT * FROM SavedAdverts INNER JOIN Adverts ON SavedAdverts.AdvertID  = Adverts.AdvertID WHERE SavedAdverts.UserID='$userID';");
        $sqlQuery->execute();
        $results = $sqlQuery->fetchAll(PDO::FETCH_OBJ);
        return $results;

    }
    public function expireAdverts()
    {
        $sqlQuery = $this->db->prepare("SELECT AdvertDate, AdvertExpiry FROM Adverts");
        $sqlQuery->execute();
        $adverts =  $sqlQuery->fetchAll(PDO::FETCH_ASSOC);
        foreach ($adverts as $row) {

                if($row['AdvertExpiry'] < date('Y-m-d')) {
                    $deleteQuery = $this->db->prepare("DELETE FROM Adverts WHERE AdvertID=:advertID");
                    $deleteQuery->bindparam(":advertID", $row['AdvertID']);
                    $deleteQuery->execute();
                    return true;
                } else return false;
            }
    }

    public function adminFilter($filter)
    {
        if($filter == "Adverts"){
            $sqlQuery = $this->db->prepare("SELECT * FROM Adverts");
            $sqlQuery->execute();
            $results = $sqlQuery->fetchAll(PDO::FETCH_OBJ);
            return $results;
        }
        if($filter == "Users"){
            $sqlQuery = $this->db->prepare("SELECT * FROM Users");
            $sqlQuery->execute();
            $results = $sqlQuery->fetchAll(PDO::FETCH_OBJ);
            return $results;
        }

    }
}