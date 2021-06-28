<?php
    require($_SERVER['DOCUMENT_ROOT']."/config/db.php");
    require($_SERVER['DOCUMENT_ROOT']."/api/class/comic.php");
    try {
        if(isset($_GET["id"])){
            $urlid=$_GET["id"];
            $dehashed=base64_decode($urlid);
           
            if(isset($dehashed)){
      
                //Fetch User details by id & email
                $emailCheck = "SELECT `is_verified` FROM xkcd_users WHERE email = '$dehashed' LIMIT 1";
                $emailCheckResult =  $conn->query($emailCheck);
                if($emailCheckResult){
                    if ($emailCheckResult->num_rows > 0){
                        $userdata = $emailCheckResult->fetch_row();
                        $isVerify = $userdata[0];
                       
                        if($isVerify == 1){
                            $set_unsub="UPDATE xkcd_users SET is_verified=0 WHERE email='$dehashed'";
                            if($conn->query($set_unsub) === TRUE){
                                
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
    echo '<script>alert("You are not subscribed or Something went Wrong, please try after some time");window.location.href="/"</script>';
    exit;
?>