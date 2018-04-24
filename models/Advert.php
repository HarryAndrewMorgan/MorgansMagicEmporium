<?php
class Advert extends PDO
{
    //declare database
    private $db;

    //constructs teh database
    function __construct($_dbhandle)
    {
        $this->db = $_dbhandle;
    }

    //Creates an advert and enters it into the database using a prepared statement, preventing injection
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
    //general function to redirect user to a page
    public function redirect($url)
    {
        header("Location: $url");
    }
    //select all values from Adverts table and returns them as an object
    public function returnAdverts()
    {
        $sqlQuery = $this->db->prepare("SELECT * FROM Adverts");
        $sqlQuery->execute();
        $results = $sqlQuery->fetchAll(PDO::FETCH_OBJ);
        return $results;
    }
    //returns adverts specific to a userID to be displayed as their created adverts
    public function getAdvertForUser($userID)
    {
        $sqlQuery = $this->db->prepare("SELECT * FROM Adverts WHERE UserID=:userID");
        $sqlQuery->bindparam(":userID", $userID);
        $sqlQuery->execute();
        $results = $sqlQuery->fetchAll(PDO::FETCH_OBJ);
        return $results;
    }
    //returns a specific advert via its ID
    public function returnAdvert($advertID)
    {
        $sqlQuery = $this->db->prepare("SELECT * FROM Adverts INNER JOIN Users ON Adverts.UserID = Users.UserID WHERE AdvertID='$advertID'");
        $sqlQuery->execute();
        $results = $sqlQuery->fetchAll(PDO::FETCH_OBJ);
        return $results;
    }
    //filters adverts via their type for display when users filter adverts
    public function filterAdverts($type)
    {
        $sqlQuery = $this->db->prepare("SELECT * FROM Adverts WHERE AdvertType='$type'");
        $sqlQuery->execute();
        $results = $sqlQuery->fetchAll(PDO::FETCH_OBJ);
        if ($sqlQuery->rowCount() == 0) {
            $sqlQuery2 = $this->db->prepare("SELECT * FROM Adverts ORDER BY AdvertName ASC");
            $sqlQuery2->execute();
            $results = $sqlQuery2->fetchAll(PDO::FETCH_OBJ);
        }

        return $results;
    }
    //displays adverts when given 2 parameters that equal the AdvertID and the foreign key UserID
    public function fetchUserForAdvert($advertId, $userId)
    {
        $sqlQuery = $this->db->prepare("SELECT * FROM Adverts WHERE AdvertID='$advertId' AND UserID='$userId'");
        $sqlQuery->execute();
        $results = $sqlQuery->fetchAll(PDO::FETCH_OBJ);
        return $results;
    }
    //allows the user to filter adverts by searching for a string matching to the name of an advert
    public function searchAdverts($query)
    {
        $sqlQuery = $this->db->prepare("SELECT * FROM Adverts WHERE AdvertName LIKE '$query'");
        $sqlQuery->execute();
        $results = $sqlQuery->fetchAll(PDO::FETCH_OBJ);
        return $results;
    }
    //Allows the user to save an advert which is stored as their userID and advertID in the SavedAdverts table.
    public function saveAdvert($userID, $advertID)
    {
        $sqlQuery = $this->db->prepare("INSERT INTO SavedAdverts (UserID, AdvertID) VALUES ('$userID', '$advertID')");
        $results = $sqlQuery->execute();
        print_r($results);
        return $results;
    }
    //returns the adverts from SavedAdverts that a specific user has saved to their account
    public function returnSavedAdverts($userID)
    {
        $sqlQuery = $this->db->prepare("SELECT * FROM SavedAdverts INNER JOIN Adverts ON SavedAdverts.AdvertID  = Adverts.AdvertID WHERE SavedAdverts.UserID='$userID';");
        $sqlQuery->execute();
        $results = $sqlQuery->fetchAll(PDO::FETCH_OBJ);
        return $results;

    }
    //Runs through the adverts table and if the date of expiry has past the current date, removes from the table. This is called upon the page loading so no expired adverts are displayed
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
    //returns all users and adverts when given a filter parameter, this is used by the admin so that all website information can be retrieved in one function
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
    //checks whether an advert has been saved by a user by checking the SavedAdverts table for a tuple corresponding to a given user and advertID
    public function isSaved($userID, $advertID)
    {
        $sqlQuery = $this->db->prepare("SELECT * FROM SavedAdverts WHERE UserID=:userID AND AdvertID=:advertID LIMIT 1");
        $sqlQuery->bindparam(":advertID", $advertID);
        $sqlQuery->bindparam(":userID", $userID);
        $sqlQuery->execute();
        $sqlQuery->fetchAll(PDO::FETCH_OBJ);
        if($sqlQuery->rowCount() > 0)
        {
            return true;
        } else return false;
    }
    //removes a saved advert from a users saved adverts list
    public function removeSaved($advertID, $userID)
    {
        $sqlQuery = $this->db->prepare("DELETE FROM SavedAdverts WHERE AdvertID=:advertID AND UserID=:userID");
        $sqlQuery->bindparam(":advertID", $advertID);
        $sqlQuery->bindparam(":userID", $userID);
        $sqlQuery->execute();
    }
    //removes a specific tuple from the adverts table when provided the adverts ID
    public function deleteAdvert($advertID)
    {
        $sqlQuery = $this->db->prepare("DELETE FROM Adverts WHERE AdvertID=:advertID");
        $sqlQuery->bindparam(":advertID", $advertID);
        $sqlQuery->execute();
    }
    //deletes a user from the user table when provided the adverts ID
    public function deleteUser($userID)
    {
        $sqlQuery = $this->db->prepare("DELETE FROM Users WHERE UserID=:userID");
        $sqlQuery->bindparam(":userID", $userID);
        $sqlQuery->execute();
    }
    //redirect function for admin use
    public function redirectAd($url)
    {
        header("Location: $url");
    }
    public function liveSearch($searchString)
    {
        $sqlQuery = $this->db->prepare("SELECT AdvertName FROM Adverts WHERE AdvertName LIKE '$searchString%'");
        $sqlQuery->bindparam(":searchString", $searchString);
        $sqlQuery->execute();
        $array = $sqlQuery->fetchAll(PDO::FETCH_ASSOC);
        //print_r($array);
        //foreach($array as $row) {
           // $adverts[] = array($row['AdvertName']);
           // print_r($adverts);
        print_r($array);
            return $array;
        //}
    }
}