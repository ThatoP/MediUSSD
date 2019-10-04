<?php

// Reads the variables sent via POST from our gateway
$sessionId   = $_POST['sessionId'];
$serviceCode = $_POST["serviceCode"];
$phoneNumber = $_POST["phoneNumber"];
$text        = $_POST["text"];

if($text == "") {
    // when the user enters the ussd code, the application promtps him/her for the patients number
    // authentication is done automatically when the user dials the USSD code
    $processingCode = "00";
    $data = array(
        'processingCode'=>$processingCode,
        'phoneNumber'=> $phoneNumber,
        'text'=> $text
    );
    $result = sendRequest($data);
    $processedResult = json_decode($result,true);

    //$medicPhone = $processedResult["phone"]; 
    //$medicSurname = $processedResult["surname"];

    if(!(isset($processedResult["Message"]))){
        $response = "CON WELCOME TO MEDITECH APPLICATION \nPlease enter the patient number.";
    } else {
        $response = "END You do not have access rights to MediTech USSD application.";
    }
} else if(strlen(strpbrk($text,"M")) > 0) {
    // in the event that the user actually enters a number, we need to check whether it exists in the db
    $processingCode = "01"; //patient number is entered and sent to compare in the database
    $data = array(
        'processingCode'=>$processingCode,
        'phoneNumber'=>$phoneNumber,
        'text'=>$text
    );
    $result = sendRequest($data);
    $processedResult = json_decode($result,true);

        if(!(isset($processedResult["Message"]))){
            $response = "END Full name: ".$processedResult["name"]." ".$processedResult["surname"]."\n";
            $response .= "Blood Group: ".$processedResult["bloodgroup"]."\n";
            $response .= "Ailments: ".$processedResult["ailments"]."\n";
            $response .= "Allergies: ".$processedResult["allergies"]."\n";
            $response .= "Next of Kin: " .$processedResult["kinName"]."\n";
            $response .= "Kin Contacts: ".$processedResult["kinContact"]."\n";

            echo $processedResult["smsMessage"];
        } else{
            $response = "END There is no information to display on Patient ID ".$text;
        }

    $conn = null;
} 
else {
    $response = "END Invalid patient number. Please try again.";
}

function sendRequest($data){

    //converting the data to be sent to json
    $payload = json_encode($data);

    //the URL we are going to be sending to
    $url = "http://localhost:8080/MediTechServlet/UssdServlet";

    //1.) Creating the cURL resource
    $ch = curl_init($url);

    //2.) Set the options, including the URL to sent the request to
    curl_setopt($ch, CURLOPT_POST, true); //we are using the post method to pst data to the server
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

    //3.) Execute HTTP request
    $res = curl_exec($ch);

    //4.) Close cURL resource
    curl_close($ch);

    return $res;
}

header('Content-type: application/json');
echo $response;