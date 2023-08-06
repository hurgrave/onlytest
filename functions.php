<?php
//echo "I'm functions.php and I'm here!";

// Check authorization and redirect
function authCheck($link,$logcred,$logpasswd) 
    {
    // Check if login and passwd hash matches, then write session
    
        // We have variable input, so time to check what is what
        if(preg_match('#^\+#',$logcred)===1){$condition = "`phone`";}
        if(preg_match('#\@#',$logcred)===1){$condition = "`email`";}
    
        // Look through MySQL table if there is a login and password
        $que1 = "SELECT `login`,`phone`,`email`,`password` FROM `testusers` WHERE ".$condition."='$logcred'";
        $ans1 = mysqli_query($link,$que1) or die();
        $res1 = mysqli_fetch_assoc($ans1);
        var_dump($res1);
        // Login and password hash are stored in $res1 now
        
        // Time to verify password
        $passhsh = password_verify($logpasswd,$res1['password']);
        
        // If all OK, then write a session
        if( ($logcred===$res1['phone'] or $logcred===$res1['email']) and ($passhsh===true))
            {
                $_SESSION['login'] = $res1['login'];
                $_SESSION['phone'] = $res1['phone'];
                $_SESSION['email'] = $res1['email'];
                $_SESSION['auth'] = 1;
                $_SESSION['report'] = 'Login successful';
            }
            
        // Redirect
        header('Location: page.php');
    }    

// Update database when userinfo is edited
function editUserInfo($link,$editlogin,$editphone,$editemail,$editpassword,$editcnfpassword)
    {
        // Support code with a walking stick
        $curlogin = $_SESSION['login'];
        
        // Check for password alteration
        if(!empty($editpassword) && $editpassword==$editcnfpassword)
                {$passcheck=true;}
        
        // Main routine
        if($_SESSION['auth']==1 
           && !empty($editlogin)
           && !empty($editphone)
           && !empty($editemail))
                {
                    if($passcheck)
                                {
                                    $hashnewpass = password_hash($editpassword, PASSWORD_DEFAULT);
                                    $passcondition = ", `password` = '$hashnewpass'";
                                }
                    else {$passcondition = "";}
                    // Now work me a query and execute it
                    $que2 = "UPDATE `testusers` SET `login` = '$editlogin',
                            `phone` = '$editphone', `email` = '$editemail'".
                            $passcondition." WHERE `login`='$curlogin'";
                    $ans2 = 
                }
                  
        else {$_SESSION['report']=="You cannot afford empty fields or edit page if unauthorized";}
    }    
        /*/* 
        // Write new values to session
        if($ans2 != false)
            {
                $_SESSION['login'] = $editlogin;
                $_SESSION['phone'] = $editphone;
                $_SESSION['email'] = $editemail;
                $_SESSION['auth'] = 1;
                $_SESSION['report'] = 'Altered successfuly!';
                echo "Done?";
                header("Refresh:0, url=page.php");
            } */
    

function checkUnique($link,$login,$phone,$email)
    {
        // Check login
        $alque1 = "SELECT `login` FROM `testusers` WHERE `login` = '$login'";
        $alres1 = mysqli_query($link,$alque1);
        $fetch1 = mysqli_fetch_assoc($alres1);
        if ($fetch1['login'] != $login) {$logfree=true;}
        else {$_SESSION['report'] = "Such login already exists";}
        // Check phone
        $alque2 = "SELECT `phone` FROM `testusers` WHERE `phone` = '$phone'";
        $alres2 = mysqli_query($link,$alque2);
        $fetch2 = mysqli_fetch_assoc($alres2);
        if ($fetch2['phone'] != $phone) {$phofree=true;}
        else {$_SESSION['report'] = "Such phone number already exists";}
        // Check email
        $alque3 = "SELECT `email` FROM `testusers` WHERE `email` = '$email'";
        $alres3 = mysqli_query($link,$alque3);
        $fetch3 = mysqli_fetch_assoc($alres3);
        if ($fetch3['email'] != $email) {$emlfree=true;}
        else {$_SESSION['report'] = "Such email already exists";}
        if($logfree && $phofree && $emlfree) {return true;}
    }
    
function regNewAccount($link,$newlogin,$newphone,$newemail,$newpassword,$cnfpassword)
{
    // Check for uniqueness and non-emptiness and password match
    if(checkUnique($link,$newlogin,$newphone,$newemail)===true 
       && !empty($newlogin)
       && !empty($newphone)
       && !empty($newemail)
       && !empty($newpassword)
       && !empty($cnfpassword)
       && $newpassword == $cnfpassword);
    // MySQL time
        {
            // Password hash
            $inspasswd = password_hash($newpassword, PASSWORD_DEFAULT);
            //Query
            $que3 = "INSERT INTO `testusers` (`login`,`phone`,`email`,`password`)
            VALUES ('$newlogin','$newphone','$newemail','$inspasswd')";
            // Request
            $ans3 = mysqli_query($link,$que3) or die();
            // Write results to session
            $_SESSION['login'] = $newlogin;
            $_SESSION['phone'] = $newphone;
            $_SESSION['email'] = $newemail;
            $_SESSION['auth'] = 1;
            $_SESSION['report'] = 'Account created successfuly!';
            // Redirect
            header('Location: page.php');
        }
}
            
// Captcha from Yandex
function check_captcha($token) 
    {
        $ch = curl_init();
        $args = http_build_query([
            "secret" => SMARTCAPTCHA_SERVER_KEY,
            "token" => $token,
            "ip" => $_SERVER['REMOTE_ADDR'], // Нужно передать IP-адрес пользователя.
                                             // Способ получения IP-адреса пользователя зависит от вашего прокси.
        ]);
        curl_setopt($ch, CURLOPT_URL, "https://smartcaptcha.yandexcloud.net/validate?$args");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 1);

        $server_output = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpcode !== 200) {
            $_SESSION['captcha'] = "Allow access due to an error: code=$httpcode; message=$server_output\n";
            return true;
        }
        $resp = json_decode($server_output);
        return $resp->status === "ok";
    }



            
