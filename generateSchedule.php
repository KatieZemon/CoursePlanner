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
 
 

<!-- Course Table -->
<SCRIPT LANGUAGE="JavaScript">
var classList = [];
var sectionList = [];
var schedule = [];
var scheduleList = [];
var scheduleIDList = [];
var coursesSelected = [];
var filteredSections = [];
var sTable, sTableBody;
var mainTableCreated = false;

var scheduleTableId = 0;
var daysOfWeek = ['M', 'T', 'W', 'R', 'F'];
var timeSlots = ["8:00", "9:00", "10:00", "11:00", "12:00", "1:00", "2:00", "3:00", "4:00", "5:00", "6:00"];
var colorTable = ["#75FF75", "#7575FF", "#FF7575", "#75FFFF", "#FFFF75", "#757575", "#BCA9F5","#FAAC58","#F781F3","#CCEEFF","#CCEE33"];

<?php 
  if (isSet($_GET['regenerate'])) {
   // echo("printSchedules();");
  }
?>


function writeClassList(text) {
classList.push(text);
  document.getElementById("classListing").innerHTML = "";
  for (i = 0; i < classList.length; i ++) {
    document.getElementById("classListing").innerHTML += classList[i] + '<br>';
  }
}


function writeSectionList(text) {
sectionList.push(text);

  document.getElementById("sectionListing").innerHTML = "";
  for (i = 0; i < sectionList.length; i ++) {
	if(sectionList[i] == "space") { document.getElementById("sectionListing").innerHTML +='<br>';}
	else{
    document.getElementById("sectionListing").innerHTML += sectionList[i] + ' ';
	}
	
  }
}

function writeScheduleList(text) {
scheduleList.push(text);
  document.getElementById("scheduleListing").innerHTML = "";
  for (i = 0; i < scheduleList.length; i ++) {
    document.getElementById("scheduleListing").innerHTML += scheduleList[i] + '<br>';
  }
}

function writeScheduleIDList(text) {
scheduleIDList.push(text);
  document.getElementById("scheduleIDListing").innerHTML = "";
  for (i = 0; i < scheduleIDList.length; i ++) {
    document.getElementById("scheduleIDListing").innerHTML += scheduleIDList[i] + '<br>';
  }
}

function printSchedules(){
  if (schedule.length > 0) {
    <?php 
     /* if (isSet($_GET['regenerate'])) {
        echo("window.location.href = window.location.href;");
      }else {
        echo("window.location.href = window.location.href + \"?regenerate=1\"");
      }*/
    ?>
  }
  
  getCourses(<?php echo($student['studentID']); ?>);
 
}

// Selected courses
function getCourses(stuid) {

  var xmlHttp = getXMLHttp();
 
  xmlHttp.onreadystatechange = function() {
    if(xmlHttp.readyState == 4) {
      HandleResponseGetCourses(xmlHttp.responseText);
    }
  }
  // NOTICE cmd=getCourseNames
  xmlHttp.open("GET", "databaseFunctions.php?cmd=getCourses&stuid=" + stuid, true);
  xmlHttp.send(null);
}
// HANDLE RESPONSE getSelectedCourses
function HandleResponseGetCourses(response) {

  //array returning courses listed in coursesSelected table
  coursesSelected = (response.substring(0,response.length-1)).split(",");
 if (coursesSelected.length == 0) return alert("There are no classes selected for generating a schedule.");
 else if (coursesSelected.length == 1) return alert("Choose at least two classes for generating a schedule.");
// getFilteredSections(<?php echo($student['studentID']); ?>);
//  alert(coursesSelected[0])
 getSectionsWithFilter(<?php echo($student['studentID']); ?>);
}


// Get Sections to be remove
function getSectionsWithFilter(stuid) {
  var xmlHttp = getXMLHttp();
 
  xmlHttp.onreadystatechange = function() {
    if(xmlHttp.readyState == 4) {
      HandleResponseGetSectionsWithFilter(xmlHttp.responseText);
    }
  }
  // NOTICE cmd=getCourseNames
  xmlHttp.open("GET", "databaseFunctions.php?cmd=getSectionsWithFilter&stuid=" + stuid, true);
  xmlHttp.send(null);
}

// HANDLE RESPONSE getSections
function HandleResponseGetSectionsWithFilter(response) {
  //temp = response.split("~");

  response = response.substring(0, response.length -1);


 // No Classes were selected
 if (response.length <= 0) return alert("There are no classes selected for generating a schedule");

  rtemp = response.split("~");

  sectionsAvail = [];

   
  for (j = 0; j < rtemp.length; j ++) {
    rtempinner = rtemp[j].split(",");

	if (sectionsAvail.indexOf(rtempinner[0]) < 0 && rtempinner[0].length > 1) {
	  sectionsAvail.push(rtempinner[0]);
	  sectionsAvail.push([]);
	}
  

	sectionsAvail[sectionsAvail.indexOf(rtempinner[0]) + 1].push(rtempinner[1].split("#"));
  }
  if ( (sectionsAvail.length / 2)  < coursesSelected.length ) {
  alert("No schedules can be created with the preferences you selected.")
  return;
  }

//alert(coursesSelected.length)
 // return;
  getSchedules();

}


// Creates array full of schedules determined by constraints
function getSchedules(){
  temp1 = [];
  temp2 = [];
  schedule = [];

  // Only 1 class selected
  if (sectionsAvail.length <= 2) {
    for (i = 0; i < sectionsAvail[1].length; i ++) {
      schedule.push(sectionsAvail[1][i][0]);
    }
    return storeSchedule();
  }
  
  for (i = 0; i < sectionsAvail[1].length; i ++) {
    for (j = 0; j < sectionsAvail[3].length; j ++) {
      if (noConflict(sectionsAvail[1][i][0], sectionsAvail[3][j][0])) {
        temp1.push([sectionsAvail[1][i][0], sectionsAvail[3][j][0]]);
      }
    }
  }

  // Only 2 classes selected
  if (sectionsAvail.length <= 4) {
    schedule = temp1.slice();
    return storeSchedule();
  }

  var c = 0;
  for (k = 5; k < sectionsAvail.length; k += 2) {
    var z = 0;
    for (i = 0; i < ((c%2) ? temp2.length : temp1.length); i ++) {
    
      for (j = 0; j < sectionsAvail[k].length; j ++) {
        if (!(c%2)) {
          temp2[z] = temp1[i].slice();
          check = true;
          for (l = 0; l < temp2[z].length; l ++) {
            check = noConflict(temp2[z][l], sectionsAvail[k][j][0]);
            if ( !check) break; 
          }
          if (check) {
            temp2[z].push(sectionsAvail[k][j][0]);
          }
        } else {
          temp1[z] = temp2[i].slice();
          check = true;
          for (l = 0; l < temp1[z].length; l ++) {
            check = noConflict(temp1[z][l], sectionsAvail[k][j][0]);
            if ( !check) break;
          }
          if (check) {
            temp1[z].push(sectionsAvail[k][j][0]);
          }
        }
        z ++;
      }
    }
    if (!(c%2)) {
      temp1 = [];
    }else {
      temp2 = [];
    }
    c ++;
    
  }
  tempschedule = ((c-1) % 2) ? temp1 : temp2;
  
  
  for (i = 0; i < tempschedule.length; i ++) {
    if (tempschedule[i].length != sectionsAvail.length / 2) continue;
    schedule.push(tempschedule[i]);
  }

  if (schedule.length > 300){
  alert(schedule.length + " schedules can be created from this set of selected courses. Select more preferences in order to narrow down your options.");
  return;
  }


  
  storeSchedule();
  
}



// STORE SCHEDULE
function storeSchedule() {

// Remove previously stored schedules
removeAllSchedules();

// Add new Schedules

// If only one course exists in each schedule
if (schedule[0][0].length == 1) {
  for (i = 0; i < schedule.length; i ++) {     
    insertSchedule( i, schedule[i], <?php echo($student['studentID']); ?> )
  }
}
// Multiple courses per schedule
else{
  for (i = 0; i < schedule.length; i ++) {
    for (j = 0; j < schedule[i].length; j++)
    {
      insertSchedule( i, schedule[i][j], <?php echo($student['studentID']); ?> )
    }
  }
}

printNumSchedules( <?php echo($student['studentID']); ?>)
  if (mainTableCreated) {
    clearScheduleTable();
  }else
 generateTable();
}

function generateTable(){



// Create Larger Table
  createScheduleTable();
  sTable = document.getElementById("scheduleTable");
  sTableBody = sTable.tBodies[0];
  
  var newRow   = sTable.insertRow(0);
  var newCell  = newRow.insertCell(0);
  newCell.colSpan = "2";

  newCell.innerHTML = '<center><font size = 6> Possible Schedules: ' + schedule.length + '<br><font size = 4>Select one to become your wish list.<br></font></center>'
  for (g = 0; g < schedule.length; g ++) {
    populateTable();
  }
}

function createScheduleTable() {
//  <TABLE border="1" id="scheduleDisplay" align="center" bgcolor="#FFFFFF" cellspacing="1" cellpadding="2" WIDTH="400">
  var body = document.getElementById("scheduleDisplayContainer");
  var tbl = document.createElement("table");
  //tbl.id = "sTable" + scheduleTableId;
  //tbl.align="center";
  var tblBody = document.createElement("tbody");
  //alert("created");
  tbl.setAttribute("border", 1);
  tbl.setAttribute("id", "scheduleDisplay");
  tbl.setAttribute("align", "center");
  tbl.setAttribute("width", 400);
  mainTableCreated = true;
  body.appendChild(tbl);
}

function clearScheduleTable() {
 // alert("Clearing it " + document.getElementById("scheduleDisplay").parentNode);
  var body = document.getElementById("scheduleDisplayContainer");
  body.innerHTML = "";
 // document.getElementById("scheduleDisplay").removeNode(true);
  /*var body = document.getElementsByTagName('body')[0];
  var el = document.getElementById("scheduleDisplay");
  body.removeChild(el);*/
  //alert("Clearing it2");
  mainTableCreated = false;
/*
var table = document.getElementById("scheduleTable");
var tablebodies = table.getElementsByTagName('tbody');
var tablebodieslength = tablebodies.length;

alert("Size of table: " + tablebodieslength);
for (i = tablebodies.length-1; i > 0; i --) {
  alert("removing row " + i);
  var cell = tablebodies[i].parentNode;
  while ( cell.childNodes.length >= 1 ) {
          cell.removeChild( cell.firstChild );       
  }
  //tablebodies[i].parentNode.removeChild(tablebodies[i]);
}
*/
/*
while (tablebodieslength > 0) {
  tablebodieslength--;
  alert("removing row " + tablebodieslength);
  table.removeChild(tablebodies[0]); 
}*/
/*
  var cell = document.getElementById("scheduleTable");

  if ( cell.hasChildNodes() ) {
      while ( cell.childNodes.length >= 1 ) {
        alert(cell.firstChild.className);
        cell.removeChild( cell.firstChild );       
      } 
  }*/
}

function populateTable() {
  sTable = document.getElementById("scheduleTable");
  
  if (scheduleTableId%2 == 0){
    var newRow = sTable.insertRow((scheduleTableId/2 + 1));
    newRow.setAttribute("id", "schRow" + scheduleTableId + "-" + (scheduleTableId/2 + 1));
  }else {
    var newRow = document.getElementById("schRow" + (scheduleTableId-1) + "-" + ((scheduleTableId-1)/2 + 1));
  }

  var newCell = newRow.insertCell(scheduleTableId%2);

  //newCell.innerHTML= numSchedules;
  
  // get the reference for the body
 // var body = document.getElementsByTagName("body")[0];

  // creates a <table> element and a <tbody> element
  var tbl     = document.createElement("table");
  //tbl.id = "sTable" + scheduleTableId;
  //tbl.align="center";
  var tblBody = document.createElement("tbody");
  
  
  // MON - FRI ROW
  var row = document.createElement("tr");
  for (var i = 0; i < daysOfWeek.length+1; i ++) {
  
    if (i == 0) {
      var cell = document.createElement("td");
        var cellText = document.createTextNode(scheduleTableId);
        //cell.style.fontSize = "14px";
    }else {
      var cell = document.createElement("td");
      var cellText = document.createTextNode(daysOfWeek[i-1]);
     if (i == daysOfWeek.length) {
        cell.style.borderRight="1px black solid";
      }
    }
    cell.style.borderLeft="1px black solid";
    cell.style.borderTop="1px black solid";
    cell.style.borderBottom="1px black solid";
    cell.style.textAlign="center";
    cell.appendChild(cellText);
    row.appendChild(cell);
  }
  tblBody.appendChild(row);
  
 
    // creating all cells
  for (var j = 0; j < timeSlots.length * 4 ; j++) {
      // creates a table row
      var row = document.createElement("tr");
     

     // row.colSpan = "0";
      
      for (var i = 0; i < daysOfWeek.length+1; i++) {
          // Create a <td> element and a text node, make the text
          // node the contents of the <td>, and put the <td> at
          // the end of the table row
          var cell = document.createElement("td");
          if (i > 0) {
         
            cell.setAttribute('name', 'sch' + ((j*daysOfWeek.length)+(i-1)));
            cell.style.fontSize = "14px";
            cell.style.fontWeight = "bold";
            cell.style.borderLeft="1px black solid";
            if (i == daysOfWeek.length) {
             cell.style.borderRight="1px black solid"; 
            }
            
             var cellText = document.createTextNode("");
             cell.innerHTML = "<div style=\"font-size:0%;\">&nbsp;</div>";
             //cell.style.fontSize = "1px";
            if ((j+1) % 4 == 0) {
              cell.style.borderBottom="1px black solid";
              var cellText = document.createTextNode("");
              //var cellText = document.createTextNode("cell: " + cell.getAttribute('name') + "  " + tbl.id);
            }

 
            

              cell.appendChild(cellText);
              
            row.appendChild(cell);
            
          }else if (j % 4 == 0) {
              var cell = document.createElement("td");
              var cellText = document.createTextNode(timeSlots[Math.floor(j / 4)]);
              cell.style.borderLeft="1px black solid";
              cell.style.borderBottom="1px black solid";
              cell.rowSpan = "4";
              cell.appendChild(cellText);
              row.appendChild(cell);
          }
              cell.style.textAlign="center";
              cell.height = "1";
      }

      // add the row to the end of the table body
      tblBody.appendChild(row);
  }


  // put the <tbody> in the <table>
  tbl.appendChild(tblBody);
  // appends <table> into <body>
 
 // body.appendChild(tbl);
 newCell.appendChild(tbl);
 
  // sets the border attribute of tbl to 2;
  tbl.setAttribute("border", "0");
  tbl.setAttribute("cellpadding", "1");
  tbl.setAttribute("cellspacing", "0");
  tbl.setAttribute("width", "600");
  tbl.setAttribute("height", "60");
        
  //document.getElementsByName("sch"+(20+4))[0].style.backgroundColor = "#68EE68";
  //document.getElementsByName("sch"+(20+4))[0].innerHTML = "hi";
  //alert("hi1");
  fillScheduleTable(scheduleTableId);
  //alert("hi2");
  
  // ADD BUTTON
 // var button = '<img src="./select1.png" onMouseOver="this.src=\'./select2.png\'" onMouseOut="this.src=\'./select1.png\' ">';
  //newCell.innerHTML = '<img src="./select1.png" onMouseOver="this.src=\'./select2.png\'" onMouseOut="this.src=\'./select1.png\' ">';
  var selectBtn = document.createElement("IMG");
  //selectBtn.setAttribute("align","bottom");
  selectBtn.setAttribute("src","./select1.png");
  selectBtn.setAttribute("onMouseOver","src=\'./select2.png\'");
  selectBtn.setAttribute("onMouseOut","src=\'./select1.png\'");
  selectBtn.setAttribute("onClick", "scheduleSelected(" + scheduleTableId + ");");
  //selectBtn.setAttribute("id", numSchedules);
  selectBtn.style.marginLeft = "244px";
  //selectBtn.style.marginTop = "50px";
  
  newCell.appendChild(selectBtn);
  selectBtn.style.position = "relative";
  selectBtn.style.verticalAlign = "bottom";
  selectBtn.style.top = "0";

  //var mybutton=document.createElement("BUTTON");
 // mybutton.style.height=50;

//mybutton.style.width=200;
//mybutton.
  //theText=document.createTextNode("Select");

//mybutton.appendChild(theText);

//mybutton.innerHTML ='<font size = 3>Select</font>'
  
  scheduleTableId ++;

}

function scheduleSelected(tableid) {
  confirm("Do you want to add Schedule #" + tableid + " with sections " + schedule[tableid] + " to your wish list?");
}


function getPosition(arrayName,arrayItem)
{
  for(var i=0;i<arrayName.length;i++){ 
   if(arrayName[i]==arrayItem)
  return i;
  }
}
/*
Array.prototype.position = function (s) {
  var pos = 0;
  while (pos < this.length && this [pos] != s) {pos++};
  return pos < this.length ? pos : undefined;
}*/

function getSectionInfo(secid) {
  var cid = secid - (secid % 100);
  
  //return sectionsAvail[sectionsAvail.position(cid) + 1][secid % 100];
  var tempinfo = sectionsAvail[getPosition(sectionsAvail, cid) + 1];
 // alert(tempinfo);
  var i;
  
  for (i = 0; i < tempinfo.length; i ++) {
    if (tempinfo[i][0] == secid) break;
  }
//  alert("cid is " + cid + " " + sectionsAvail + " +++ " + i);
  return tempinfo[i];
}

//function roundTo(num,to) { // num is an exponent of 10
//  return Math.round(info[2]/100)*100;
//}


function fillScheduleTable(schid) {
  for (i = 0; i < schedule[schid].length; i ++) {

    var info = getSectionInfo(schedule[schid][i]);
    //var cNamePos = Math.floor(sectionsAvail.position(info[0] - (info[0]%100)) / 2);

    var cNamePos = Math.floor(getPosition(sectionsAvail, (info[0] - (info[0]%100))) / 2);
    var iName = getSectionInfo(schedule[schid][i])[6];
    var sTime = getDisplayTime(getSectionInfo(schedule[schid][i])[1]);
    var eTime = getDisplayTime(getSectionInfo(schedule[schid][i])[2]);
    var location = getSectionInfo(schedule[schid][i])[4];
    
    
    var cColor = colorTable[cNamePos];
    var cName = coursesSelected[cNamePos];
   // alert(coursesSelected[cInstructorPos]);

    hourSegments = Math.floor((Math.round(info[2]/100)*100 - info[1]) / 25);
    for (j = 0; j < hourSegments; j ++) {
      stime = Math.round(info[1]) + (j*15)%60;
      // adjust time
      stime = (j*15)/60 >= 1 ? (stime + 100 * Math.floor((j*15)/60)) : stime;

      for (k = 0; k < info[3].length; k ++) {
        day = daysOfWeek.indexOf(info[3].substring(k, k+1));
        getScheduleCell(schid, stime, day).style.backgroundColor = cColor;
        
        switch (j) {
          case 0:// Course Name
            getScheduleCell(schid, stime, day).innerHTML = cName;
          break;
          
          case 1:  // Time
          getScheduleCell(schid, stime, day).innerHTML = sTime + " - " + eTime;
          break;
          
          case 2: // Instructor Name
            getScheduleCell(schid, stime, day).innerHTML = iName;
          break;
          
          case 3: // Section Letter
            getScheduleCell(schid, stime, day).innerHTML = location;
          break;
        }
        
      }
    }
  }
}
//document.getElementsByName("sch"+(1*5))[scheduleTableId].innerHTML = "hi";

function getScheduleCell(schid, time, day) {
  //alert("Getting " + time + " sch"+(20* (Math.floor((time-800)/100)) +day));
  min = time % 100;
  return document.getElementsByName( "sch" + (20 * (Math.floor((time-800)/100)) + ((min*100/60)/25)*5 + day))[schid];
}



// Count Schedules
function printNumSchedules(stuid) {

  var xmlHttp = getXMLHttp();
 
  xmlHttp.onreadystatechange = function() {
    if(xmlHttp.readyState == 4) {
      HandleResponseCountSchedules(xmlHttp.responseText);
    }
  }
  // NOTICE cmd=countCourseSelected
  xmlHttp.open("GET", "databaseFunctions.php?cmd=countSchedules&stuid=" + stuid, true);
  xmlHttp.send(null);
  
  /// Change to numSchedules = Count(Rows in Schedule) / Count(Rows in courseSelected
}


// HANDLE RESPONSE Num Schedules
function HandleResponseCountSchedules(response) {
  document.getElementById("numSchedules").innerHTML = response;
}



// INSERT into Schedule
function insertSchedule(schedID, secID, stuid) {
  var xmlHttp = getXMLHttp();
 
  xmlHttp.onreadystatechange = function() {
    if(xmlHttp.readyState == 4) {
      HandleResponse(xmlHttp.responseText);
    }
  }
  
  // NOTICE cmd=addSchedule to signify that we're adding
    xmlHttp.open("GET", "databaseFunctions.php?cmd=addSchedule&schedID=" + schedID + "&secID=" + secID + "&stuid=" + stuid, true); 
  xmlHttp.send(null);
}

function removeAllSchedules(){
 var xmlHttp = getXMLHttp();
 
  xmlHttp.onreadystatechange = function() {
    if(xmlHttp.readyState == 4) {
      HandleResponse(xmlHttp.responseText);
    }
  }
  
  // NOTICE cmd=removeAllSchedule
    xmlHttp.open("GET", "databaseFunctions.php?cmd=removeAllSchedules", true); 
  xmlHttp.send(null);
}


// Return true if no time conflict exists between the two sections
//function noConflict(i,j,k,m){
function noConflict(sec1,sec2){
var i,j,k,m;

outerloop:
  for (i = 0; i < sectionsAvail.length; i ++) {
    for (j = 0; j < sectionsAvail[i].length; j ++) {
      if (sectionsAvail[i][j][0] == sec1) break outerloop;
    }
  }

outerloop2:
  for (k = 0; k < sectionsAvail.length; k ++) {
    for (m = 0; m < sectionsAvail[k].length; m ++) {
      if (sectionsAvail[k][m][0] == sec2) break outerloop2;
    }
  }

s1 = sectionsAvail[i][j][1];
e1 = sectionsAvail[i][j][2];
s2 = sectionsAvail[k][m][1];
e2 = sectionsAvail[k][m][2];

//Compare Times
var noTimeConflict = ((e2 < s1) || (s2 > e1));

//Compare Days
var noDayConflict = true;

// Num chars in first section

var numDaysij= sectionsAvail[i][j][3].length;
var numDayskm= sectionsAvail[k][m][3].length;

for (var n = 0; n < numDaysij; n++) {
  for (var p = 0; p < numDayskm;p++) {
  
    if (sectionsAvail[k][m][3].substring(p,p+1) == sectionsAvail[i][j][3].substring(n,n+1)){
	  noDayConflict = false;
	}
  }
}

return (noDayConflict || noTimeConflict);
}

function getDisplayTime(time) {
  dayTypes = ['AM', 'PM'];
  var hour = Math.floor(time / 100) % 12;
  hour = !hour ? 12:hour;
  var min = time % 100 + dayTypes[Math.floor(time / 1200)];
  if (time % 100 == 0) {
    min = "00" + dayTypes[Math.floor(time / 1200)];
  }
  return hour + ':' + min;
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


</SCRIPT>


<body>





<center>

<!-- Generate Schedules Button -->
<button type="button" id="newclassbutton" style="height: 40px; width: 400px" onclick="printSchedules();"><font size = "4">
Generate Schedules</button></font></center>

<br><br>
<div id="regText">
    <?php 
      //if (isSet($_GET['regenerate'])) {
      //  echo("<div id=\"regText\"><h1><center>Regenerating list</center></h1></div>");
      //}
    ?>
</div>
<!-- Holds all schedules-->
<TABLE border="0" id="scheduleTable" align="center" bgcolor="#FFFFFF" cellspacing="0" cellpadding="20">
</Table>

<br>
<br>

 
 
<div id="scheduleDisplayContainer">
</div>
<br><br><br><br>

 </body>

 
