<?php
  if ($_GET["logout"] == 1) {
    setcookie("courseplanner_user", NULL, NULL);
    setcookie("courseplanner_key", NULL, NULL);
  }
?>
<!-- This is the login page -->
<!DOCTYPE HTML>
<html>
  <head>
    <!-- specify the attributes of the page -->
    <style type="text/css">
      .whiteBox {
       background-color: rgb(255,255,255);
        
        width: 400px;
        height: 500px;
        
        /* Divide the width and heigh by 2 to center it on the page */
        margin-left: -200px;
        margin-top: -250px;
        
        position: fixed;
        
        top:  50%;
        left: 50%;
        
        border-width: 0px;

      }
      
      .Box {
        background-color: rgb(201,232,181);
        
        width: 400px;
        height: 360px;
        
        /* Divide the width and heigh by 2 to center it on the page */
        margin-left: -200px;
        margin-top: -180px;
        
        position: fixed;
        
        top:  50%;
        left: 50%;
        
        border-style: solid;
        border-width: 3px;
        border-color: #000;
      }
      
      
      .userpassText {
        margin-top: 18%;
        margin-left: 58px;
       /*font-weight: bold;*/
        font-family: calibri;
        font-size: 20px;
      }
      
      .loginfields {
        margin-top: -105px;
        margin-left: 158px;
        width: 100px;
        height: 500px;
      }
      
    </style>
    
  </head>
  
  <body>
    

   
    <!-- Draw the white box -->
    <div class="whiteBox"> 
     <p style ="font-family:calibri; font-size:25px; margin-top:20px; margin-left:54px "> Missouri S&T Course Planner</p>
      
    <div class="Box">
      
      <!-- Write Content Inside Box -->
      <p style ="font-family:calibri; font-size:20px; margin-top:16px; margin-left:28px "> Sign in</p>
  
     <div class="userpassText" >
        Username<br><br><br>Password

      <!-- Add Text boxes -->
      <div class="loginfields">
        <form action="gatekeeper.php" method="post">
          <input type="text" name="username" style="width: 120px; height: 25px; font-size: 15px" />
          <input type="password" name="password" style="width: 120px; height: 25px; margin-top: 50px" />
         
          <!-- Add Button -->
          <div style="margin-left: -152px; margin-top: 45px;">
          <input type="submit" value="Submit" style="height: 40px; width: 280px" ;/> </div>
          
        </form> 
      </div>
     
    </div>
      <!-- ERROR- incorrect input -->
      <?PHP 
      if ($_GET["error"] == 1) {
        echo("<font color=\"red\"><font size=4.0><div style=\"margin-top: 375px;\">The username and password you entered is incorrect.</div></font>");
        echo("<div style=\"margin-top:-224px\">");
      } 
      ?>
        <?PHP 
      if ($_GET["error"] == 1) {
        echo("</div>");
      } 
      ?>
 
  </body>
</html>