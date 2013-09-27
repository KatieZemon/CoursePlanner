<?php 
// Install script containing all data in the database

// Connects to the database
mysql_connect("127.0.0.1", "jakekra1_katie", "database238") or die(mysql_error()); 
mysql_select_db("jakekra1_katie") or die(mysql_error()); 
 


 
 
/****************** Student ******************/
mysql_query("
  CREATE TABLE IF NOT EXISTS Student(
    username VARCHAR(64),
    password VARCHAR(64),
    studentID int(9) NOT NULL ,
    fname VARCHAR(64),
    mname VARCHAR(64),
    lname VARCHAR(64),
    PRIMARY KEY (studentID)
  );"
);
// Students within Joe'SS Database

mysql_query("INSERT INTO Student Values('kjihdc','".md5("pass1")."','12345678','Kathryn','Jane','Isbell');");
mysql_query("INSERT INTO Student Values('jk7b6','".md5("pass2")."','90909090','Jacob','Jon','Kramer');");
mysql_query("INSERT INTO Student Values('jub4h5','".md5("pass3")."','77777777','Jake','Zed','Bielefeldt');");



/****************** Term ******************/
mysql_query("
  CREATE TABLE IF NOT EXISTS Term(
    semester VARCHAR(64) NOT NULL,
    year int(4) NOT NULL ,
    PRIMARY KEY(semester,year)
  );"
);
// Terms in which courses are offered
/*
mysql_query("INSERT INTO Term Values('Fall','2011');");
mysql_query("INSERT INTO Term Values('Spring','2012');");
mysql_query("INSERT INTO Term Values('Summer','2012');");
*/


/****************** Course ******************/
mysql_query("
  CREATE TABLE IF NOT EXISTS Course(
    department VARCHAR(64) NOT NULL,
    catalogNumber int(3) NOT NULL,
    name VARCHAR(64) NOT NULL,
    credits INT,
	courseID INT,
    PRIMARY KEY (courseID),
    UNIQUE ID (department,catalogNumber)
  );"
);

/****************** Course Selected ******************/
mysql_query("
  CREATE TABLE IF NOT EXISTS CourseSelected(
  courseID INT REFERENCES Course,
	studentID INT(9) REFERENCES Student,
    PRIMARY KEY (courseID,studentID)
  );"
);

// Courses
mysql_query("INSERT INTO Course Values('ENGLISH','20','Exposition and Argumentation',3,12100);");
mysql_query("INSERT INTO Course Values('ENGLISH','60','Writing and Research',3,12200);");
mysql_query("INSERT INTO Course Values('ENGLISH','65','Introduction to Technical Communication',3,12300);");

mysql_query("INSERT INTO Course Values('COMP SCI','53','Introduction to Programming',3,13100);");
mysql_query("INSERT INTO Course Values('COMP SCI','54','Introduction to Programming Lab',1,13200);");
mysql_query("INSERT INTO Course Values('COMP SCI','128','Discrete Mathematics for Computer Science',3,13300);");
mysql_query("INSERT INTO Course Values('COMP SCI','153','Data Structures',3,13400);");
mysql_query("INSERT INTO Course Values('COMP SCI','206','Software Engineering I',3,13500);");
mysql_query("INSERT INTO Course Values('COMP SCI','253','Algorithms',3,13600);");
mysql_query("INSERT INTO Course Values('COMP SCI','238','File Structures and Introduction to Database Systems',3,13700);");

mysql_query("INSERT INTO Course Values('MATH','2','College Algebra',5,14100);");
mysql_query("INSERT INTO Course Values('MATH','4','College Algebra',3,14200);");
mysql_query("INSERT INTO Course Values('MATH','6','Trigonometry',3,14300);");
mysql_query("INSERT INTO Course Values('MATH','14','Calculus for Engineers I',4,14400);");
mysql_query("INSERT INTO Course Values('MATH','15','Calculus for Engineers II',4,14500);");
mysql_query("INSERT INTO Course Values('MATH','22','Calculus With Analytic Geometry III',4,14600);");
mysql_query("INSERT INTO Course Values('MATH','204','Elementary Differential Equations',3,14700);");
mysql_query("INSERT INTO Course Values('MATH','208','Linear Algebra',3,14800);");

mysql_query("INSERT INTO Course Values('COMP ENG','111','Introduction to Computer Engineering',3,15100);");
mysql_query("INSERT INTO Course Values('COMP ENG','112','Computer Engineering Laboratory',1,15200);");
mysql_query("INSERT INTO Course Values('COMP ENG','213','Digital Systems Design',3,15300);");
mysql_query("INSERT INTO Course Values('COMP ENG','214','Digital Engineering Lab II',1,15400);");

mysql_query("INSERT INTO Course Values('EE','121','Introduction to Electronic Devices',3,16100);");
mysql_query("INSERT INTO Course Values('EE','122','Electronic Devices Laboratory',1,16200);");
mysql_query("INSERT INTO Course Values('EE','151','Circuits I',3,16300);");
mysql_query("INSERT INTO Course Values('EE','152','Circuit Analysis Laboratory I',1,16400);");

mysql_query("INSERT INTO Course Values('PHYSICS','21','General Physics I',4,17100);");
mysql_query("INSERT INTO Course Values('PHYSICS','22','General Physics Laboratory',1,17200);");
mysql_query("INSERT INTO Course Values('PHYSICS','23','Engineering Physics I',4,17300);");
mysql_query("INSERT INTO Course Values('PHYSICS','24','Engineering Physics II',4,17400);");



/****************** Student Transcript ******************/
mysql_query("
  CREATE TABLE IF NOT EXISTS StudentTranscript(
  sid INT REFERENCES Student(studentID),
  courseTaken INT REFERENCES Course(courseID),
  grade CHAR, 
  PRIMARY KEY (sid, courseTaken)
  );"
);


// Transcripts
//kjihdc
mysql_query("INSERT INTO StudentTranscript Values(12345678,13100,'A');");
mysql_query("INSERT INTO StudentTranscript Values(12345678,13200,'A');");
mysql_query("INSERT INTO StudentTranscript Values(12345678,13300,'B');");

mysql_query("INSERT INTO StudentTranscript Values(12345678,14100,'A');");
mysql_query("INSERT INTO StudentTranscript Values(12345678,14200,'B');");
mysql_query("INSERT INTO StudentTranscript Values(12345678,14300,'C');");
mysql_query("INSERT INTO StudentTranscript Values(12345678,14400,'A');");
mysql_query("INSERT INTO StudentTranscript Values(12345678,14500,'D');");




//jub4h5
mysql_query("INSERT INTO StudentTranscript Values(77777777,12100,'B');");

mysql_query("INSERT INTO StudentTranscript Values(77777777,13100,'A');");
mysql_query("INSERT INTO StudentTranscript Values(77777777,13600,'C');");
mysql_query("INSERT INTO StudentTranscript Values(77777777,13300,'B');");
mysql_query("INSERT INTO StudentTranscript Values(77777777,13400,'A');");

mysql_query("INSERT INTO StudentTranscript Values(77777777,14100,'A');");
mysql_query("INSERT INTO StudentTranscript Values(77777777,14200,'A');");
mysql_query("INSERT INTO StudentTranscript Values(77777777,14300,'C');");
mysql_query("INSERT INTO StudentTranscript Values(77777777,14400,'B');");
mysql_query("INSERT INTO StudentTranscript Values(77777777,14500,'A');");



/****************** CourseRequirement / Prerequisite ******************/
mysql_query("
  CREATE TABLE IF NOT EXISTS CourseRequirement(
  cid1 INT REFERENCES Course(courseID),
  cid2 INT REFERENCES Course(courseID),
  cName1 VARCHAR(16),
  cName2 VARCHAR(16),
  PRIMARY KEY (cid1, cid2)
  );"
);

// Course Requirements -- List of CourseIDs and the CourseIDs of their prerequisites

// ENGLISH
mysql_query("INSERT INTO CourseRequirement Values(12200,12100,'ENGLISH 60','ENGLISH 20');");
mysql_query("INSERT INTO CourseRequirement Values(12300,12100,'ENGLISH 65','ENGLISH 20');");

// COMP SCI
mysql_query("INSERT INTO CourseRequirement Values(13300,13100,'COMP SCI 128','COMP SCI 53');");
mysql_query("INSERT INTO CourseRequirement Values(13400,13100,'COMP SCI 153','COMP SCI 53');");
mysql_query("INSERT INTO CourseRequirement Values(13500,13600,'COMP SCI 206','COMP SCI 253');");

mysql_query("INSERT INTO CourseRequirement Values(13600,13300,'COMP SCI 253','COMP SCI 128');");
mysql_query("INSERT INTO CourseRequirement Values(13600,13400,'COMP SCI 253','COMP SCI 153');");

mysql_query("INSERT INTO CourseRequirement Values(13700,13400,'COMP SCI 238','COMP SCI 153');");


// MATH
mysql_query("INSERT INTO CourseRequirement Values(14300,14100,'MATH 6','MATH 2');");
mysql_query("INSERT INTO CourseRequirement Values(14300,14200,'MATH 6','MATH 4');");
mysql_query("INSERT INTO CourseRequirement Values(14400,14300,'MATH 14','MATH 6');");
mysql_query("INSERT INTO CourseRequirement Values(14500,14400,'MATH 15','MATH 14');");
mysql_query("INSERT INTO CourseRequirement Values(14700,14600,'MATH 204','MATH 22');");
mysql_query("INSERT INTO CourseRequirement Values(14800,14500,'MATH 208','MATH 15');");
mysql_query("INSERT INTO CourseRequirement Values(14600,14500,'MATH 22','MATH 15');");

// COMP ENG
mysql_query("INSERT INTO CourseRequirement Values(15300,15100,'COMP ENG 213','COMP ENG 111');");
mysql_query("INSERT INTO CourseRequirement Values(15300,13100,'COMP ENG 213','COMP SCI 53');");
mysql_query("INSERT INTO CourseRequirement Values(15400,15300,'COMP ENG 214','COMP ENG 213');");
mysql_query("INSERT INTO CourseRequirement Values(15400,16100,'COMP ENG 214','EE 121');");
mysql_query("INSERT INTO CourseRequirement Values(15400,16200,'COMP ENG 214','EE 122');");

// EE
mysql_query("INSERT INTO CourseRequirement Values(16100,16300,'EE121','EE 151');");
mysql_query("INSERT INTO CourseRequirement Values(16100,16400,'EE121','EE 152');");
mysql_query("INSERT INTO CourseRequirement Values(16100,17400,'EE121','PHYSICS 24');");
mysql_query("INSERT INTO CourseRequirement Values(16200,16300,'EE122','EE 151');");
mysql_query("INSERT INTO CourseRequirement Values(16200,16400,'EE122','EE 152');");
mysql_query("INSERT INTO CourseRequirement Values(16200,17400,'EE122','PHYSICS 24');");
mysql_query("INSERT INTO CourseRequirement Values(16300,14500,'EE151','MATH 15');");
mysql_query("INSERT INTO CourseRequirement Values(16400,14500,'EE152','MATH 15');");

// PHYSICS
mysql_query("INSERT INTO CourseRequirement Values(17100,14400,'PHYSICS 21','MATH 14');");
mysql_query("INSERT INTO CourseRequirement Values(17200,14400,'PHYSICS 22','MATH 14');");
mysql_query("INSERT INTO CourseRequirement Values(17300,14400,'PHYSICS 23','MATH 14');");
mysql_query("INSERT INTO CourseRequirement Values(17400,14500,'PHYSICS 24','MATH 15');");
mysql_query("INSERT INTO CourseRequirement Values(17400,17300,'PHYSICS 24','PHYSICS 23');");

/****************** Section ******************/
mysql_query("
  CREATE TABLE IF NOT EXISTS Section(
  cid INT,
  sectionID INT,
  sTime INT,
  eTime INT,
  day VARCHAR(16),
  location VARCHAR(32),
  letter CHAR,
  instructor VARCHAR(32),
  availability CHAR, 
  PRIMARY KEY (sectionID),
  FOREIGN KEY (cid) REFERENCES Course(courseID)
  );"
);


/////////////////////// Sections Offered/////////////////////////
// ENGLISH 20
mysql_query("INSERT INTO Section Values(12100,12100,800,850,'MWF','Human-Soc Sci 203','A','Randall Arthur','A');");
mysql_query("INSERT INTO Section Values(12100,12101,900,950,'MWF','Human-Soc Sci 203','B','Kelly Tate','A');");
mysql_query("INSERT INTO Section Values(12100,12102,1100,1150,'MWF','Human-Soc Sci 203','C','Mathew Goldberg','A');");
mysql_query("INSERT INTO Section Values(12100,12103,1300,1415,'TR','Human-Soc Sci 203','D','Lindgren Johnson','A');");

// ENGLISH 60
mysql_query("INSERT INTO Section Values(12200,12200,800,850,'MWF','Engineering Manage 106','A','Daniel Reardon','A');");
mysql_query("INSERT INTO Section Values(12200,12201,900,950,'MWF','Engineering Manage 106','B','Daniel Reardon','A');");
mysql_query("INSERT INTO Section Values(12200,12202,1000,1050,'MWF','Human-Soc Sci 201','C','Alexander Wulff','A');");
mysql_query("INSERT INTO Section Values(12200,12203,800,850,'MWF','Human-Soc Sci 201','D','Alexander Wulff','A');");

// ENGLISH 65
mysql_query("INSERT INTO Section Values(12300,12300,800,850,'MWF','Human-Soc Sci 201','A','David Young','A');");
mysql_query("INSERT INTO Section Values(12300,12301,900,950,'MWF','Campus Support Facil 114','B','David Young','A');");
mysql_query("INSERT INTO Section Values(12300,12302,1000,1050,'MWF','Human-Soc Sci 201','C','Alexander Wulff','A');");
mysql_query("INSERT INTO Section Values(12300,12303,800,850,'MWF','Human-Soc Sci 201','D','Alexander Wulff','A');");

// COMP SCI 53
mysql_query("INSERT INTO Section Values(13100,13100,800,850,'MWF','Computer Science 206','A','Clayton Price','A');");
mysql_query("INSERT INTO Section Values(13100,13101,900,950,'MWF','Computer Science 203','B','Staff','A');");
mysql_query("INSERT INTO Section Values(13100,13102,1000,1050,'MWF','Computer Science 206','C','Staff','W');");
mysql_query("INSERT INTO Section Values(13100,13103,800,850,'MWF','Computer Science 203','D','Clayton Price','A');");


// COMP SCI 54
mysql_query("INSERT INTO Section Values(13200,13200,1000,1150,'T','Inter Engineering 105','A','Staff','A');");
mysql_query("INSERT INTO Section Values(13200,13201,800,950,'T','Centennial Hall 105','B','Staff','A');");
mysql_query("INSERT INTO Section Values(13200,13202,1600,1750,'R','Centennial Hall 105','C','Staff','A');");
mysql_query("INSERT INTO Section Values(13200,13203,800,950,'R','Centennial Hall 105','D','Staff','A');");

// COMP SCI 128
mysql_query("INSERT INTO Section Values(13300,13300,800,850,'MWF','Computer Science 203','A','Chaman Sabharwal','A');");
mysql_query("INSERT INTO Section Values(13300,13301,900,950,'MWF','Computer Science 203','B','Chaman Sabharwal','A');");
mysql_query("INSERT INTO Section Values(13300,13302,1100,1150,'MWF','Computer Science 207','C','Xiaoyan Cheng','A');");
mysql_query("INSERT INTO Section Values(13300,13303,1100,1215,'TR','Computer Science 207','D','Xiaoyan Cheng','A');");
mysql_query("INSERT INTO Section Values(13300,13304,1300,1350,'MWF','Computer Science 203','E','Bruce Mcmillin','W');");
mysql_query("INSERT INTO Section Values(13300,13305,1100,1215,'TR','Computer Science 203','F','Bruce Mcmillin','W');");


// COMP SCI 153
mysql_query("INSERT INTO Section Values(13400,13400,1000,1050,'MWF','V.H. Mcnutt Hall 204','A','Staff','A');");
mysql_query("INSERT INTO Section Values(13400,13401,1300,1350,'MWF','V.H. Mcnutt Hall 206','B','Staff','A');");
mysql_query("INSERT INTO Section Values(13400,13402,1400,1450,'MWF','V.H. Mcnutt Hall 206','C','Staff','A');");
mysql_query("INSERT INTO Section Values(13400,13403,1300,1350,'MWF','V.H. Mcnutt Hall 204','D','Staff','A');");


// COMP SCI 206
mysql_query("INSERT INTO Section Values(13500,13500,1500,1650,'M','Computer Science 209','A','Thomas Weigert','A');");
mysql_query("INSERT INTO Section Values(13500,13501,1400,1550,'F','Campus Support Facil 114','B','Thomas Weigert','A');");
mysql_query("INSERT INTO Section Values(13500,13502,1500,1650,'T','Human-Soc Sci 201','C','William Bond','A');");
mysql_query("INSERT INTO Section Values(13500,13503,1500,1650,'R','Human-Soc Sci 201','D','William Bond','W');");
mysql_query("INSERT INTO Section Values(13500,13504,1200,1350,'R','Human-Soc Sci 201','E','William Bond','A');");
mysql_query("INSERT INTO Section Values(13500,13505,1400,1550,'R','Human-Soc Sci 201','F','William Bond','U');");

// COMP SCI 253
mysql_query("INSERT INTO Section Values(13600,13600,1300,1350,'MWF','Computer Science 216','A','Fikret Ercal','A');");
mysql_query("INSERT INTO Section Values(13600,13601,800,850,'MWF','Computer Science 216','B','Fikret Ercal','A');");
mysql_query("INSERT INTO Section Values(13600,13602,100,1050,'MWF','Computer Science 216','C','Fikret Ercal','W');");

// COMP SCI 238
mysql_query("INSERT INTO Section Values(13700,13700,1400,1515,'TR','Computer Science 203','A','Dan Lin','A');");
mysql_query("INSERT INTO Section Values(13700,13701,800,915,'TR','Computer Science 203','B','Dan Lin','A');");


// MATH 2
mysql_query("INSERT INTO Section Values(14100,14100,800,850,'MTWRF','Butler-Carlton Hall 314','A','Staff','A');");
mysql_query("INSERT INTO Section Values(14100,14101,800,850,'MTWRF','Computer Science 202','B','Staff','A');");
mysql_query("INSERT INTO Section Values(14100,14102,1100,1150,'MTWRF','Computer Science 202','C','Staff','A');");
mysql_query("INSERT INTO Section Values(14100,14103,1300,1350,'MTWRF','Computer Science 202','D','Staff','A');");


// MATH 4
mysql_query("INSERT INTO Section Values(14200,14200,800,850,'MTWRF','Centennial Hall 104','A','Staff','A');");
mysql_query("INSERT INTO Section Values(14200,14201,900,950,'MTWRF','Centennial Hall 104','B','Staff','A');");
mysql_query("INSERT INTO Section Values(14200,14202,1000,1050,'MTWRF','Centennial Hall 104','C','Staff','U');");
mysql_query("INSERT INTO Section Values(14200,14203,1100,1150,'MTWRF','Centennial Hall 104','D','Staff','A');");


// MATH 6
mysql_query("INSERT INTO Section Values(14300,14300,800,850,'MWF','Computer Science 202','A','Staff','A');");
mysql_query("INSERT INTO Section Values(14300,14301,900,950,'MWF','Computer Science 202','B','Staff','A');");
mysql_query("INSERT INTO Section Values(14300,14302,900,950,'MWF','Toomey Hall 250','C','Staff','A');");
mysql_query("INSERT INTO Section Values(14300,14303,1000,1050,'MWF','Toomey Hall 250','D','Staff','L');");
mysql_query("INSERT INTO Section Values(14300,14304,1300,1350,'MWF','Computer Science 207','E','Staff','A');");

// MATH 14
mysql_query("INSERT INTO Section Values(14400,14400,800,850,'MWF','Butler-Carlton Hall 120','A','Staff','A');");
mysql_query("INSERT INTO Section Values(14400,14401,800,850,'MWF','Butler-Carlton Hall 125','B','Staff','A');");
mysql_query("INSERT INTO Section Values(14400,14402,900,950,'MWF','Butler-Carlton Hall 120','C','Staff','W');");
mysql_query("INSERT INTO Section Values(14400,14403,900,950,'MWF','Butler-Carlton Hall 125','D','Staff','A');");
mysql_query("INSERT INTO Section Values(14400,14404,1100,1150,'MWF','Butler-Carlton Hall 120','E','Staff','A');");

// MATH 15
mysql_query("INSERT INTO Section Values(14500,14500,800,850,'MWF','Butler-Carlton Hall 120','A','Staff','A');");
mysql_query("INSERT INTO Section Values(14500,14501,900,950,'MWF','Butler-Carlton Hall 125','B','Staff','W');");
mysql_query("INSERT INTO Section Values(14500,14502,1000,1050,'MWF','Butler-Carlton Hall 120','C','Staff','A');");
mysql_query("INSERT INTO Section Values(14500,14503,1300,1350,'MWF','Butler-Carlton Hall 125','D','Staff','A');");
mysql_query("INSERT INTO Section Values(14500,14504,1400,1450,'MWF','Butler-Carlton Hall 120','E','Staff','A');");

// MATH 22
mysql_query("INSERT INTO Section Values(14600,14600,800,850,'MTWRF','Schrenk Hall 126','A','Staff','A');");
mysql_query("INSERT INTO Section Values(14600,14601,800,850,'MTWRF','Emerson Electric 102','B','Staff','A');");
mysql_query("INSERT INTO Section Values(14600,14602,900,950,'MTWRF','Inter Engineering 206','C','Staff','A');");
mysql_query("INSERT INTO Section Values(14600,14603,900,950,'MTWRF','Butler-Carlton Hall 314','D','Staff','W');");
mysql_query("INSERT INTO Section Values(14600,14604,1400,1450,'MTWRF','Centennial Hall 104','E','Staff','W');");
mysql_query("INSERT INTO Section Values(14600,14602,1100,1150,'MTWRF','Inter Engineering 206','F','Staff','A');");

// MATH 204
mysql_query("INSERT INTO Section Values(14700,14700,900,950,'MWF','Fulton 227','A','Staff','A');");
mysql_query("INSERT INTO Section Values(14700,14701,1000,1050,'MWF','Fulton 227','B','Staff','W');");
mysql_query("INSERT INTO Section Values(14700,14702,1400,1450,'MWF','Campus Support Facilities G5E','C','Staff','A');");
mysql_query("INSERT INTO Section Values(14700,14703,1100,1215,'TR','Campus Support Facilities G5E','D','Staff','W');");
mysql_query("INSERT INTO Section Values(14700,14704,1400,1450,'TR','Fulton 227','E','Staff','A');");
mysql_query("INSERT INTO Section Values(14700,14705,800,915,'TR','Fulton 227','F','Staff','A');");

// MATH 208
mysql_query("INSERT INTO Section Values(14800,14800,900,950,'MWF','Rolla G5','A','Eugene Insall','A');");
mysql_query("INSERT INTO Section Values(14800,14801,1100,1150,'MWF','Rolla G5','B','Eugene Insall','A');");
mysql_query("INSERT INTO Section Values(14800,14802,1400,1450,'MWF','V.H. Mcnutt Hall 211','C','Robert Roe','W');");
mysql_query("INSERT INTO Section Values(14800,14803,1300,1350,'MWF','Rolla G5','D','Robert Roe','A');");
mysql_query("INSERT INTO Section Values(14800,14804,900,950,'MWF','V.H. Mcnutt Hall 211','E','Stephen Clark','W');");
mysql_query("INSERT INTO Section Values(14800,14805,1300,1350,'MWF','Computer Science 203','F','Stephen Clark','A');");



// COMP ENG 111
mysql_query("INSERT INTO Section Values(15100,15100,800,850,'MWF','Emerson Electric 101','A','John Seiffertt IV','A');");
mysql_query("INSERT INTO Section Values(15100,15101,900,950,'MWF','Emerson Electric 102','B','John Seiffertt IV','A');");
mysql_query("INSERT INTO Section Values(15100,15102,1000,1050,'MWF','Emerson Electric 101','C','John Seiffertt IV','W');");
mysql_query("INSERT INTO Section Values(15100,15103,1100,1150,'MWF','Emerson Electric 102','D','John Seiffertt IV','U');");
mysql_query("INSERT INTO Section Values(15100,15104,1000,1050,'MWF','Emerson Electric 102','E','Minsu Choi','W');");
mysql_query("INSERT INTO Section Values(15100,15105,1100,1215,'TR','Emerson Electric 102','F','Minsu Choi','A');");
mysql_query("INSERT INTO Section Values(15100,15106,1230,1345,'TR','Emerson Electric 102','G','Minsu Choi','A');");


// COMP ENG 112
mysql_query("INSERT INTO Section Values(15200,15200,1300,1450,'M','Emerson Electric 209','A','Staff','A');");
mysql_query("INSERT INTO Section Values(15200,15201,1500,1650,'M','Emerson Electric 209','B','Staff','W');");
mysql_query("INSERT INTO Section Values(15200,15202,1000,1150,'T','Emerson Electric 209','C','Staff','W');");
mysql_query("INSERT INTO Section Values(15200,15203,1430,1620,'T','Emerson Electric 209','D','Staff','A');");
mysql_query("INSERT INTO Section Values(15200,15204,1500,1650,'W','Emerson Electric 209','E','Staff','W');");
mysql_query("INSERT INTO Section Values(15200,15205,1000,1150,'R','Emerson Electric 209','F','Staff','A');");

// COMP ENG 213
mysql_query("INSERT INTO Section Values(15300,15300,1100,1214,'TR','Emerson Electric G31','A','Ronald Stanley','A');");
mysql_query("INSERT INTO Section Values(15300,15301,800,915,'TR','Emerson Electric G31','B','Ronald Stanley','A');");
mysql_query("INSERT INTO Section Values(15300,15302,800,915,'TR','Emerson Electric G31','C','Maciej Zawodniok','A');");

// COMP ENG 214
mysql_query("INSERT INTO Section Values(15400,15400,800,850,'M','Emerson Electric 107','A','Staff','A');");
mysql_query("INSERT INTO Section Values(15400,15401,1000,1050,'M','Emerson Electric 107','B','Staff','W');");
mysql_query("INSERT INTO Section Values(15400,15402,1600,1650,'M','Emerson Electric 107','C','Staff','A');");

// EE 121
mysql_query("INSERT INTO Section Values(16100,16100,800,850,'MWF','Emerson Electric 102','A','Steve Watkins','A');");
mysql_query("INSERT INTO Section Values(16100,16101,1000,1050,'MWF','Emerson Electric 103','B','Steve Watkins','A');");
mysql_query("INSERT INTO Section Values(16100,16102,1100,1215,'TR','Emerson Electric 102','C','Cheng Wu','A');");
mysql_query("INSERT INTO Section Values(16100,16103,1300,1415,'TR','Emerson Electric 102','D','Theresa Swift','W');");
mysql_query("INSERT INTO Section Values(16100,16104,1000,1050,'MWF','Emerson Electric 102','E','Theresa Swift','A');");

// EE 122
mysql_query("INSERT INTO Section Values(16200,16200,1300,1450,'M','Emerson Electric 203','A','Staff','A');");
mysql_query("INSERT INTO Section Values(16200,16201,1500,1650,'M','Emerson Electric 203','B','Staff','A');");
mysql_query("INSERT INTO Section Values(16200,16202,800,950,'W','Emerson Electric 203','C','Staff','A');");
mysql_query("INSERT INTO Section Values(16200,16203,1300,1450,'F','Emerson Electric 203','D','Staff','A');");

// EE 151
mysql_query("INSERT INTO Section Values(16300,16300,800,850,'MWF','Emerson Electric 104','A','Bijaya Shrestha','A');");
mysql_query("INSERT INTO Section Values(16300,16301,900,950,'MWF','Emerson Electric 104','B','Bijaya Shrestha','A');");
mysql_query("INSERT INTO Section Values(16300,16302,1100,1215,'TR','Emerson Electric 101','C','Steve Watkins','A');");

// EE 152
mysql_query("INSERT INTO Section Values(16400,16400,800,950,'M','Emerson Electric 203','A','Staff','A');");
mysql_query("INSERT INTO Section Values(16400,16401,1100,1250,'T','Emerson Electric 203','B','Staff','A');");


// PHYSICS 21
mysql_query("INSERT INTO Section Values(17100,17100,800,950,'MTWRF','Physics 104','A','Agnes Vokjta','A');");
mysql_query("INSERT INTO Section Values(17100,17101,1000,1050,'MTWRF','Physics 104','B','Agnes Vokjta','W');");
mysql_query("INSERT INTO Section Values(17100,17102,1200,1250,'MTWRF','Physics 104','C','Agnes Vokjta','A');");
mysql_query("INSERT INTO Section Values(17100,17103,1400,1450,'MTWRF','Physics 104','D','Agnes Vokjta','A');");

// PHYSICS 22
mysql_query("INSERT INTO Section Values(17200,17200,1400,1550,'T','Physics 215','A','H.M. Sumudu Herath','A');");
mysql_query("INSERT INTO Section Values(17200,17201,1200,1350,'T','Physics 215','B','John Fenech','A');");
mysql_query("INSERT INTO Section Values(17200,17202,1300,1450,'W','Physics 215','C','John Fenech','A');");
mysql_query("INSERT INTO Section Values(17200,17203,1500,1750,'W','Physics 215','D','H.M. Sumudu Herath','A');");

// PHYSICS 23
mysql_query("INSERT INTO Section Values(17300,17300,900,950,'TR','Physics 104','A','Ronald Bieniek','A');");
mysql_query("INSERT INTO Section Values(17300,17301,1000,1050,'TR','Physics 104','B','Ronald Bieniek','A');");
mysql_query("INSERT INTO Section Values(17300,17302,1300,1350,'TR','Physics 104','C','Ronald Bieniek','A');");
mysql_query("INSERT INTO Section Values(17300,17303,1400,1450,'TR','Physics 104','D','Ronald Bieniek','A');"); 

// PHYSICS 24
mysql_query("INSERT INTO Section Values(17400,17400,900,950,'MW','Physics 104','A','Oran Pringle','A');");
mysql_query("INSERT INTO Section Values(17400,17401,1000,1050,'MW','Physics 104','B','Oran Pringle','A');");
mysql_query("INSERT INTO Section Values(17400,17402,1200,1250,'MW','Physics 104','C','Oran Pringle','A');");
mysql_query("INSERT INTO Section Values(17400,17403,1300,1350,'MW','Physics 104','D','Oran Pringle','A');");



//////////////////// Schedules ////////////////////
mysql_query("
  CREATE TABLE IF NOT EXISTS Schedule(
    scheduleID INT,
    sectionID INT,
    studentID INT,
    PRIMARY KEY (scheduleID),
    FOREIGN KEY (sectionID) REFERENCES Section,
    FOREIGN KEY (studentID) REFERENCES Student
  );"
);



 ////////////////////// Timeslot /////////////////////////
mysql_query("
  CREATE TABLE IF NOT EXISTS Timeslot(
    studentID INT REFERENCES Student,
	startTime INT,
	endTime INT,
	day VARCHAR(16),
    PRIMARY KEY (studentID,startTime,endTime,day),
    FOREIGN KEY (studentID) REFERENCES Student
  );
");


 
 //////////////////// Instructor ////////////////////
mysql_query("
  CREATE TABLE IF NOT EXISTS Instructor(
    prefID INT,
    studentID INT REFERENCES Student,
    sectionID INT REFERENCES Section,
    PRIMARY KEY (prefID,studentID,sectionID),
    FOREIGN KEY (studentID) REFERENCES Student,
    FOREIGN KEY (sectionID) REFERENCES Section    
  );"
);


///////////////////// Location /////////////////////
mysql_query("
  CREATE TABLE IF NOT EXISTS Location(
    prefID INT,
    studentID INT REFERENCES Student,
    sectionID INT REFERENCES Section,
    PRIMARY KEY (prefID,studentID,sectionID),
    FOREIGN KEY (studentID) REFERENCES Student,
    FOREIGN KEY (sectionID) REFERENCES Section    
  );
");


////////////////////// Section Preference //////////////////////////
mysql_query("
  CREATE TABLE IF NOT EXISTS SectionPreference(
    prefID INT,
    studentID INT REFERENCES Student,
    sectionID INT REFERENCES Section,
    PRIMARY KEY (prefID,studentID,sectionID),
    FOREIGN KEY (studentID) REFERENCES Student,
    FOREIGN KEY (sectionID) REFERENCES Section    
  );
");



////////////////////// Availability /////////////////////////
mysql_query("
  CREATE TABLE IF NOT EXISTS Availability(
    prefID INT,
    studentID INT REFERENCES Student,
    sectionID INT REFERENCES Section,
    PRIMARY KEY (prefID,studentID,sectionID),
    FOREIGN KEY (studentID) REFERENCES Student,
    FOREIGN KEY (sectionID) REFERENCES Section    
  );
");


echo("Successfully installed.");
?>
















