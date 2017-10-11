DROP DATABASE IF EXISTS cars;
CREATE DATABASE cars;
USE cars;

DROP TABLE IF EXISTS users;
CREATE TABLE users
(
	rit_id VARCHAR(7) NOT NULL
	,first_name VARCHAR(25)
	,last_name VARCHAR(50)
	,email VARCHAR(32)
	,inactive TINYINT(1)DEFAULT 0
	,role_id TINYINT(1) NOT NULL
	,PRIMARY KEY(rit_id)
	,CONSTRAINT unique_rit_id UNIQUE(rit_id)
)
;

DROP TABLE IF EXISTS role;
CREATE TABLE role
(
	role_id TINYINT NOT NULL
	,role_name VARCHAR(30)
	,PRIMARY KEY(role_id)
)
;

DROP TABLE IF EXISTS token;
CREATE TABLE token
(
	rit_id VARCHAR(7) NOT NULL
	,token VARCHAR(50) NOT NULL
	,expiration DATETIME NOT NULL
	,PRIMARY KEY(rit_id, token)
)
;

DROP TABLE IF EXISTS preference;
CREATE TABLE preference
(
	rit_id VARCHAR(7) NOT NULL
	,academic_term SMALLINT NOT NULL
	,preference TEXT
	,PRIMARY KEY(rit_id, academic_term)
)
;

DROP TABLE IF EXISTS request;
CREATE TABLE request
(
	request_id INT AUTO_INCREMENT
	,rit_id VARCHAR(7) NOT NULL
	,section_id INT NOT NULL
	,request_order FLOAT
	,PRIMARY KEY(request_id)
)
;

DROP TABLE IF EXISTS assignment;
CREATE TABLE assignment
(
	assignment_id INT AUTO_INCREMENT
	,rit_id VARCHAR(7) NOT NULL
	,section_id INT NOT NULL
	,PRIMARY KEY(assignment_id)
)
;

DROP TABLE IF EXISTS section;
CREATE TABLE section
(
	section_id INT AUTO_INCREMENT
	,course_id INT
	,section_number TINYINT
	,academic_term SMALLINT
	,instructor_credit DOUBLE DEFAULT 1
	,section_type VARCHAR(15)
	,online TINYINT(1)
	,PRIMARY KEY(section_id)
)
;

DROP TABLE IF EXISTS academic_term;
CREATE TABLE academic_term
(
	academic_term SMALLINT NOT NULL
	,term_description VARCHAR(20)
	,status TINYINT
	,PRIMARY KEY(academic_term)
)
;

DROP TABLE IF EXISTS section_time;
CREATE TABLE section_time
(
	section_time_id INT AUTO_INCREMENT
	,section_id INT
	,day_of_week CHAR(2)
	,start_time TIME
	,end_time TIME
	,room_id INT
	,PRIMARY KEY(section_time_id)
)
;

DROP TABLE IF EXISTS course;
CREATE TABLE course
(
	course_id INT AUTO_INCREMENT
	,program_code CHAR(4) NOT NULL
	,course_number SMALLINT
	,course_name VARCHAR(100)
	,course_description TEXT
	,course_notes TEXT
	,colisted_id INT
	,student_credits SMALLINT
	,archived TINYINT(1) DEFAULT 0
	,class_contact_hours INT
	,lab_contact_hours INT
	,PRIMARY KEY(course_id)
)
;

DROP TABLE IF EXISTS department;
CREATE TABLE department
(
	department_id INT AUTO_INCREMENT
	,department_name VARCHAR(75)
	,department_abbreviation VARCHAR(5)
	,PRIMARY KEY(department_id)
)
;

DROP TABLE IF EXISTS program;
CREATE TABLE program
(
	program_id INT AUTO_INCREMENT
	,program_code CHAR(4) NOT NULL
	,program_name VARCHAR(50)
	,degree ENUM('Adv. Cert.', 'BS', 'MS', 'Ph.D.')
	,department_id INT
	,PRIMARY KEY(program_id)
)
;

DROP TABLE IF EXISTS users_department;
CREATE TABLE users_department
(
	rit_id VARCHAR(7)
	,department_id INT
	,PRIMARY KEY(rit_id, department_id)
)
;

DROP TABLE IF EXISTS status;
CREATE TABLE status
(
	status TINYINT NOT NULL
	,status_description VARCHAR(25)
	,PRIMARY KEY(status)
)
;

DROP TABLE IF EXISTS building;
CREATE TABLE building
(
	building_abbreviation CHAR(3)
	,facility_name VARCHAR(75)
	,PRIMARY KEY(building_abbreviation)
)
;

DROP TABLE IF EXISTS room;
CREATE TABLE room
(
	room_id INT AUTO_INCREMENT
	,room_number VARCHAR(15)
	,seat_count SMALLINT
	,building_abbreviation CHAR(3)
	,department_id INT
	,PRIMARY KEY(room_id)
)
;

-- Foreign Keys for ACADEMIC_TERM
ALTER TABLE academic_term
ADD CONSTRAINT fk_AcademicTermStatus
FOREIGN KEY (status)
REFERENCES status(status)
;

-- Foreign Keys for ASSIGNMENT
ALTER TABLE assignment
ADD CONSTRAINT fk_AssignmentUsers
FOREIGN KEY (rit_id)
REFERENCES users(rit_id)
;

ALTER TABLE assignment
ADD CONSTRAINT fk_Assignmentsection
FOREIGN KEY (section_id)
REFERENCES section(section_id)
;

/*
-- Foreign Key for COURSE
ALTER TABLE course
ADD CONSTRAINT fk_CourseProgram
FOREIGN KEY (program_code)
REFERENCES program(program_code)
;
*/

-- Foreign Keys for PREFERENCE
ALTER TABLE preference
ADD CONSTRAINT fk_PreferenceUsers
FOREIGN KEY (rit_id)
REFERENCES users(rit_id)
;

ALTER TABLE preference
ADD CONSTRAINT fk_PreferenceAcademicTerm
FOREIGN KEY (academic_term)
REFERENCES academic_term(academic_term)
;

-- Foreign Keys for PROGRAM
ALTER TABLE program
ADD CONSTRAINT fk_ProgramDepartment
FOREIGN KEY (department_id)
REFERENCES department(department_id)
;

-- Foreign Keys for REQUEST
ALTER TABLE request
ADD CONSTRAINT fk_RequestUsers
FOREIGN KEY (rit_id)
REFERENCES users(rit_id)
;

ALTER TABLE request
ADD CONSTRAINT fk_Requestsection
FOREIGN KEY (section_id)
REFERENCES section(section_id)
;

-- Foreign Keys for ROOM
ALTER TABLE room
ADD CONSTRAINT fk_RoomBuilding
FOREIGN KEY (building_abbreviation)
REFERENCES building(building_abbreviation)
;

ALTER TABLE room
ADD CONSTRAINT fk_RoomDepartment
FOREIGN KEY (department_id)
REFERENCES department(department_id)
;

-- Foreign Keys for SECTION
ALTER TABLE section
ADD CONSTRAINT fk_sectionAcademicTerm
FOREIGN KEY (academic_term)
REFERENCES academic_term(academic_term)
;

ALTER TABLE section
ADD CONSTRAINT fk_sectionCourse
FOREIGN KEY (course_id)
REFERENCES course(course_id)
;

-- Foreign Keys for SECTION_TIME
ALTER TABLE section_time
ADD CONSTRAINT fk_SectionTimeSection
FOREIGN KEY (section_id)
REFERENCES section(section_id)
;

ALTER TABLE section_time
ADD CONSTRAINT fk_SectionTimeRoom
FOREIGN KEY (room_id)
REFERENCES room(room_id)
;

-- Foreign Keys for TOKEN
ALTER TABLE token
ADD CONSTRAINT fk_TokenUsers
FOREIGN KEY (rit_id)
REFERENCES users(rit_id)
;

-- Foreign Keys for USERS
ALTER TABLE users
ADD CONSTRAINT fk_UsersRole
FOREIGN KEY (role_id)
REFERENCES role(role_id)
;

-- Foreign Keys for USERS_DEPARTMENT
ALTER TABLE users_department
ADD CONSTRAINT fk_UsersUsersDepartment
FOREIGN KEY (rit_id)
REFERENCES users(rit_id)
;

ALTER TABLE users_department
ADD CONSTRAINT fk_DepartmentUsersDepartment
FOREIGN KEY (department_id)
REFERENCES department(department_id)
;


-- ROLE
INSERT INTO role (role_id, role_name) VALUES
(1,"System Administrator")
,(2,"Department Administrator")
,(3,"Department Manager")
,(4,"Instructor")
,(5,"Adjunct")
,(6,"Student Employee")
;

-- STATUS
INSERT INTO status (status, status_description) VALUES
(1, "Under Development")
,(2, "Open for Requests")
,(3, "Open for Assignment")
,(4, "Closed")
;

-- ACADEMIC_TERM
INSERT INTO academic_term (academic_term, term_description, status) VALUES
(2155, "Spring 2015",4)
,(2161,"Fall 2016",4)
,(2165,"Spring 2016",3)
,(2171,"Fall 2017",2)
,(2175, "Spring 2017", 1)
;

-- DEPARTMENT
INSERT INTO department (department_name, department_abbreviation) VALUES
("Information Sciences and Technology", "IST")
,("Software Engineering", "SE")
,("Computer Science","CS")
,("Interactive Games and Media","IGM")
,("Computer Security","CSEC")
,("Golisano College of Commuting and Information Sciences", "GCCIS")
;

-- PROGRAM
INSERT INTO program(program_code,program_name,degree,department_id) VALUES
("ISTE","Web and Mobile Computing","BS",1)
,("ISTE","Human-Centered Computing","BS",1)
,("NSSA","Computing and Information Technologies","BS",1)
,("ISTE","Information Sciences and Technologies","MS",1)
,("HCIN","Human-Computer Interaction","MS",1)
,("NSSA","Networking and Systems Administration","MS",1)
,("SWEN","Software Engineering","BS",2)
,("SWEN","Software Engineering","MS",2)
,("CSCI","Computer Science","BS",3)
,("CSCI","Computer Science","MS",3)
,("NMDE","New Media Interactive Development","BS",4)
,("IGME","Game Design and Development","BS",4)
,("IGME","Game Design and Development","MS",4)
,("CSEC","Computing Security","BS",5)
,("CSEC","Computing Security","MS",5)
,("CISC","Computing and INformation Sciences","Ph.D.",6)
;

-- BUILDING
INSERT INTO building (building_abbreviation, facility_name) VALUES
("BLC", "Bausch and Lomb Center")
,("BOO", "James E. Booth Hall")
,("BRN", "Brown Hall")
,("CAR", "Chester F. Carlson Center for Imaging Science")
,("CSI", "Center for Student Innovation")
,("EAS", "George Eastman Hall")
,("ENG", "Engineering Hall")
,("ENT", "Engineering Technology Hall")
,("GAN", "Frank E. Gannett Hall")
,("GLE", "James E. Gleason Hall")
,("GOL", "Golisano Hall")
,("GOS", "Thomas Gosnell Hall")
,("HAC", "Hale-Andrews Student Life Center")
,("LAC", "Laboratory for Applied Computing")
,("LBJ", "Lyndon Baines Johnson Hall")
,("LBR", "Liberal Arts Hall")
,("LOW", "Max Lowenthal Hall")
,("ORN", "Orange Hall")
,("ROS", "Lewis P. Ross Hall")
,("SLA", "Louise Slaughter Hall")
,("SUS", "Sustainability Institute")
,("USC", "University Services Center")
,("WAL", "Wallace Library")
;

INSERT INTO room (room_number,seat_Count,building_abbreviation,department_id) VALUES
("1400",NULL,"GOL",NULL)
,("2000",NULL,"EAS",NULL)
,("3381",NULL,"EAS",NULL)
,("3201",NULL,"LBR",NULL)
,("3215",NULL,"LBR",NULL)
,("1350",NULL,"BOO",NULL)
,("1400",NULL,"BOO",NULL)
,("1440",NULL,"BOO",NULL)
,("1560",NULL,"BOO",NULL)
,("A145",NULL,"GAN",NULL)
,("A410",NULL,"BOO",NULL)
,("1174",NULL,"GOS",NULL)
,("1305",NULL,"GOS",NULL)
,("2130",NULL,"GOS",NULL)
,("2355",NULL,"GOS",NULL)
,("2365",NULL,"GOS",NULL)
,("3305",NULL,"GOS",NULL)
,("3365",NULL,"GOS",NULL)
,("A300",NULL,"GOS",NULL)
,("1139",NULL,"GLE",NULL)
,("1149",NULL,"GLE",NULL)
,("1159",NULL,"GLE",NULL)
,("2149",NULL,"GLE",NULL)
,("2159",NULL,"GLE",NULL)
,("3129",NULL,"GLE",NULL)
,("3139",NULL,"GLE",NULL)
,("3149",NULL,"GLE",NULL)
,("A330",NULL,"ROS",NULL)
,("1125",NULL,"LOW",NULL)
,("1215",NULL,"LOW",NULL)
,("1350",NULL,"LOW",NULL)
,("3235",NULL,"LOW",NULL)
,("1340",NULL,"ORN",NULL)
,("1350",NULL,"ORN",NULL)
,("1355",NULL,"ORN",NULL)
,("1360",NULL,"ORN",NULL)
,("1370",30,"ORN",4)
,("1380",30,"ORN",4)
,("1535",NULL,"ENG",NULL)
,("1545",NULL,"ENG",NULL)
,("1555",NULL,"ENG",NULL)
,("3214",NULL,"LBR",NULL)
,("1420",NULL,"BOO",NULL)
,("1435",NULL,"GOL",NULL)
,("1445",NULL,"GOL",NULL)
,("1455",NULL,"GOL",NULL)
,("1520",NULL,"GOL",NULL)
,("1610",NULL,"GOL",NULL)
,("1620",NULL,"GOL",NULL)
,("2130",63,"GOL",1)
,("2160",51,"GOL",1)
,("2320",84,"GOL",1)
,("2330",NULL,"GOL",5)
,("2400",NULL,"GOL",6)
,("2410",26,"GOL",5)
,("2435",43,"GOL",4)
,("2455",NULL,"GOL",NULL)
,("2500",20,"GOL",6)
,("2520",30,"GOL",1)
,("2530",NULL,"GOL",4)
,("2550",25,"GOL",4)
,("2570",36,"GOL",4)
,("2590",NULL,"GOL",NULL)
,("2620",40,"GOL",1)
,("2650",40,"GOL",1)
,("2670",45,"GOL",1)
,("2690",NULL,"GOL",NULL)
,("3435",NULL,"GOL",3)
,("3445",NULL,"GOL",3)
,("3455",NULL,"GOL",3)
,("3560",NULL,"GOL",NULL)
,("3690",36,"GOL",1)
,("3540",NULL,"GOL",3)
,("3520",NULL,"GOL",3)
,("3660",NULL,"GOL",3)
,("3650",NULL,"GOL",3)
,("3620",NULL,"GOL",3)
,("1155",NULL,"CAR",NULL)
,("1210",NULL,"CAR",NULL)
,("1230",NULL,"CAR",NULL)
,("1235",NULL,"CAR",NULL)
,("4020",NULL,"GAN",NULL)
,("1100",NULL,"BRN",NULL)
,("1110",40,"BRN",NULL)
,("1120",NULL,"BRN",NULL)
,("1150",NULL,"BRN",NULL)
,("3640",NULL,"GOL",3)
,("A201",NULL,"LBR",NULL)
,("1320",NULL,"HAC",NULL)
,("1440",NULL,"GOL",NULL)
,("3000",NULL,"GOL",NULL)
,("3400",NULL,"GOL",NULL)
,("3550",NULL,"GOL",3)
,("3610",NULL,"GOL",NULL)
,("1125",NULL,"CAR",NULL)
,("1111",NULL,"GOS",NULL)
,("1550",NULL,"GOL",2)
,("A400",NULL,"WAL",NULL)
,("A205",NULL,"LBR",NULL)
,("3496",NULL,"BOO",NULL)
,("1155",NULL,"GOL",NULL)
,("2365",NULL,"SLA",NULL)
,("3225",NULL,"LOW",NULL)
,("1530",NULL,"GOL",NULL)
,("1640",NULL,"GOL",2)
,("1135",NULL,"LOW",NULL)
,("4202",NULL,"GAN",NULL)
,("2000",30,"GOL",4)
,("1300",NULL,"GOS",NULL)
,("3105",NULL,"BOO",NULL)
,("2025",30,"GOL",4)
,("1069",NULL,"LAC",NULL)
,("3220",NULL,"LBR",NULL)
,("3225",NULL,"LBR",NULL)
,("3287",NULL,"EAS",NULL)
,("A260",NULL,"LBR",NULL)
,("A151",NULL,"BOO",NULL)
,("1210",NULL,"ORN",NULL)
,("3215",NULL,"LOW",3)
,("1250",NULL,"GOS",NULL)
,("3245",NULL,"LOW",3)
,("152030",NULL,"GOL",NULL)
,("1600",NULL,"CSI",4)
,("2560",NULL,"GOL",3)
,("A310",NULL,"ROS",3)
,("2139",NULL,"GLE",3)
,("1570",NULL,"GOL",2)
,("2129",NULL,"GLE",NULL)
,("1375",NULL,"ORN",3)
,("3280",NULL,"GLE",3)
,("1325",NULL,"HAC",NULL)
,("3500",15,"GOL",3)
,("3600",NULL,"GOL",3)
,("1435",NULL,"CAR",3)
,("3510",NULL,"GOL",1)
,("1650",NULL,"GOL",2)
,("2160",NULL,"SLA",1)
,("1320",NULL,"LBJ",1)
,("2325",NULL,"LBJ",NULL)
,("1440",NULL,"LBJ",NULL)
,("1310",NULL,"LBJ",NULL)
,("1445",NULL,"LBJ",NULL)
,("2335",NULL,"LBJ",NULL)
,("2345",NULL,"LBJ",NULL)
;
