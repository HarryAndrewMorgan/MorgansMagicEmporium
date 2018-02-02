<?php
class Advert extends PDO
{
    private $db;

    function __construct($connection)
    {
        $this->db = $connection;
    }

    public function createAdvert($name, $price, $description, $type)
    {
        try {

            $sqlQuery = $this->db->prepare("INSERT INTO Adverts(AdvertName,AdvertPrice,AdvertDescription, AdvertType) VALUES(:aname, :price, :description, :type)");
            $sqlQuery->bindparam(":aname", $name);
            $sqlQuery->bindparam(":price", $price);
            $sqlQuery->bindparam(":description", $description);
            $sqlQuery->bindparam(":type", $type);
            $sqlQuery->execute();

        } catch (PDOException $exception) {
            echo $exception->getMessage();
        }

    }
}