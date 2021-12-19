CREATE TABLE tblperformance
(username varchar(30) NOT NULL,
subjectName varchar(20) NOT NULL REFERENCES tblsubjects(subjectName),
topicname varchar(100) NOT NULL REFERENCES tbltopics(topicname),
question_type char(2) NOT NULL,
score numeric(3,0) NOT NULL,
start_date timestamp NOT NULL,
end_date timestamp NOT NULL,
PRIMARY KEY(username,start_date,end_date));

CREATE TABLE tbltestsection
(subjectQode numeric(1,0) NOT NULL REFERENCES tblsubjects(subjectCode),
sectionId numeric(1,0) NOT NULL,
sectionName varchar(50) NOT NULL,
sectionQuota numeric(3,0) NOT NULL,
PRIMARY KEY(sectionId,subjectQode));

INSERT INTO tbltestsection
VALUES
(3,1,'mutauro',7),
(3,2,'tsika nemagariro',10),
(3,3,'zvakatikomberedza',3),
(3,4,'nzwisiso',0),
(3,5,'zvemazuvaano',0);


LOAD DATA LOCAL INFILE 'C:/xampp/htdocs/teacher/csv/mcquestions.csv' INTO TABLE tblmcquestions FIELDS TERMINATED BY ',';
LOAD DATA LOCAL INFILE 'C:/xampp/htdocs/teacher/csv/tblstructuredquestions20190502.csv' INTO TABLE tblstructuredquestions FIELDS TERMINATED BY ',';
LOAD DATA LOCAL INFILE 'C:/xampp/htdocs/teacher/csv/structuredquestions20190505.csv' INTO TABLE tblstructuredquestions FIELDS TERMINATED BY ',';
LOAD DATA LOCAL INFILE 'C:/xampp/htdocs/teacher/csv/structuredanswers20190506.csv' INTO TABLE tblstructuredanswers FIELDS TERMINATED BY ',';

LOAD DATA LOCAL INFILE 'C:/xampp/htdocs/teacher/csv/structuredquestions20190512.csv' INTO TABLE tblstructuredquestions FIELDS TERMINATED BY ',';
LOAD DATA LOCAL INFILE 'C:/xampp/htdocs/teacher/csv/structuredanswers20190512.csv' INTO TABLE tblstructuredanswers FIELDS TERMINATED BY ',';
LOAD DATA LOCAL INFILE 'C:/xampp/htdocs/teacher/csv/storyquestions20190525.csv' INTO TABLE tblmcquestions FIELDS TERMINATED BY ',';

--Mathematics Load Maths--
LOAD DATA LOCAL INFILE 'F:/web_backup/math_operations_statements.csv' INTO TABLE tblmcquestions FIELDS TERMINATED BY ',';
LOAD DATA LOCAL INFILE 'F:/web_backup/fractions_pecimals_percentages_proportion.csv' INTO TABLE tblmcquestions FIELDS TERMINATED BY ',';	
LOAD DATA LOCAL INFILE 'F:/web_backup/shapes.csv' INTO TABLE tblmcquestions FIELDS TERMINATED BY ',';
LOAD DATA LOCAL INFILE 'F:/web_backup/measurements.csv' INTO TABLE tblmcquestions FIELDS TERMINATED BY ',';
LOAD DATA LOCAL INFILE 'F:/web_backup/time_travel_events.csv' INTO TABLE tblmcquestions FIELDS TERMINATED BY ',';
LOAD DATA LOCAL INFILE 'F:/web_backup/money_averages_proportion.csv' INTO TABLE tblmcquestions FIELDS TERMINATED BY ',';	


--Shona Load Shona Questions
LOAD DATA LOCAL INFILE 'F:/web_backup/tsumo_pedzisa.csv' INTO TABLE tblmcquestions FIELDS TERMINATED BY ',';
LOAD DATA LOCAL INFILE 'F:/web_backup/tsumo_dudzira.csv' INTO TABLE tblmcquestions FIELDS TERMINATED BY ',';
LOAD DATA LOCAL INFILE 'F:/web_backup/tsumo_dudzira2.csv' INTO TABLE tblmcquestions FIELDS TERMINATED BY ',';
LOAD DATA LOCAL INFILE 'F:/web_backup/tsumo_dzakafanana.csv' INTO TABLE tblmcquestions FIELDS TERMINATED BY ',';
LOAD DATA LOCAL INFILE 'F:/web_backup/dimikira.csv' INTO TABLE tblmcquestions FIELDS TERMINATED BY ',';
LOAD DATA LOCAL INFILE 'F:/web_backup/mazwi_akafanana.csv' INTO TABLE tblmcquestions FIELDS TERMINATED BY ',';
LOAD DATA LOCAL INFILE 'F:/web_backup/mazwi_anopikisana.csv' INTO TABLE tblmcquestions FIELDS TERMINATED BY ',';
LOAD DATA LOCAL INFILE 'F:/web_backup/magariro_hukama_netsika.csv' INTO TABLE tblmcquestions FIELDS TERMINATED BY ',';
LOAD DATA LOCAL INFILE 'F:/web_backup/magariro_zvipfuyo.csv' INTO TABLE tblmcquestions FIELDS TERMINATED BY ',';
LOAD DATA LOCAL INFILE 'F:/web_backup/magariro_midziyo_nemabasa.csv' INTO TABLE tblmcquestions FIELDS TERMINATED BY ',';
LOAD DATA LOCAL INFILE 'F:/web_backup/magariro_kudya.csv' INTO TABLE tblmcquestions FIELDS TERMINATED BY ',';
LOAD DATA LOCAL INFILE 'F:/web_backup/zvakatikomberedza.csv' INTO TABLE tblmcquestions FIELDS TERMINATED BY ',';
LOAD DATA LOCAL INFILE 'F:/web_backup/hwaro_hwemutauro.csv' INTO TABLE tblmcquestions FIELDS TERMINATED BY ',';
LOAD DATA LOCAL INFILE 'F:/web_backup/nzwisiso.csv' INTO TABLE tblmcquestions FIELDS TERMINATED BY ',';
LOAD DATA LOCAL INFILE 'F:/web_backup/nyaudzosingwi.csv' INTO TABLE tblmcquestions FIELDS TERMINATED BY ',';


--General Paper LOAD
LOAD DATA LOCAL INFILE 'F:/web_backup/food_nutrition.csv' INTO TABLE tblmcquestions FIELDS TERMINATED BY ',';
LOAD DATA LOCAL INFILE 'F:/web_backup/disease_health.csv' INTO TABLE tblmcquestions FIELDS TERMINATED BY ',';
LOAD DATA LOCAL INFILE 'F:/web_backup/energy.csv' INTO TABLE tblmcquestions FIELDS TERMINATED BY ',';
LOAD DATA LOCAL INFILE 'F:/web_backup/transport_communication.csv' INTO TABLE tblmcquestions FIELDS TERMINATED BY ',';
LOAD DATA LOCAL INFILE 'F:/web_backup/shelter_clothing.csv' INTO TABLE tblmcquestions FIELDS TERMINATED BY ',';
LOAD DATA LOCAL INFILE 'F:/web_backup/natural_resources.csv' INTO TABLE tblmcquestions FIELDS TERMINATED BY ',';
LOAD DATA LOCAL INFILE 'F:/web_backup/heritage.csv' INTO TABLE tblmcquestions FIELDS TERMINATED BY ',';
LOAD DATA LOCAL INFILE 'F:/web_backup/work_leisure.csv' INTO TABLE tblmcquestions FIELDS TERMINATED BY ',';
LOAD DATA LOCAL INFILE 'F:/web_backup/faith_religion.csv' INTO TABLE tblmcquestions FIELDS TERMINATED BY ',';
LOAD DATA LOCAL INFILE 'F:/web_backup/agric_plants_supplement.csv' INTO TABLE tblmcquestions FIELDS TERMINATED BY ',';

--Agriculture LOAD
LOAD DATA LOCAL INFILE 'F:/web_backup/weather_climate_vegetation.csv' INTO TABLE tblmcquestions FIELDS TERMINATED BY ',';
LOAD DATA LOCAL INFILE 'F:/web_backup/water.csv' INTO TABLE tblmcquestions FIELDS TERMINATED BY ',';
LOAD DATA LOCAL INFILE 'F:/web_backup/soil.csv' INTO TABLE tblmcquestions FIELDS TERMINATED BY ',';
LOAD DATA LOCAL INFILE 'F:/web_backup/agric_plants.csv' INTO TABLE tblmcquestions FIELDS TERMINATED BY ',';
LOAD DATA LOCAL INFILE 'F:/web_backup/animals.csv' INTO TABLE tblmcquestions FIELDS TERMINATED BY ',';
LOAD DATA LOCAL INFILE 'F:/web_backup/farm_tools_safety.csv' INTO TABLE tblmcquestions FIELDS TERMINATED BY ',';
LOAD DATA LOCAL INFILE 'F:/web_backup/agribusiness.csv' INTO TABLE tblmcquestions FIELDS TERMINATED BY ',';

--English Load
LOAD DATA LOCAL INFILE 'F:/web_backup/verbs_nouns.csv' INTO TABLE tblmcquestions FIELDS TERMINATED BY ',';
LOAD DATA LOCAL INFILE 'F:/web_backup/conjuctives.csv' INTO TABLE tblmcquestions FIELDS TERMINATED BY ',';
LOAD DATA LOCAL INFILE 'F:/web_backup/prepositions.csv' INTO TABLE tblmcquestions FIELDS TERMINATED BY ',';
LOAD DATA LOCAL INFILE 'F:/web_backup/opposites.csv' INTO TABLE tblmcquestions FIELDS TERMINATED BY ',';
LOAD DATA LOCAL INFILE 'F:/web_backup/synonyms.csv' INTO TABLE tblmcquestions FIELDS TERMINATED BY ',';
LOAD DATA LOCAL INFILE 'F:/web_backup/homonyms.csv' INTO TABLE tblmcquestions FIELDS TERMINATED BY ',';
LOAD DATA LOCAL INFILE 'F:/web_backup/eng_comprehension.csv' INTO TABLE tblmcquestions FIELDS TERMINATED BY ',';


--Agriculture Topics LOAD
INSERT INTO tbltopics 
VALUES 
(2,5,7,0,'WATER'),
(3,5,7,0,'SOIL'),
(4,5,7,0,'PLANTS'),
(5,5,7,0,'ANIMALS'),
(6,5,7,0,'TOOLS MACHINERY SAFETY'),
(7,5,7,0,'AGRIBUSINESS');
	
	SELECT *
	FROM tbltopics
	WHERE subject_code=3 AND grade=7
	ORDER BY topic_id; 
	
	SELECT subject_code,topic_id,COUNT(*),MAX(question_number)
	FROM tblmcquestions
	WHERE subject_code=3 AND grade=7
	GROUP BY subject_code,topic_id
	ORDER BY topic_id;

	INSERT INTO tbltopics 
	VALUES
	(1,1,7,1,'BASIC OPERATIONS',8);

LOAD DATA LOCAL INFILE 'F:/web_backup/math_operations_statements.csv' 
INTO TABLE tblmcquestions FIELDS TERMINATED BY ',';	

LOAD DATA LOCAL INFILE 'F:/web_backup/maths_supplement2.csv' 
INTO TABLE tblmcquestions FIELDS TERMINATED BY ',';	

$subject_query="SELECT * FROM tblsubjects 
				WHERE subject_code IN (SELECT subject_code 
										FROM tblmcquestions 
										HAVING COUNT(subject_code>$minimum_questions))";
--Questions per topic_id	
SELECT m.subject_code,t.topic_name,COUNT(*) 
FROM tblmcquestions m,tbltopics t 
WHERE t.subject_code=m.subject_code 
AND t.topic_id=m.topic_id
GROUP BY m.subject_code,t.topic_name;	

SELECT COUNT(*)
FROM tblmcquestions
GROUP BY subject_code;

SELECT subject_code,topic_id,COUNT(topic_id) 
FROM tblmcquestions 
GROUP BY subject_code,topic_id 
HAVING COUNT(topic_id)>0;

SELECT t.topic_name,COUNT(m.topic_id) 
FROM tblmcquestions m, tbltopics t
WHERE t.topic_id=m.topic_id AND t.subject_code=m.subject_code
AND m.subject_code=2
GROUP BY m.subject_code,t.topic_name 
HAVING COUNT(m.topic_id)>0;

$topic_query="SELECT t.topic_name AS topic_name
FROM tblmcquestions m, tbltopics t
WHERE t.topic_id=m.topic_id AND t.subject_code=m.subject_code
AND m.subject_code=".$_SESSION['subject_code'].
GROUP BY m.subject_code,t.topic_name 
HAVING COUNT(m.topic_id)>0;


SELECT t.topic_name,COUNT(s.topic_id) 
FROM tblstructuredquestions s, tbltopics t
WHERE t.topic_id=s.topic_id AND t.subject_code=s.subject_code
AND s.subject_code=3
GROUP BY s.subject_code,t.topic_name 
HAVING COUNT(s.topic_id)>0;

$topic_query="SELECT topic_name FROM tbltopics 
WHERE subject_code=".$_SESSION['subject_code']." AND grade=".$_SESSION['grade'];
--Questions per subject

SELECT s.subject_name,COUNT(*) 
FROM tblmcquestions m,tblsubjects s 
WHERE s.subject_code=m.subject_code 
GROUP BY s.subject_name;

SELECT story_id,story_title,story_content FROM tblstories WHERE subject_code=3 ORDER BY RAND() LIMIT 1

CREATE TABLE tblmctestlog
(subject_code int(5),
topic_id int(11),
question_number int(10),
question_counter int(5),
question_complete text,
choice0 varchar(100),
choice1 varchar(100),
choice2 varchar(100),
choice3 varchar(100),
instruction varchar(100),
story_id int(11),
image_id int(11),
table_based int(1),
verdict int(1),
submitted_answer varchar(100),
complete int(1),
username varchar(30),
start_date timestamp,
PRIMARY KEY(subject_code,topic_id,question_number,username,start_date));

INSERT INTO tblmctestlog 
VALUES 
(1, 1, 35, 3, 'Draw_table33('','','','',5,0,0,'','','','','+',5,0);', '550', '450', '500', '25000', '550', 'Choose the correct answer', 0, 0, 1, 1, '550', 0, 'pupil5', '2020-02-21 14:09:34')


CREATE TABLE tblflaws
(subject_code tinyint NOT NULL,
topic_id smallint NOT NULL,
question_number smallint NOT NULL,
flaw_type varchar(50),
flaw_description text,
PRIMARY KEY (subject_code,topic_id,question_number));

SELECT CONCAT(question,'.') 
FROM tblmcquestions 
WHERE NOT (question LIKE '%?' OR question LIKE '%.') AND subject_code=5;

 
UPDATE tblmcquestions 
SET question=CONCAT(question,'.')
WHERE NOT (question LIKE '%?' OR question LIKE '%.') AND subject_code=5;

LOAD DATA LOCAL INFILE 'F:/web_backup/maths_supplement.csv' INTO TABLE tblmcquestions FIELDS TERMINATED BY '~';
LOAD DATA LOCAL INFILE 'F:/web_backup/agric_supp.csv' INTO TABLE tblmcquestions FIELDS TERMINATED BY '~';

GRANT ALL PRIVILEGES ON etutor.* TO 'headteacher'@'localhost' IDENTIFIED BY 'teacher2o2o';
GRANT ALL PRIVILEGES ON etutorsrv.* TO 'headteacher'@'localhost' IDENTIFIED BY 'teacher2o2o';

LOAD DATA LOCAL INFILE 'F:/web_backup/eng_supp.csv' INTO TABLE tblmcquestions FIELDS TERMINATED BY '~';

LOAD DATA LOCAL INFILE 'F:/web_backup/assorted.csv' INTO TABLE tblmcquestions FIELDS TERMINATED BY '~';

LOAD DATA LOCAL INFILE 'F:/web_backup/ict.csv' INTO TABLE tblmcquestions FIELDS TERMINATED BY '~';

INSERT INTO tbltopics
VALUES
(1,6,1,'HARDWARE AND SAFETY'),
(2,6,2,'SOFTWARE'),
(3,6,3,'NETWORKS AND SERVICES'),
(4,6,4,'SECURITY AND OPTIMISATION');

SELECT * FROM tblmcquestions WHERE subject_code=5 AND topic_id=5 AND lower_grade<=3 AND upper_grade>=3 AND story_id=0;

SELECT * FROM tblmcquestions WHERE subject_code=5 AND topic_id=5 AND story_id=0;

LOAD DATA LOCAL INFILE 'F:/web_backup/maths_supplement3.csv' INTO TABLE tblmcquestions FIELDS TERMINATED BY '~';

SELECT subject_code,topic_id,question_number,verdict,COUNT(*) 
FROM tblmctestlog 
GROUP BY subject_code,topic_id,question_number,verdict 
ORDER BY subject_code,topic_id,question_number,verdict;

SELECT * FROM tblmcquestions 
WHERE subject_code=2 AND topic_id=2 AND lower_grade<=3 AND upper_grade>=3 AND story_id=0 
ORDER BY rand() LIMIT 1


SELECT subject_code,topic_id,username,start_time
FROM tblmctestlog 
WHERE test_type='MC' 
GROUP BY subject_code,topic_id,username,start_time 
HAVING COUNT(*)<10;


SELECT * 
FROM tblmcquestions 
WHERE subject_code=5 AND topic_id=1 AND lower_grade<=7 AND upper_grade>=7 AND story_id=0 
--ORDER BY rand() LIMIT 1

SELECT subject_code,topic_id,COUNT(*)
FROM tblmcquestions
WHERE lower_grade<=3 AND upper_grade>=3
GROUP BY subject_code,topic_id
ORDER BY subject_code,topic_id;

CREATE TABLE tblservice
(service_id int,
service_code char(15) UNIQUE NOT NULL,
service_description varchar(50) UNIQUE NOT NULL,
service_charge decimal(12,2) NOT NULL,
PRIMARY KEY (service_id));

INSERT INTO tblservice
VALUES
(1,'eC1','eCOMPANION END USER SINGLE',20.00),
(2,'eC2','eCOMPANION END USER DOUBLE',35.00),
(3,'eC3','eCOMPANION END USER TRIPPLE',45.00);

$performance_array[$record_counter][0]=$subject_code;
		$performance_array[$record_counter][1]=$topic_id;
		$performance_array[$record_counter][2]=$question_number;
		$performance_array[$record_counter][3]=$verdict;
		$performance_array[$record_counter][4]=$score_count;
		

CREATE TABLE tblsuccesslog
(subject_code int NOT NULL,
topic_id int NOT NULL,
question_number int NOT NULL,
verdict int NOT NULL,
score_count int NOT NULL,
PRIMARY KEY (subject_code,topic_id,question_number)
);

CREATE TABLE tblmctestdetail
(test_owner varchar(50) NOT NULL REFERENCES tblmembers(username),
subject_code int NOT NULL REFERENCES tblsubjects(subject_code),
topic_id int NOT NULL,
question_number int NOT NULL,
answer0 varchar(120) NOT NULL,
answer1 varchar(120) NOT NULL,
answer2 varchar(120),
answer3 varchar(120),
instruction varchar(100) NOT NULL,
test_status int(1) NOT NULL DEFAULT 0,
subject_counter int NOT NULL,
story_id int NOT NULL DEFAULT 0,
image_id int NOT NULL DEFAULT 0,
PRIMARY KEY (test_owner,subject_code,topic_id,question_number,subject_counter)
);

CREATE TABLE tblmctestsummary
(test_owner varchar(50) NOT NULL REFERENCES tblmembers(username),
subject_code int NOT NULL REFERENCES tblsubjects(subject_code),
subject_counter int,
start_date datetime NOT NULL,
posted_date datetime DEFAULT '0000-00-00 00:00:00',
test_deadline datetime DEFAULT '0000-00-00 00:00:00',
PRIMARY KEY (test_owner,subject_code,subject_counter));



