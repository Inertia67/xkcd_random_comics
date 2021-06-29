<?php
header('Content-Type: application/json');
require('../../config/db.php');
require("../class/comic.php");

try{
    $json = file_get_contents('php://input');
    // Converts it into a PHP object
    $data = json_decode($json);
    if(!empty($data)){
        $name = trim(htmlspecialchars($data->name));
        $email = trim(htmlspecialchars($data->email));
        $email = strtolower($email);
        // validation
        if($name != "" 
            && $email != "" 
            && filter_var($email, FILTER_VALIDATE_EMAIL)
        ){
            //check if email exist or not
            $emailCheck = "SELECT `id`, `is_verified` FROM xkcd_users WHERE email = '$email' LIMIT 1";
            $emailCheckResult =  $conn->query($emailCheck);
            if($emailCheckResult){
                $sentVerifyMail = 0;
                if ($emailCheckResult->num_rows > 0){
                    //Check if user verified or not
                    $userdata = $emailCheckResult->fetch_row();
                    $isVerify = $userdata[1];
                    if($isVerify == 1){
                        //send rcomics
                        $sendComic=new comic();
                        $sendComicData = $sendComic->fetch_random_comic(['email' => $email]);
                        if($sendComicData['is_success'] === 1){
                            http_response_code(200);
                            $response=['status'=>200,'message'=> "Random Comic sent to your email, please check your inbox"];
                            echo json_encode($response);
                            exit;
                        }
                    }else{
                        $last_id =$userdata[0];
                        $sentVerifyMail = 1;
                    }
                
                }else {
                    $newSub= "INSERT INTO xkcd_users (`name`, `email`) VALUES ('$name','$email')";
                    if ($conn->query($newSub) === TRUE){
                        $last_id = $conn->insert_id;
                        $sentVerifyMail = 1;
                    }
                }
                if($sentVerifyMail === 1){
                    $sendComic=new comic();
                    $verifyMailResult = $sendComic->send_verify_mail(['email' => $email,'name'=>$name,'id'=>$last_id]);
                    if($verifyMailResult['is_success'] === 1){
                        http_response_code(200);
                        $response=['status'=>200,'message'=>'Please Verify Your Email! Check your inbox for Verification mail'];
                        echo json_encode($response);
                        exit;
                    }
                }
            }
        }
    }

    //Error
    // var_dump($conn->connect_error);/]
    http_response_code(500);
    $response=['status'=>500,'message'=>'Internal Error'];
    echo json_encode($response);

}catch(Exception $e){
    http_response_code(500);
    echo json_encode(['status'=>500,
                        'message'=>$e->getMessage()]);
}
?>