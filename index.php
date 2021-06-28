<?php header('Access-Control-Allow-Origin: *'); 
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    ini_set('smtp_server', 'smtp.gmail.com');
    ini_set('smtp_port', '587');
    ini_set('auth_username', 'finalcollegeproject@gmail.com');
    ini_set('auth_password', 'Kal@8961');
    ini_set('force_sender', 'finalcollegeproject@gmail.com');

        error_reporting(E_ALL);

    
?>

<!DOCTYPE html>
<html>
    <head>
        <title>XKCD Random Comics by Kishan Singh</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="./assets/css/styles.css">
    </head>
    <body>
        <div class="name-tag">
            <div class="hello">
                <div id="hello">XKCD</div>
                <div id="my-name-is">Random Comics</div>
            </div>
            <form class="form" name="subscribe_form">
                <div>
                    <input type="text" name="name" placeholder="Enter Name" required value=""/>
                </div>
                <div class="break"></div>
                <div>
                    <input type="email" name="email" placeholder="and Email" required value=""/>
                </div>
                <div>
                    <button type="submit" name="submit">Get Random XKCD Comics! <i class="fa fa-arrow-right"></i></button>
                </div>
            </form>
            <div class="underbar"></div>
        </div>
        <div class="greeting">
            <p></p>
            <!-- <p>Thanks for signing up! <span></span> Please verify your email to get a random XKCD Comics daily.</p>  -->
        </div>	
        <script src="./assets/js/main.js"></script> 
    </body>
</html>