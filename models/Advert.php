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

    public function countAdverts()
    {
        $sqlQuery = $this->db->prepare("SELECT COUNT(*) FROM Adverts");
        $sqlQuery->execute();
        $rows = $sqlQuery->fetch(PDO::FETCH_ASSOC);
        return $rows;

    }
    public function printAdverts()
    {
        $numberOfRows = $this->countAdverts();
        $advert = $this->fetchAllAdverts();
        for($i = 0; $i <= $numberOfRows; $i++)
        {
            echo $advert['AdvertName'];
            echo $advert['AdvertPrice'];
            echo $advert['AdvertDescription'];
            echo $advert['AdvertType'];
            return $advert;

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
}