<?PHP
// If correct username and password are entered, send user to class selection page
mysql_connect("127.0.0.1", "jakekra1_katie", "database238") or die(mysql_error()); 
mysql_select_db("jakekra1_katie") or die(mysql_error()); 

// check database for username
$check = mysql_query("SELECT * FROM Student WHERE username='".strtolower($_POST["username"])."';");

// username was not found
$check2 = mysql_num_rows($check);
if ($check2 == 0) {
  header("Location: ./login.php?error=1");
}

/****** Username was found, now check for the password *******/
while($info = mysql_fetch_array( $check )) {
  $_POST['password'] = stripslashes($_POST['password']);
  $info['password'] = stripslashes($info['password']);
  $_POST['password'] = md5($_POST['password']);

  // Password is incorrect- throw error
  if ($_POST['password'] != $info['password']) {
    header("Location: ./login.php?error=1");
  }

  // Password is correct
  else { 
    // if login is ok then we add a cookie 
    $_POST['username'] = stripslashes($_POST['username']); 
    $hour = time() + 3600;
    // Stores the session as a cookie
    setcookie("courseplanner_user", $_POST['username'], $hour); 
    setcookie("courseplanner_key", $_POST['password'], $hour);	
    //setcookie("courseplanner_name", $info['fname']. " ". $info['lname']);

    // Go to the main page
    header("Location: courseplanner.php"); 
  }
}

?>
