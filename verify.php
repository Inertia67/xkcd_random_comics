<?php
    require($_SERVER['DOCUMENT_ROOT']."/config/db.php");
    require($_SERVER['DOCUMENT_ROOT']."/api/class/comic.php");
    try {
        if(isset($_GET["reference_id"])){
            $urlString=$_GET["reference_id"];
            $urlString=base64_decode($urlString);
            $verificationCode=explode(',', $urlString);
      
            if(isset($verificationCode[0]) && isset($verificationCode[1])){
                $id = $verificationCode[0];
                $email = $verificationCode[1];
                //Fetch User details by id & email
                $emailCheck = "SELECT `is_verified` FROM xkcd_users WHERE id=$id AND email = '$email' LIMIT 1";
                $emailCheckResult =  $conn->query($emailCheck);
                if($emailCheckResult){
                    if ($emailCheckResult->num_rows > 0){
                        $userdata = $emailCheckResult->fetch_row();
                        $isVerify = $userdata[0];
                        
                        if($isVerify == 1){
                            echo '<script>alert("Your Email is already verified!");window.location.href="/"</script>';
                            exit;
                        }else{
                        
                            //Update is_verified
                            $set_verify="UPDATE xkcd_users SET is_verified=1 WHERE id=$id";
                            if($conn->query($set_verify) === TRUE){
                                $sendComic=new comic();
                                $sendComicData = $sendComic->fetch_random_comic(['email' => $email]);
                                echo '<script>alert("Your email has been successfully verified ! Random comic has already been sent to your inbox!");window.location.href="/"</script>';
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
    echo '<script>alert("Something went Wrong, please try after some time");</script>';
    exit;
?>