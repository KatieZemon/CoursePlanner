<?php
$cmd = $_GET['cmd'];
$params = array(
                "cid"     => $_GET["cid"],
                "stuid"   => $_GET["stuid"],
                "schedID" => $_GET["schedID"],
                "secID"   => $_GET["secID"],
				"startTime"   => $_GET["startTime"],
				"endTime"   => $_GET["endTime"],
				"days"   => $_GET["days"]
          );
$type = $_GET['type'];

// Connects to the database
$connection = mysql_connect("127.0.0.1", "jakekra1_katie", "database238") or die(mysql_error()); 
mysql_select_db("jakekra1_katie") or die(mysql_error()); 
if ($cmd == "addCourseSelected") {
  mysql_query("INSERT INTO CourseSelected VALUES(".$params['cid'].", ".$params['stuid'].");");
} else if ($cmd == "delCourseSelected") {
  // Remove selected course goes here
  mysql_query("DELETE FROM CourseSelected WHERE courseID='".$params['cid']."' AND studentID='".$params['stuid']."';");
  
}else if ($cmd == "sumCourseSelected") {
  // Return the value of the aggregate function as an echo
  // then in javascript handle it using the HandleResponse event
//$sumCredits = mysql_query("SELECT courseid,SUM(courseID) AS TotalCredits FROM CourseSelected GROUP BY courseID;");
  $sumCredits = "SELECT SUM(credits) AS \"TotalCredits\" 
                FROM CourseSelected CS, Course C
                WHERE CS.courseID = C.courseID AND studentID='".$params['stuid']."';";
                
              
  $result = mysql_query($sumCredits) or die(mysql_error());

  // Print out result
  while($row = mysql_fetch_array($result)){
    echo($row['TotalCredits']);
  }
}else if ($cmd == "info") {
  // Return the class information as an echo
  // then in javascript handle it using the HandleResponse event
  $courseInfo = "SELECT department AS \"dept\", catalogNumber AS \"cnum\", name  AS \"name\"
                FROM CourseSelected CS, Course C
                WHERE CS.courseID = C.courseID AND C.courseID = ".$params['cid'].";";
  $result = mysql_query($courseInfo) or die(mysql_error());

  // Print out result
  while($row = mysql_fetch_array($result)){
    echo($row['dept']." ".$row['cnum']." - ".$row['name']);
  }
  
  
}else if ($cmd == "getCourses") {
  // Return the dept+no of classes selected by a student as an echo
  // then in javascript handle it using the HandleResponse event
  $courseName = "SELECT department AS \"dept\", catalogNumber AS \"num\"
                FROM CourseSelected CS, Course C
                WHERE CS.courseID = C.courseID AND studentID='".$params['stuid']."'";
  $result = mysql_query($courseName) or die(mysql_error());

  
  //Print out result
  while($row = mysql_fetch_array($result)){
    echo($row['dept']. " " . $row['num'] . "," );
  }
}else if ($cmd == "getSections") {
  // Return the section info of classes selected as an echo
  // then in javascript handle it using the HandleResponse event
  $sectionInfo = "SELECT sectionID, sTime, eTime, day, location, letter, instructor, availability, courseID
                FROM CourseSelected CS, Section S
                WHERE CS.courseID = S.cid AND studentID='".$params['stuid']."'";
  $result = mysql_query($sectionInfo) or die(mysql_error());

  
  //Print out result
  while($row = mysql_fetch_array($result)){
    echo($row['courseID']. "," .$row['sectionID']. "#" .$row['sTime'] . "#" .$row['eTime']. "#" .$row['day'] . "#" .$row['location'] . "#" .$row['letter'] . "#" .$row['instructor'] . "#" .$row['availability'] ."~" );
  }
}else if ($cmd == "addSchedule") {
  mysql_query("INSERT INTO Schedule VALUES(".$params['schedID'].", ".$params['secID'].", ".$params['stuid'].");");
}else if ($cmd == "removeAllSchedules") {
  mysql_query("DELETE * FROM Schedule;");
  echo("success");
}else if ($cmd == "getPrerequisites") {
  // Return the courseIDs of courses and their requirements
  $prereqInfo = "SELECT cid1, cid2, cName1, cName2
                FROM CourseRequirement";
  $result = mysql_query($prereqInfo) or die(mysql_error());

  
  //Print out result
  while($row = mysql_fetch_array($result)){
    echo($row['cid1']. "," .$row['cid2']. "," .$row['cName1']. "," .$row['cName2']. "~" );
  }
}else if ($cmd == "getStudentTranscript") {
  // Return the courseIDs of courses and their requirements
  $coursesPassed = "SELECT courseTaken
                FROM StudentTranscript T
                WHERE (T.grade = 'A' OR T.grade = 'B' OR T.grade = 'C') AND T.sid='".$params['stuid']."'";
  $result = mysql_query($coursesPassed) or die(mysql_error());

  
  //Print out result
  while($row = mysql_fetch_array($result)){
    echo($row['courseTaken']. "," );
  }
}
// Insert a new instructor into Instructor table
else if($cmd == "addInstructorSection"){
  mysql_query("INSERT INTO Instructor VALUES(".$params['cid'].", ".$params['stuid'].", ".$params['secID'].");");
}

// Remove an instructor from the Instructor table
else if($cmd == "delInstructorSection"){
  mysql_query("DELETE FROM Instructor WHERE studentID='".$params['stuid']."' AND sectionID='".$params['secID']."';");
}

// A list of all of the instructors and course names for the selected courses
else if($cmd == "getInstructors"){
	 $sectionInfo = "SELECT department, catalogNumber, sectionID, instructor 
                FROM CourseSelected CS, Section S, Course CO
                WHERE CS.courseID = S.cid AND CO.courseID = S.cid AND studentID='".$params['stuid']."'";
  $result = mysql_query($sectionInfo) or die(mysql_error());

  
  //Print out result
  while($row = mysql_fetch_array($result))
	{
    echo($row['department']. " " .$row['catalogNumber']. "#" .$row['sectionID'] . "#" .$row['instructor']."~" );
	}

}

// A list of all of the sectionIDs from the instructor table
else if($cmd == "getExistingInstructorPreferences"){
	$sectionInfo = "SELECT sectionID
									FROM Instructor I
									WHERE I.studentID='".$params['stuid']."'";
	$result = mysql_query($sectionInfo) or die(mysql_error());
	
	while($row = mysql_fetch_array($result))
	{
		echo($row['sectionID']. "~");
	}
}


else if($cmd == "getSectionsForPreferences")
{
	$sectionInfo = "SELECT department, catalogNumber, sectionID, letter, sTime, eTime, instructor 
                FROM CourseSelected CS, Section S, Course CO
                WHERE CS.courseID = S.cid AND CO.courseID = S.cid AND studentID='".$params['stuid']."'";
  $result = mysql_query($sectionInfo) or die(mysql_error());

  
  //Print out result
  while($row = mysql_fetch_array($result))
	{
    echo($row['department']. " " .$row['catalogNumber']. "#" .$row['sectionID']. "#" .$row['letter']."#" .$row['sTime']. "#" .$row['eTime']. "#" .$row['instructor']."~" );
	}
}
else if($cmd == "addSectionPreference")
{
	mysql_query("INSERT INTO SectionPreference VALUES(".$params['cid'].", ".$params['stuid'].", ".$params['secID'].");");
}
else if($cmd == "delSectionPreference")
{
  mysql_query("DELETE FROM SectionPreference WHERE studentID='".$params['stuid']."' AND sectionID='".$params['secID']."';");
}
else if($cmd == "getLocationForPreferences")
{
	$sectionInfo = "SELECT department, catalogNumber, location, sectionID
									FROM CourseSelected CS, Section S, Course CO
									WHERE CS.courseID = S.cid AND CO.courseID = S.cid AND studentID='".$params['stuid']."'";
	$result = mysql_query($sectionInfo) or die(mysql_error());
	
	while($row = mysql_fetch_array($result))
	{
		echo($row['department']." ".$row['catalogNumber']. "#" .$row['location']. "#" . $row['sectionID']."~");
	}
}
else if($cmd == "addLocationPreference")
{
	mysql_query("INSERT INTO Location VALUES(".$params['cid'].", ".$params['stuid'].", ".$params['secID'].");");
}
else if($cmd == "delLocationPreference")
{
	mysql_query("DELETE FROM Location WHERE studentID='".$params['stuid']."' AND sectionID='".$params['secID']."';");
}
else if($cmd == "getAvailableForPreferences"){
	$sectionInfo = "SELECT department, catalogNumber, availability, sectionID
									FROM CourseSelected CS, Section S, Course CO
									WHERE CS.courseID = S.cid AND CO.courseID = S.cid AND studentID='".$params['stuid']."'";
	$result = mysql_query($sectionInfo) or die(mysql_error());
	
	while($row = mysql_fetch_array($result))
	{
		echo($row['department']." ".$row['catalogNumber']."#".$row['availability']."#".$row['sectionID']."~");
	}
}
else if($cmd == "addAvailablePreference"){
	mysql_query("INSERT INTO Availability VALUES(".$params['cid'].", ".$params['stuid'].", ".$params['secID'].");");
}
else if($cmd == "delAvailablePreference"){
	mysql_query("DELETE FROM Availability WHERE studentID='".$params['stuid']."' AND sectionID='".$params['secID']."';");
}
else if($cmd == "getExistingLocationPreferences")
{
	$sectionInfo = "SELECT sectionID
									FROM Location L
									WHERE L.studentID='".$params['stuid']."'";
	$result = mysql_query($sectionInfo) or die(mysql_error());
	
	while($row = mysql_fetch_array($result))
	{
		echo($row['sectionID']. "~");
	}
}

else if($cmd == "getExistingSectionPreferences"){
	$sectionInfo = "SELECT sectionID
									FROM SectionPreference SP
									WHERE SP.studentID='".$params['stuid']."'";
	$result = mysql_query($sectionInfo) or die(mysql_error());
	
	while($row = mysql_fetch_array($result))
	{
		echo($row['sectionID']. "~");
	}
}
else if($cmd == "getExistingAvailablePreferences")
{
	$sectionInfo = "SELECT sectionID
									FROM Availability A
									WHERE A.studentID='".$params['stuid']."'";
	$result = mysql_query($sectionInfo) or die(mysql_error());
	
	while($row = mysql_fetch_array($result))
	{
		echo($row['sectionID']. "~");
	}
}






// All Preferences that are removed from the list of schedules
else if ($cmd == "getSectionsWithFilter") {
  // Return the section info of classes selected as an echo
  // then in javascript handle it using the HandleResponse event
  
 $removedSections = "SELECT I.sectionID
									FROM Instructor I
									WHERE I.studentID='".$params['stuid']."' 
                  
                  UNION
                  
                  SELECT P.sectionID
									FROM SectionPreference P
									WHERE P.studentID='".$params['stuid']."' 
                  
                  UNION
                  
                  SELECT L.sectionID
									FROM Location L
									WHERE L.studentID='".$params['stuid']."' 
                  
                  UNION
                  
                  SELECT A.sectionID
									FROM Availability A
									WHERE A.studentID='".$params['stuid']."' 
									
				  UNION
                  
                  SELECT S.sectionID
							FROM Timeslot T, Section S, CourseSelected C
							WHERE C.studentID='".$params['stuid']."' AND 
							C.courseID = S.cid AND
							
							(T.startTime >= S.sTime   AND   T.endTime >= S.sTime) OR
							(T.startTime <= S.eTime   AND   T.endTime > S.eTime)
							
							
							
							AND 
							
							S.day LIKE T.day
                  
                  ";
                  

//  while($row = mysql_fetch_array($result1)){
  //  $removedSections = "'".$row['sectionID']. "',";
  //}
  
  $removedSections = subStr($removedSections, 0, strlen($listofsections)-1);
  
  $sectionInfo = "
                SELECT sectionID, sTime, eTime, day, location, letter, instructor, availability, courseID
                FROM CourseSelected CS, Section S
                WHERE CS.courseID = S.cid AND studentID='".$params['stuid']."' AND sectionID NOT IN($removedSections)
                

                  
                
                ;";


  $result = mysql_query($sectionInfo) or die(mysql_error());

  
  
  
  //Print out result
  while($row = mysql_fetch_array($result)){
    echo($row['courseID']. "," .$row['sectionID']. "#" .$row['sTime'] . "#" .$row['eTime']. "#" .$row['day'] . "#" .$row['location'] . "#" .$row['letter'] . "#" .$row['instructor'] . "#" .$row['availability'] ."~" );
  }
}

else if($cmd == "getPreferencesToBeRemoved") {

	$removedSections = "SELECT I.sectionID
									FROM Instructor I
									WHERE I.studentID='".$params['stuid']."' 
                  
                  UNION
                  
                  SELECT P.sectionID
									FROM SectionPreference P
									WHERE P.studentID='".$params['stuid']."' 
                  
                  UNION
                  
                  SELECT L.sectionID
									FROM Location L
									WHERE L.studentID='".$params['stuid']."' 
                  
                  UNION
                  
                  SELECT A.sectionID
									FROM Availability A
									WHERE A.studentID='".$params['stuid']."'	       
                  ";
                  
	$result = mysql_query($removedSections) or die(mysql_error());

	
	while($row = mysql_fetch_array($result)){
		echo($row['sectionID']."~");
	}
}
// Add a new timeslot to the timeslot table
else if ($cmd == "addNewTimeSlot") {
  mysql_query("INSERT INTO Timeslot VALUES('".$params['stuid']."', '".$params['startTime']."', '".$params['endTime']."', '".$params['days']."');");
}else if ($cmd == "removeTimeSlot") {
  //mysql_query("INSERT INTO Timeslot VALUES('".$params['stuid']."', '".$params['startTime']."', '".$params['endTime']."', '".$params['days']."');");
  mysql_query("DELETE FROM Timeslot WHERE studentID='".$params['stuid']."' AND startTime='".$params['startTime']."' AND endTime='".$params['endTime']."' AND day='".$params['days']."';");
}


// Aggregate function which could be used to return the number of possible schedules
/*
else if ($cmd == "countSchedules") {
  //  Aggregate function which counts all rows in schedule table and rows in CourseSelected table
  // Number of rows in schedule / Number of courses = Number of schedules
  $rowsInSchedule = "SELECT COUNT(*) AS \"scheduleRows\" 
                FROM Schedule
                WHERE studentID='".$params['stuid']."';";
                
  $rowsInCourseSelected = "SELECT COUNT(*) AS \"courseRows\" 
                FROM CourseSelected
                WHERE studentID='".$params['stuid']."';";        
         
   
   $rowSchedule = mysql_query($rowsInSchedule) or die(mysql_error());
   $rowCourses = mysql_query($rowsInCourseSelected) or die(mysql_error());
     
    while($row1 = mysql_fetch_array($rowSchedule)){
    $result1 = ($row1['scheduleRows'] );
  }
  
     while( $row2 = mysql_fetch_array($rowCourses) ){
     $result2 = ($row2['courseRows'] );
  }

  echo($result1 / $result2);
}
*/

    mysql_close($connection);

?>