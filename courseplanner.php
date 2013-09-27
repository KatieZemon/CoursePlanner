<?php 

 // Connects to the database
mysql_connect("127.0.0.1", "jakekra1_katie", "database238") or die(mysql_error()); 
mysql_select_db("jakekra1_katie") or die(mysql_error()); 

 /**** Verify that you are logged in *****/
 // Checks the cookie to be sure you are logged in
if(isset($_COOKIE['courseplanner_user'])) {
  $username = $_COOKIE['courseplanner_user']; 
  $pass = $_COOKIE['courseplanner_key']; 
  $check = mysql_query("SELECT * FROM Student WHERE username = '$username'")or die(mysql_error()); 

  while($info = mysql_fetch_array( $check )) {
    // If the cookie has the wrong password, you are taken to the login page 
    if ($pass != $info['password']) {
      header("Location: index.php"); 
    } 

  }
}// If you are not logged in, you are redirected to the login screen
 else {			 
   header("Location: index.php"); 
}

// check database for username
$check = mysql_query("SELECT * FROM Student WHERE username='".strtolower($_COOKIE['courseplanner_user'])."';");

// username was not found
$check2 = mysql_num_rows($check);
if ($check2 == 0) {
  //header("Location: ./login.php?error=1");
 die('That user does not exist in our database.');
}

$student = mysql_fetch_array( $check );
$CURRENT_TERM = 35;
$TERM_NAMES = array("Spring", "Summer", "Fall");

//while($info = mysql_fetch_array( $check )) {
//  $info['password'] = stripslashes($info['password']);
//}
  
 ?> 
 
 
 <!-- ****************** Main Content ******************* -->
 <html>
 
 <head>
  <style type="text/css">
    A:link {text-decoration: none; color: rgb(58,150,222);}
    A:visited {text-decoration: none; color: rgb(58,150,222);}
    A:active {text-decoration: none; color: rgb(58,150,222);}
    A:hover {text-decoration: none; color: rgb(58,150,222);}
  </style>
  
  
  <!-- specify the attributes of the page -->
    <style type="text/css">

      .Box {
        background-color: rgb(255,255,255);
        
        width: 600px;
        height: 500px;
        
        margin-left: -300px;
        
        position: absolute;
        left: 50%;
        
        border-style: solid;
        border-width: 4px;
        border-color: #000;
      }
	  
      </style>
  

  

    <title>S&T Course Planner</title>
	
 </head>
 
 
 <body>

 
 
<font size = 6><b>MISSOURI S&T Course Planner </font></b>

 <!-- Student First name, MI, Last name  -->
 <div align="right" style="margin-top:-30px">
<?php echo($student['fname']." ".substr($student['mname'], 0, 1)." ".$student['lname']); ?>
 <br>
 
<!-- Sign Out -->
<a href="./login.php?logout=1" link="blue" text="blue" > Sign Out </a>
</div>

 <HR>
 <br>
<!-- Select Term -->
Term:   
 <select id="select" onchange="document.getElementById('textbox').value=document.getElementById('select').value" style="margin-left:20px">
<option name="thisTerm"><?php echo($TERM_NAMES[$CURRENT_TERM % 3]." ".(intval($CURRENT_TERM/3)+2000));?></option>
<option name="nextTerm" value="apples"><?php echo($TERM_NAMES[$CURRENT_TERM % 1]." ".(intval($CURRENT_TERM/3)+2001));?></option>
<option name="futureTerm" value="apples"><?php echo($TERM_NAMES[$CURRENT_TERM % 2]." ".(intval($CURRENT_TERM/3)+2001));?></option>
</select>

<br><br>



<!-- 
*******************************
Select Courses
************************************* 
-->
<center>
<TABLE> <TR><TD><center>
<!-- Select Course -->
 <?php   include ('selectCourse.php');?>
 <br><br><br>
 </center></TD></TR>
 <TR><TD><center>
 
<!-- Select Preferences -->
<?php   include ('preferenceUI.php');?>
 </center></TD></TR>
</TABLE>
</center>
<br> <br><br><br>
<!-- Generate Schedule -->
<?php   include('generateSchedule.php');?>
 
<br><br><br>


 </body>
 </html>
 
 
 
 
 
 