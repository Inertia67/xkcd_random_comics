<?php
    require("config/db.php");
    require("api\class\comic.php");
    try {
        if(isset($_GET["email_id"])){
            $unsubemail=$_GET["email_id"];
            //$urlString=base64_decode($urlString);
            //$verificationCode=explode(',', $urlString);
            // print_r($verificationCode);
            if(isset($unsubemail)){
               // $id = $verificationCode[0];
               // $email = $verificationCode[1];
                //Fetch User details by id & email
                $emailCheck = "SELECT `is_verified` FROM xkcd_users WHERE email = '$unsubemail' LIMIT 1";
                $emailCheckResult =  $conn->query($emailCheck);
                if($emailCheckResult){
                    if ($emailCheckResult->num_rows > 0){
                        $userdata = $emailCheckResult->fetch_row();
                        $isVerify = $userdata[0];
                        echo $isVerify;
                        if($isVerify == 1){
                            $set_unsub="UPDATE xkcd_users SET is_verified=0 WHERE email='$unsubemail'";
                            if($conn->query($set_unsub) === TRUE){
                                //$sendComic=new comic();
                                //$sendComicData = $sendComic->fetch_random_comic(['email' => $email]);
                                echo '<script>alert("You have been successfully unsubscribed !");window.location.href="/"</script>';
                                exit;
                        }
                        }
                    
                }
            }
        }
    }
}
    catch(Exception $e){

    }
    echo '<script>alert("You are not subscribed or Something went Wrong, please try after some time");</script>';
    exit;
?>