<html>
    
<?php
// We rely on sessions hardly so it's essential
session_start();
// Bring functions on!
require_once "functions.php";
// And DB connection too
include "connect.php";
?>
    
    <head>
        <meta charset="UTF-8">
        <title>Test Job: start</title>
    </head>
    
<style>
body {padding: 10px 20px 10px 20px;}
input {border: none; border-bottom: 1px solid #dedede; margin-bottom: 8px; font-size: 20px;}
</style>  
    
    <body>
        <h1>Start page</h1>
        <p>Here you will enjoy something. But before enjoying you'll have to 
        sign in or create a profie here.</p>
        <div style="display: flex; flex-direction: row; ">
            <div style="padding: 10px;">
                <h3>Sign in</h3>
                <p>If you already have an account here, <br>just enter credentials to proceed.</p>
                <form method="post" action="">
                    Phone number or email:<br>
                    <input type="text" name="logcred"><br>
                    Your password here:<br>
                    <input type="password" name="logpasswd"></br><br>
                    <!-- Captcha from Yandex code -->
                    <script src="https://smartcaptcha.yandexcloud.net/captcha.js" defer></script>
                    <div id="captcha-container" class="smart-captcha" 
                         data-sitekey="SITEKEY" style="height: 100px;">
                         <input type="hidden" name="smart-token" value="TOKEN">
                    </div>
                    <!-- Yandex ends -->
                    <br><br><br>
                    <input type="submit" style="background-color: #cecece;">
                </form>
            </div>
            <div style="padding: 10px;">
                <h3>Create an account</h3>
                <p>If you're new here, you can<br> create an account. Just fill in a form.</p>
                <form method="post" action="">
                    New login:<br><input type="text" name="newlogin"><br>
                    Your phone:<br><input type="text" name="newphone"><br>
                    Your email:<br><input type="text" name="newemail"><br>
                    Create password:<br><input type="password" name="newpassword"><br>
                    Confirm password:<br><input type="password" name="cnfpassword"><br>
                    <br>
                    <input type="submit" style="background-color: #cecece;">
                </form>
            </div>
        </div>

</body>
</html>

<?php
// Captcha
        define('SMARTCAPTCHA_SERVER_KEY', '<ключ_сервера>');
        $token = $_POST['smart-token'];
        if (check_captcha($token)) {$_SESSION['captcha'] = "Passed";} 
        else {$_SESSION['captcha'] = "Robot";}
        
// Register an account section
        // Get reg variables
         $newlogin = htmlentities(mysqli_real_escape_string($link,$_POST['newlogin']));
         $newphone = htmlentities(mysqli_real_escape_string($link,$_POST['newphone']));
         $newemail = htmlentities(mysqli_real_escape_string($link,$_POST['newemail']));
         $newpassword = htmlentities(mysqli_real_escape_string($link,$_POST['newpassword']));
         $cnfpassword = htmlentities(mysqli_real_escape_string($link,$_POST['cnfpassword']));
         // Use function to register
         if(!empty($newlogin) && !empty($newphone) && !empty($newemail) && !empty($newpassword) && !empty($cnfpassword))
            {$regs = regNewAccount($link,$newlogin,$newphone,$newemail,$newpassword,$cnfpassword);}
        
// Authentification section
        // Get our variables from a form  
        $logcred = htmlentities(mysqli_real_escape_string($link,$_POST['logcred']));
        $logpasswd = htmlentities(mysqli_real_escape_string($link,$_POST['logpasswd']));
        // Use function to authorise
        // Without captcha
        authCheck($link,$logcred,$logpasswd);
        // With captcha
        /*
        if($_SESSION['captcha']==="Passed")
            {
                authCheck($link,$logcred,$logpasswd);
            }
        else 
            {
                echo "No robots allowed to login";
                header(Refresh:0, url=index.php;
            }
        */
        

       

            