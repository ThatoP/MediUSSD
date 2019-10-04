<?php
	$servername = "localhost";
	$username = "postgres";
	$pass = "Tyler$0ft";
	$myDB = "MediTech";
	$pid = "M1234";

	$myObj->name = "John";
$myObj->age = 30;
$myObj->city = "New York";
$myObj->processingCode = "01";

$myJSON = json_encode($myObj);

echo $myJSON;


	// try {
 //        //create a connection to the database
 //        $conn = new PDO("pgsql:host=$servername;dbname=$myDB", $username, $pass);

 //        // set the PDO error mode to exception
 //        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

 //        //set up the database query statement
 //        $dbop = "SELECT * FROM public.patients WHERE pid = 'M1234'";

 //        $stmt = $conn->query($dbop);
 //        $row = $stmt->fetch(PDO::FETCH_ASSOC);

 //        if(count($row)>0){
 //            echo "Full name: ".$row["name"]." ".$row["surname"]."<br>";
 //            echo "Blood Group: ".$row["bloodgroup"]."<br>";
 //            echo "Allergies: ".$row["allergies"]."<br>";
 //            echo "Next of Kin: " .$row["ksurname"]."<br>";
 //            echo "Kin Contacts: ".$row["kcontact"]."<br>";
 //        }else{
 //            echo "END There is no information to display on Patient ID ".$text;
 //        }
 //    }catch(PDOException $e){
 //        echo "Connection failed: " . $e->getMessage();
 //    }
?>