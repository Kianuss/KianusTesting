<?php
   ob_start();
   session_start();
?>

<html lang = "en">
   
   <head>
      <title>Tournament Controller</title>
      <link href = "css/bootstrap.min.css" rel = "stylesheet">
      
      <style>
         body {
            padding-top: 40px;
            padding-bottom: 40px;
            background-color: #ADABAB;
         }
         
         .form-signin {
            max-width: 330px;
            padding: 15px;
            margin: 0 auto;
            color: #017572;
         }
         input{
             width:100%;
             background-color:#909090;
             border-width: 0;
         }
         .form-signin .form-signin-heading,
         .form-signin .checkbox {
            margin-bottom: 10px;
         }
         
         .form-signin .checkbox {
            font-weight: normal;
         }
         
         .form-signin .form-control {
            position: relative;
            height: auto;
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
            padding: 10px;
            font-size: 16px;
         }
         
         .form-signin .form-control:focus {
            z-index: 2;
         }
         
         .form-signin input[type="text"] {
            margin-bottom: 5px;
            border-bottom-right-radius: 0;
            border-bottom-left-radius: 0;
            border-color:#017572;
            border-radius: 10px;
         }
         
         .form-signin input[type="password"] {
            margin-bottom: 10px;
            border-top-left-radius: 0;
            border-top-right-radius: 0;
            border-color:#017572;
            border-radius: 10px;
            
         }
         
         h2{
            text-align: center;
            color: #017572;
         }
         .yes{
             width:50%;
             margin: 25%;
             margin-top:100px;
             margin-bottom:100px;
         }
         button{
             width:100%;
             background-color:#909090;
             border-width: 0;
             height:50px;
             border-radius:10px;
         }
      </style>
      
   </head>
	
   <body>
      <div class=Yes>
      <h2>Enter Username and Password</h2> 
      <div class = "container form-signin">
         
         <?php
            if($_SESSION['valid'] == true)
               if($_SESSION['username'] != 'YhIOiQyCfGz3GAG') {session_destroy();}else{
               header("Location: ./");}
            $msg = '';
            
            if (isset($_POST['login']) && !empty($_POST['username']) 
               && !empty($_POST['password'])) {
				
               if ($_POST['username'] == 'PASS' && 
                  $_POST['password'] == 'PASS') {
                  $_SESSION['valid'] = true;
                  $_SESSION['timeout'] = time();
                  $_SESSION['username'] = 'YhIOiQyCfGz3GAG';
                  header("Location: ./");
               }else {
                  $msg = 'Wrong username or password';
               }
            }
         ?>
      </div> <!-- /container -->
      
      <div class = "container">
      
         <form class = "form-signin" role = "form" 
            action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']); 
            ?>" method = "post">
            <h4 class = "form-signin-heading"><?php echo $msg; ?></h4>
            <input type = "text" class = "form-control" 
               name = "username" placeholder = "Username" 
               required autofocus></br>
            <input type = "password" class = "form-control"
               name = "password" placeholder = "Password" required><br>
            <button class = "btn btn-lg btn-primary btn-block" type = "submit" 
               name = "login">Login</button>
         </form>
			
         
      </div> 
      </div> 
   </body>
</html>