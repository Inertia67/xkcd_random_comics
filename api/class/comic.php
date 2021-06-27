<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;
    require_once($_SERVER['DOCUMENT_ROOT']."/vendor/autoload.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/config/config.php");//remove random-xkcd-comics for production
    class comic {
        function __construct() {
           
            $this->serverUrl =  (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
        }

        public function fetch_random_comic($userData) {
            try{
                $url = xkcdBaseApi."/random/comic/";
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_HEADER, true);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                $a = curl_exec($ch);

                $url = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
                curl_close($ch); 
                $postfix="info.0.json";
                $jurl= $url.$postfix ;
                $json = file_get_contents($jurl);
                $obj = json_decode($json);
                $comicData=['title'=>$obj->title,
                             'img'=>$obj->img,
                            'url'=>$url,
                            'email'=>$userData['email']
                        ];
                $comaicMailResponse = $this->send_mail($comicData);
                if($comaicMailResponse['is_success']){
                    return ['is_success'=>1];
                }else{
                    return ['is_success'=>0, 'message'=>$comaicMailResponse['message']];
                }
            }
            catch(Exception $e){
                return ['is_success'=>0,
                        'message'=>$e->getMessage()];
            }
        }

        public function send_mail($data){
            try{
                $headers = "MIME-Version: 1.0" . "\r\n"; 
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n"; 
                $to_email= $data['email'];
                $template = file_get_contents($_SERVER['DOCUMENT_ROOT']."/api/template/comic.html");
                $variables = array(
                    "{{img}}" => $data["img"],
                    "{{title}}" => $data["title"],
                    "{{url}}" => $data["url"],
                    "{{unsuburl}}" => "Adnan",
                    "{{serverUrl}}"=>$this->serverUrl,
                    "{{email}}"=>"$to_email"

                );
                foreach ($variables as $key => $value){
                    $template = str_replace($key, $value, $template);
                }
                $subject =  $data['title']." | XKCD";
                // send email
                // if(mail($to_email, $subject, $template, $headers)){
                //     return ['is_success'=>1];
                // }else{
                //     return ['is_success'=>0, 'message' => 'Error sending Mail'];
                // }
                $mail = new PHPMailer(true);
                $mail->isSMTP();            
                //Set SMTP host name                          
                $mail->Host = "smtp.gmail.com";
                //Set this to true if SMTP host requires authentication to send email
                $mail->SMTPAuth = true;                          
                //Provide username and password     
                $mail->Username = "finalcollegeproject@gmail.com";                 
                $mail->Password = "Kal@8961";                           
                //If SMTP requires TLS encryption then set it
                $mail->SMTPSecure = "tls";                           
                //Set TCP port to connect to
                $mail->Port = 587;                                   

                $mail->From = "finalcollegeproject@gmail.com";
                $mail->FromName = "Random Comic";

                $mail->addAddress($to_email);

                $mail->isHTML(true);

                $mail->Subject = "Subject Text";
                $mail->Body = "<i>Mail body in HTML</i>";
                // $mail->AltBody = "This is the plain text version of the email content";

                try {
                    $mail->send();
                    return ['is_success'=>1];
                } catch (Exception $e) {
                    var_dump($mail->ErrorInfo);
                    // echo "Mailer Error: " . $mail->ErrorInfo;
                    return ['is_success'=>0, 'message' => 'Error sending Mail'];
                }

            }
            catch(Exception $e){
                return ['is_success'=>0,
                'message'=>$e->getMessage()];
            }
        }

            public function send_verify_mail($data){
                try{
                    $headers = "MIME-Version: 1.0" . "\r\n"; 
                    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n"; 
                    $to_email= $data['email'];
                    $template = file_get_contents($_SERVER['DOCUMENT_ROOT']."/api/template/verify.html");
                    $hash=base64_encode($data['id'].",".$data['email']);
                    $variables = array(
                        "{{suburl}}" => "$hash",
                        "{{serverUrl}}"=>$this->serverUrl
                        
                    );
                    foreach ($variables as $key => $value){
                        $template = str_replace($key, $value, $template);
                    }
                    // send email
                    if(mail($to_email, "Verification mail | XKCD", $template, $headers)){
                        return ['is_success'=>1];
                    }else{
                        return ['is_success'=>0, 'message' => 'Error sending Mail'];
                    }
                }
                catch(Exception $e){
                    return ['is_success'=>0,
                    'message'=>$e->getMessage()];
                }
        }
    }
?> 