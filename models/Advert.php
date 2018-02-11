<?php
class Advert extends PDO
{
    private $db;

    function __construct($_dbhandle)
    {
        $this->db = $_dbhandle;
    }

    public function createAdvert($name, $price, $description, $type, $userID, $picture)
    {
        {
            try {
                $sqlQuery = $this->db->prepare("INSERT INTO Adverts(AdvertName,AdvertPrice,AdvertDescription, AdvertType, UserID, PhotoName) VALUES(:aname, :price, :description, :type, :userID, :picture)");
                $sqlQuery->bindparam(":aname", $name);
                $sqlQuery->bindparam(":price", $price);
                $sqlQuery->bindparam(":description", $description);
                $sqlQuery->bindparam(":type", $type);
                $sqlQuery->bindparam(":userID", $userID);
                $sqlQuery->bindparam(":picture", $picture);
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
}