USE cars;

-- USERS
INSERT INTO users (rit_id, first_name, last_name, email, inactive, role_id) VALUES
("jak3554","Jon","Koch","jak3554@rit.edu",0,1)
,("jmt4735","Jon","Theisman","jmt4735@rit.edu",0,2)
,("cvc6644","Chase","Caynoski","cvc6644@rit.edu",0,3)
,("jjw3782","Jeremiah","Wong","jjw3782@rit.edu",0,4)
,("aeb8554","Amanda","Barton","aeb8554@rit.edu",0,5)
;

-- USERS_DEPARTMENT
INSERT INTO users_department(department_id,rit_id) VALUES
(1,"aeb8554")
,(1,"cvc6644")
,(1,"jak3554")
,(1,"jjw3782")
,(1,"jmt4735")
;

INSERT INTO course (program_code,course_number,course_name,course_description,colisted_id,student_credits,archived,lab_contact_hours,class_contact_hours) VALUES
("ISTE",120,"Computational Problem Solving in the Information Domain I","A first course in using the object-oriented approach to solve problems in the information domain. Students will learn to model hierarchical information structures using XML, to design software solutions using the object-oriented approach, to visually model systems using UML, to implement software solutions using a contemporary programming language, and to test these software solutions. Additional topics include thinking in object-oriented terms, and problem definition. Programming projects will be required.",NULL,3,0,0,3)
;
