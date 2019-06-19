<?php
require_once('AfricasTalkingGateway.php');
require_once('configs.php');

// Reads the variables sent via POST from our gateway
$sessionId   = $_POST["sessionId"];
$serviceCode = $_POST["serviceCode"];
$phoneNumber = $_POST["phoneNumber"];
$text        = $_POST["text"];

if($text == "") {
    // when the user enters the ussd code, the application promtps him/her for the patients number
    // authentication is done automatically when the user dials the USSD code
    $output = ""; $who = "";
    
    try {
        //create a connection to the database
        // note the pgsql here is to point to a postgreSQL database
        $conn = new PDO("pgsql:host=$servername;dbname=$myDB", $username, $pass);

        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        //set up the database query statement
        $dbop = "SELECT * FROM public.paramedics WHERE phone = '$phoneNumber'";

        // dump the result set into the appropriate variables
        $stmt = $conn->query($dbop);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $output = $row["phone"];
        $who = $row["surname"];


    }catch(PDOException $e){
        echo "Connection failed: " . $e->getMessage();
    }

    if($phoneNumber == $output){
        $response = "CON WELCOME TO MEDITECH APPLICATION, $who \n Please enter the patient number.";
    }else{
        $response = "END You do not have access rights to MediTech USSD application.";
    }
    $conn = null;

} else if(strlen(strpbrk($text,"M")) > 0) {
    // in the event that the user actually enters a number, we need to check whether it exists in the db

    try {
        //create a connection to the database
        $conn = new PDO("pgsql:host=$servername;dbname=$myDB", $username, $pass);

        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        //set up the database query statement
        $dbop = "SELECT * FROM public.patients WHERE pid = '$text'";

        $stmt = $conn->query($dbop);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if(count($row)>0){
            $response = "END Full name: ".$row["name"]." ".$row["surname"]."\n";
            $response .= "Blood Group: ".$row["bloodgroup"]."\n";
            $response .= "Allergies: ".$row["allergies"]."\n";
            $response .= "Next of Kin: " .$row["ksurname"]."\n";
            $response .= "Kin Contacts: ".$row["kcontact"]."\n";
        }else{
            $response = "END There is no information to display on Patient ID ".$text;
        }
    }catch(PDOException $e){
        echo "Connection failed: " . $e->getMessage();
    }

    $conn = null;
} 
else {
    $response = "END Invalid patient number. Please try again.";
}

// Echo the response back to the API
header('Content-type: text/plain');
echo $response;