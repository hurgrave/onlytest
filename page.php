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
        <title>Test Job: profile page</title>
    </head>

        <style>
        body {padding: 10px 20px 10px 20px;}
        input {border: none; border-bottom: 1px solid #dedede; margin-bottom: 8px; font-size: 20px;}
        </style>    
    
    
    <body>
        <h1>Profile page</h1>
        <p><a href="index.php"><--back to index</a></p>
        <p>If you have logged in, you can watch profiles. And edit them. 
        So be careful!</p>

        <form method="post" action="" >
            Your login is (you may change it right here): <br>
            <input type="text" name="editlogin" value="<?php if($_SESSION['auth']==1){echo $_SESSION['login'];} ?>"><br>
            Your phone number is (also editable):<br>
            <input type="text" name="editphone" value="<?php if($_SESSION['auth']==1){echo $_SESSION['phone'];} ?>"><br>
            Your email looks like (and you may change it):<br>
            <input type="text" name="editemail" value="<?php if($_SESSION['auth']==1){echo $_SESSION['email'];} ?>"><br>
            You can submit a new password:<br>
            <input type="password" name="editpasswd" value=""><br>
            Retype password if you're changing it:<br>
            <input type="password" name="cnfpasswd" value=""><br>
            <input type="submit" style="background-color: #dedede;">
        </form>


        <?php 
        // Here I was thinking to use function taking userinfo from DB via 
        // MySQL but reconsidered and used session instead. 3 strings of session
        // array elements look better then an SQL request in terms of speed.
        if(!empty($_POST['editlogin']) 
               && !empty($_POST['editphone']) 
               && !empty($_POST['editemail'])) 
                    {
                        $editlogin = htmlentities(mysqli_real_escape_string($link,$_POST['editlogin']));
                        $editphone = htmlentities(mysqli_real_escape_string($link,$_POST['editphone']));
                        $editemail = htmlentities(mysqli_real_escape_string($link,$_POST['editemail']));
                        $editpassword = htmlentities(mysqli_real_escape_string($link,$_POST['editpasswd']));
                        $editcnfpassword = htmlentities(mysqli_real_escape_string($link,$_POST['cnfpasswd']));
                    }
        else {$_SESSION['report']="One or more fields is not set. Fill all of them.";}
        
        $aa = editUserInfo($link,$editlogin,$editphone,$editemail,$editpassword,$editcnfpassword);
        ?>
        
        
</body>
</html>

        