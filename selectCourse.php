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

//while($info = mysql_fetch_array( $check )) {
//  $info['password'] = stripslashes($info['password']);
//}
  
 ?> 
 
 

<STYLE TYPE="text/css">

</STYLE>

<!-- Button Stuff -->
<script language="JavaScript">

  <?php
    $connection = mysql_connect("127.0.0.1", "jakekra1_katie", "database238") or die(mysql_error()); 
    mysql_select_db("jakekra1_katie") or die(mysql_error()); 
      
      $list = mysql_query("SELECT department,catalogNumber,name FROM CourseSelected CS, Course C WHERE CS.studentID = ". $student['studentID']." AND CS.courseID = C.courseID");
      $selected_list = "";
      
      echo("selectedlist = [");
      while($row = mysql_fetch_array($list)) {
    
      $selected_list .= "'".$row['department']." ".$row['catalogNumber']." - ".$row['name']."', ";
 
      }
      $selected_list = substr($selected_list, 0, strlen($selected_list)-2);
      echo("$selected_list];\n");
    
   
    mysql_close($connection);
  ?>

upImage = new Image();
upImage.src = "button2.png";
downImage = new Image();
downImage.src = "button2.png"
normalImage = new Image();
normalImage.src = "button1.png";
function changeImage(imagename)
{
  document.images[imagename].src= upImage.src;
  return true;
}
function changeImageBack(imagename)
{
   document.images[imagename].src = normalImage.src;
   return true;
}
function handleMUp()
{
 changeImage();
 return true;
}
</script>



<!-- Course Table -->
<SCRIPT LANGUAGE="JavaScript">
var theTable, theTableBody;
var courseIdList = [];
var errorMsgList = [];
var courseRequirements = [];
var coursesPassed = [];

function init() {
    theTable = (document.all) ? document.all.courseTable : 
        document.getElementById("courseTable")
    theTableBody = theTable.tBodies[0]
    
      // Obtain a list of prerequisites
    getPrerequisites();
     
}


function addRow(form) {
   
   // Insert the new row in database
   insertTableRow(form)
//form.insertIndex.value
  
  // Reset text in text field     
   document.getElementById('course').style.textAlign = "center";
   document.getElementById('course').value = "< Insert Course Name >";
document.getElementById('newclassbutton').disabled = true;

}

function writeErrorMessage(text) {
  if (errorMsgList.indexOf(text) < 0)
    errorMsgList.push(text);
  displayErrorMessages();
  //document.getElementById("errorMessage").innerHTML += '<br>' + text;
}

function clearErrorMessages() {
  document.getElementById("errorMessage").innerHTML = "";
  errorMsgList = [];
}

function displayErrorMessages() {
  document.getElementById("errorMessage").innerHTML = "";
  for (i = 0; i < errorMsgList.length; i ++) {
    document.getElementById("errorMessage").innerHTML += errorMsgList[i] + '<br>';
  }
}

////// Insert Course ///////////
function insertTableRow(form) {

  // Add Course Data
  var classname = document.getElementById('course').value.toLowerCase();
  templist = list.slice();
  for (i = 0; i < templist.length; i ++) {
    templist[i] = templist[i].toLowerCase();
  }
  var classindex = templist.indexOf(classname, 0);
  templist = [];
  var nowData = [datalist[classindex][0], datalist[classindex][1], datalist[classindex][2], datalist[classindex][3]];
 
  clearErrorMessages(); // To refresh the list of error messages
   
   // Error if you try to insert the same course twice
  if (courseIdList.indexOf(datalist[classindex][4]) >= 0) {
    return writeErrorMessage("Error: You have already added " + datalist[classindex][0] + " " + datalist[classindex][1] + " - " + datalist[classindex][2] + " to your list.");
  }
  
  
  // If the table has not yet been created, add Credits label
  var numRows = theTableBody.rows.length
  var newCell
  
  courseIdList.push(datalist[classindex][4]);
  //alert("Pushing " + datalist[classindex][4]);
    
  var newRow = theTableBody.insertRow(numRows-1) //Insert Row before the last row
  


  //If we have not yet added any courses, add the Credits header
  if (numRows == 1)
  {
    document.getElementById("bcell").style.borderTop="thin solid";

    for (var i = 0; i < 4; i++) { //First four empty cells
      newCell = newRow.insertCell(i) 
    }

    newCell = newRow.insertCell(4)
    newCell.innerHTML = "Credits"

    //Add new row for the first row of the Courses Table 
    var newRow = theTableBody.insertRow(numRows)
    
    numRows++;
  }
  
  // Fix Position of Input Textbox
  document.getElementById("course").style.marginLeft = "75px";

  // Insert button to remove course
  newCell = newRow.insertCell(0)
  newCell.innerHTML = '<a href="#"><img src="./button1.png" onMouseOver="this.src=\'./button2.png\'" onMouseOut="this.src=\'./button1.png\'" onMouseDown="verifyRemove(this.parentNode.parentNode.parentNode.rowIndex, getElementById(\'controls\')) "></a>';
  newCell.style.borderTop = "thin solid"
  newCell.style.borderLeft = "thin solid"

  // Insert into selectCourse Database
  insertCourseSelected(<?php echo($student['studentID']); ?>, datalist[classindex][4]);

  // Department
  newCell = newRow.insertCell(1)
  newCell.innerHTML = '<font size = "4"><center>'+nowData[0]+'</center></font></td>'
  newCell.width = 100
  newCell.style.borderStyle = "solid"
  newCell.style.borderWidth = 0
  newCell.style.borderLeft = "thin solid"
  newCell.style.borderTop = "thin solid"

  // Number
  newCell = newRow.insertCell(2)
  newCell.innerHTML = '<font size = "4"><center>'+nowData[1]+'</center></font>'	
  newCell.width = 50
  newCell.style.borderStyle = "solid"
  newCell.style.borderWidth = 0
  newCell.style.borderTop = "thin solid"

  // Name
  newCell = newRow.insertCell(3)
  newCell.innerHTML = '<font size = "4"><center>'+nowData[2]+'</center></font>'	
  newCell.width = 200
  newCell.style.borderStyle = "solid"
  newCell.style.borderWidth = 0
  newCell.style.borderRight = "thin solid"
  newCell.style.borderTop = "thin solid"
	
  // CREDITS
  newCell = newRow.insertCell(4)
  newCell.innerHTML = '<center>'+nowData[3]+'</center>'
  newCell.style.borderStyle = "solid"
  newCell.style.borderWidth = 0
  newCell.style.borderRight = "thin solid"
  newCell.style.borderTop = "thin solid"

updateCredits(<?php echo($student['studentID']); ?>,form);

}

// This is just an initialization function- do not edit.
function getXMLHttp() {
  var xmlHttp

  try {
    //Firefox, Opera 8.0+, Safari
    xmlHttp = new XMLHttpRequest();
  }
  catch(e) {
    //Internet Explorer
    try {
      xmlHttp = new ActiveXObject("Msxml2.XMLHTTP");
    }
    catch(e) {
      try {
        xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
      }
      catch(e) {
        alert("Your browser does not support AJAX!")
        return false;
      }
    }
  }
  return xmlHttp;
}

// INSERT into CourseSelected
function insertCourseSelected(stuid, cid) {
  var xmlHttp = getXMLHttp();
 
  xmlHttp.onreadystatechange = function() {
    if(xmlHttp.readyState == 4) {
      HandleResponse(xmlHttp.responseText);
    }
  }
  // NOTICE cmd=add to signify that we're adding
  xmlHttp.open("GET", "databaseFunctions.php?cmd=addCourseSelected&stuid=" + stuid + "&cid=" + cid, true);
  xmlHttp.send(null);
}


// REMOVE from CourseSelected
function removeCourseSelected(stuid, cid) {

  var xmlHttp = getXMLHttp();
 
  xmlHttp.onreadystatechange = function() {
    if(xmlHttp.readyState == 4) {
      HandleResponse(xmlHttp.responseText);
    }
  }
  // NOTICE cmd=del
  xmlHttp.open("GET", "databaseFunctions.php?cmd=delCourseSelected&stuid=" + stuid + "&cid=" + cid, true);
  xmlHttp.send(null);
}

function HandleResponse(){

// update # of Credits after the courses is added or removed
   updateCredits(<?php echo($student['studentID']); ?>);
}




// Get Student Transcript
function getStudentTranscript(stuid) {
  var xmlHttp = getXMLHttp();
 
  xmlHttp.onreadystatechange = function() {
    if(xmlHttp.readyState == 4) {
      HandleResponsegetStudentTranscript(xmlHttp.responseText);
    }
  }
  // NOTICE cmd=sumCourseSelected
  xmlHttp.open("GET", "databaseFunctions.php?cmd=getStudentTranscript&stuid=" + stuid, true);
  xmlHttp.send(null);
}


// HANDLE RESPONSE Course Requirements
function HandleResponsegetStudentTranscript(response) {

// Store the list of courses passed in an array
coursesPassed = response.split(",");


 for (i = 0; i < selectedlist.length; i ++) {
      document.getElementById('course').value = selectedlist[i];
      addRow('controls');
    }


// Update # credits
    updateCredits(<?php echo($student['studentID']); ?>);

}







// Get Prerequisites
function getPrerequisites() {
  var xmlHttp = getXMLHttp();
 
  xmlHttp.onreadystatechange = function() {
    if(xmlHttp.readyState == 4) {
      HandleResponsegetPrerequisites(xmlHttp.responseText);
    }
  }
  // NOTICE cmd=sumCourseSelected
  xmlHttp.open("GET", "databaseFunctions.php?cmd=getPrerequisites", true);
  xmlHttp.send(null);
}


// HANDLE RESPONSE Course Requirements
function HandleResponsegetPrerequisites(response) {

  
  rtemp = response.split("~");
  
  for (j = 0; j < rtemp.length; j++)
  {
    rtempinner = rtemp[j].split(",");
    rtemp[j] = rtempinner.slice();
   
  }
  courseRequirements = rtemp;
   // Obtain a list of the classes in which the student has taken
    // and check whether the student meets the requirements
    getStudentTranscript(<?php echo($student['studentID']); ?>);  
    
}

function checkIfMeetRequirements(){
//alert(coursesPassed[0]+ coursesPassed[1]+ coursesPassed[2])
  // Run through all selected courses, and check whether the student has taken all prereqs
  for (var i = 0; i < courseIdList.length; i++) {

    // Check if this course has any prerequisites
    for (var j = 0 ; j < courseRequirements.length; j++ ){

      // If this course has a requirement
      if ( (courseRequirements[j][0]) == (courseIdList[i]) ){  
  
        // Check if the required courses exists in the list of courses passed by the student
        
        if (  (coursesPassed.indexOf(courseRequirements[j][1]) ) == -1 ){
         writeErrorMessage("You have not passed " + courseRequirements[j][3] + " to be eligible for " + courseRequirements[j][2]); 

       }
    
      }
    }
 
 }
 
  
}


// UPDATE CREDITS
function updateCredits(stuid, form) {

  var xmlHttp = getXMLHttp();
 
  xmlHttp.onreadystatechange = function() {
    if(xmlHttp.readyState == 4) {
      HandleResponseCredits(xmlHttp.responseText,form);
    }
  }
  // NOTICE cmd=sumCourseSelected
  xmlHttp.open("GET", "databaseFunctions.php?cmd=sumCourseSelected&stuid=" + stuid, true);
  xmlHttp.send(null);
}




// GET COURSE INFO
function verifyRemove(index, form) {

  var xmlHttp = getXMLHttp();
 
  xmlHttp.onreadystatechange = function() {
    if(xmlHttp.readyState == 4) {
      HandleResponseInfo(xmlHttp.responseText,index,form);
    }
  }
  // NOTICE cmd=info
  xmlHttp.open("GET", "databaseFunctions.php?cmd=info&cid="+courseIdList[index-1], true);
  xmlHttp.send(null);
}


// HANDLE RESPONSE Info
function HandleResponseInfo(response,index,form) {

  var r = confirm("Are you sure you want to remove\n" + response + "?");
  if (r) {
    removeRow(form, index);
  }

}


// HANDLE RESPONSE Credits
function HandleResponseCredits(response,form) {
// Return 0 # credits if none has been entered
if (response < 1) {
  response = 0;
}

  document.getElementById("numberCredits").innerHTML = response;
  
  // After Credits are added, check if the student has taken the prerequisites
  checkIfMeetRequirements();
}





//////// Remove Row  /////////
function removeRow(form, index) {

 // Remove from selectCourse Database
  removeCourseSelected(<?php echo($student['studentID']); ?>, courseIdList[index-1]);
  
  // Remove element from the array
  courseIdList.splice(index-1, 1);
  
	if (theTableBody.rows.length == 3)
	{

      // Remove row and credits row	
	  theTableBody.deleteRow(index-1) 
	  theTableBody.deleteRow(index-1)
	  
	  // Reposition text field
	  document.getElementById("course").style.marginLeft = "25px";
	  // Remove top border of empty cell 
	  document.getElementById("bcell").style.borderTop="none";
	}
	 
	 else{ 
    theTableBody.deleteRow(index)
	}
  clearErrorMessages();
    checkIfMeetRequirements();

	
}

// Remove all rows
function removeAllRows(form) {
  var numRows = courseIdList.length
  if (numRows > 0) {
    var r = confirm("Are you sure you want to remove all selected courses?");
    if (r) {
      var numRows = courseIdList.length
      for (var i = numRows; i >0 ; i-- ) { // Call removeRow until all rows are deleted
        removeRow(form, i)
      }
    }
  }

}


</SCRIPT>

<!-- Using Autocomplete -->
<link rel="stylesheet" href="AutoComplete.css" media="screen" type="text/css">
<script language="javascript" type="text/javascript" src="./autocomplete.js"></script>




<BODY onLoad="init(); getTimeslotInfoPrefUI(); getInstructorInfoPrefUI(); getLocationInfoPrefUI(); getSectionInfoPrefUI(); getAvailableInfoPrefUI();" onClick="SetText('course');">
<CENTER>
<TABLE><TR><TD>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</TD><TD>
<!-- Box -->
<TABLE border="0" id="selectBox" align="center" bgcolor="#FFFFFF" cellspacing="0" cellpadding="2" WIDTH="1050">

<tr>
<td WIDTH="200"></td>
<td WIDTH="700"><font size = "4"><center> Select Courses </center></font></td>
<td WIDTH="40"></td>
<td WIDTH ="80"></td>
</tr>

<tr>

<td></td>
<td height = "70" width = "525" style="border-style:solid; border:0px solid black; border-top:solid; border-bottom:solid;border-left:solid;border-right:solid;">
<br>

<!-- Courses Table -->
  <TABLE border="0" id="courseTable" align="center" bgcolor="#FFFFFF" cellspacing="0" cellpadding="2">
  <tr>
  <td id="bcell">&nbsp;</td>


  <td height = "70" width = "395" colspan="4" align="center" style="border-style:solid; border:0px solid black; border-left:thin solid #000000; border-right:thin solid #000000; border-bottom:thin solid #000000; border-top:thin solid #000000;"> 
		<center>&nbsp;<input type="text" name ="selectCourse" id="course" value="< Insert Course Name >" onClick="SelectAll(this);" style="margin-left: 25px; margin-top: -68px; position: absolute; width: 370px; height: 64px; border: 0px solid; text-align: center; font-size: 20px;"></center>

		</td>	
  <TBODY>
  </TABLE>


<br><br>

<FORM NAME="controls" ID="controls">
<!-- Add Course Button -->
<center><button type="button" id="newclassbutton" style="height: 40px; width: 300px; " onclick="addRow(this.form);"><font size = "4">
Add New Class</button></font>

    <INPUT TYPE="hidden" NAME="insertIndex">   
    </INPUT></center>
</FORM>

</td>



<td>
<!-- Remove All (Courses) Button -->
<center><font size = "4">Total Credits:</font>
<br>
<b><font size = "5" ID = "numberCredits">&nbsp;</font></b></center>

<br><br>

<!-- REMOVE ALL COURSES BUTTON -->
<button type="button" id="removeallclasses" style="height: 35px; width: 125px; margin-left: 100px;  margin-right: 100px; margin-top:-50x; " align="center" onclick="removeAllRows()"><font size = "3">
Clear All</font></button></center>
</td>
<td></td>
</tr>


<tr>
<td></td>
<td colspan="2">
<font size = "3" color = "red" id="errorMessage"></font></b>
</td>
<td></td>
</tr>
</TABLE>



<!-- < Insert Course Name > Text Dropdown -->

<script language="javascript" type="text/javascript">
  <?php
    $connection = mysql_connect("127.0.0.1", "jakekra1_katie", "database238") or die(mysql_error()); 
    mysql_select_db("jakekra1_katie") or die(mysql_error()); 
      
      $list = mysql_query("SELECT * FROM Course");
      $result_list = "";
      $data_list = "";
      
      echo("list = [");
      while($row = mysql_fetch_array($list)) {

  
  
    // 2 Possible entries for result list : Search Dept+Num or Name
      $result_list .= "'".$row['department']." ".$row['catalogNumber']." - ".$row['name']."', 
	   '".$row['name']." - ".$row['department']." ".$row['catalogNumber']."',  ";
		
		// Corresponding possible entries for the data list: Dept+Number or Name
        $data_list .= "['".$row['department']."', '".$row['catalogNumber']."', '".$row['name']."', '".$row['credits']."', '".$row['courseID']."'], ";
		$data_list .= "['".$row['department']."', '".$row['catalogNumber']."', '".$row['name']."', '".$row['credits']."', '".$row['courseID']."'], ";
 
      }
      $result_list = substr($result_list, 0, strlen($result_list)-2);
      $data_list = substr($data_list, 0, strlen($data_list)-2);
      echo("$result_list];\n");
      
      echo("datalist = [$data_list];\n");
    
   
    mysql_close($connection);
  ?>
    droplist = list.slice();
    templist = list.slice();
    for (i = 0; i < templist.length; i ++) {
      templist[i] = templist[i].toLowerCase();
    }
    function SetText(id)
    {
      if (document.getElementById(id).value.length == 0) {
	      document.getElementById(id).style.textAlign = "center";
        document.getElementById(id).value = "< Insert Course Name >";
      }
    }

    AutoComplete_Create('course', droplist.sort(), templist);

</script>
</TD></TR></TABLE></CENTER>
</BODY>


