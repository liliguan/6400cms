CREATE DATABASE IF NOT EXISTS ERMS;
USE ERMS;
DROP TABLE IF EXISTS User;
CREATE TABLE `User` (
  `Username` VARCHAR(45) NOT NULL,
  `Name` VARCHAR(45) NOT NULL,
  `Password` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`Username`));

DROP TABLE IF EXISTS Individual;
CREATE TABLE `Individual` (
 	`Username` VARCHAR(45) NOT NULL,
  `JobTitle` VARCHAR(45) NOT NULL,
  `HiredDate` DATE NOT NULL,
  PRIMARY KEY (`Username`),
	FOREIGN KEY (`Username`) REFERENCES User(Username));

DROP TABLE IF EXISTS Municipality;
CREATE TABLE `Municipality` (
 	`Username` VARCHAR(45) NOT NULL,
 	`PopulationSize` INT UNSIGNED NOT NULL,
 	PRIMARY KEY (`Username`),
 	FOREIGN KEY (`Username`) REFERENCES User(Username));

DROP TABLE IF EXISTS Company;
CREATE TABLE `Company` (
 	`Username` VARCHAR(45) NOT NULL,
  `LocationOfHeadquarter` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`Username`),
  FOREIGN KEY (`Username`) REFERENCES User(Username));

DROP TABLE IF EXISTS GovernmentAgency;
CREATE TABLE `GovernmentAgency` (
 	`Username` VARCHAR(45) NOT NULL,
 	`Jurisdiction` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`Username`),
  FOREIGN KEY (`Username`) REFERENCES User(Username));

DROP TABLE IF EXISTS Incident;
CREATE TABLE `Incident` (
 	`IncidentID` INT UNSIGNED NOT NULL AUTO_INCREMENT,
 	`IncidentDate` DATE NOT NULL,
 	`IncidentDescription` VARCHAR(250) NOT NULL,
 	`IncidentLongitude` DECIMAL(11,8) NOT NULL,
 	`IncidentLatitude` DECIMAL(10,8) NOT NULL,
  `OwnerUsername` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`IncidentID`),
  FOREIGN KEY (`OwnerUsername`) REFERENCES User(Username));
  	
DROP TABLE IF EXISTS Cost;
CREATE TABLE `Cost` (
  `TypeName` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`TypeName`));

INSERT INTO Cost VALUES ('Hour'),('Day'),('Week'),('Each');

DROP TABLE IF EXISTS ESF;
CREATE TABLE `ESF` (
  `ESFNumber` INT UNSIGNED NOT NULL,
  `ESFDescription` VARCHAR(250) NOT NULL,
  PRIMARY KEY (`ESFNumber`));

INSERT INTO ESF (ESFNumber,ESFDescription) VALUES
(1,'Transportation'),
(2,'Communications'),
(3,'Public Works and Engineering'),
(4,'Firefighting'),
(5,'Emergency Management'),
(6,'Mass Care, Emergency Assistance, Housing, and Human Services'),
(7,'Logistics Management and Resource Support'),
(8,'Public Health and Medical Services'),
(9,'Search and Rescue'),
(10,'Oil and Hazardous Materials Response'),
(11,'Agriculture and Natural Resources'),
(12,'Energy'),
(13,'Public Safety and Security'),
(14,'Long-Term Community Recovery'),
(15,'External Affairs');


DROP TABLE IF EXISTS Resource;
CREATE TABLE `Resource` (
  `ResourceID` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `ResourceName` VARCHAR(45) NOT NULL,
  `CostType` VARCHAR(45) NOT NULL,
  `PrimaryESFNumber` INT UNSIGNED NOT NULL,
  `CostValue` INT UNSIGNED NOT NULL,
  `Description` VARCHAR(250) NULL,
  `ResourceLatitude` DECIMAL(10,8) NOT NULL,
  `ResourceLongitude` DECIMAL(11,8) NOT NULL,
  `NextAvailableDate` DATE NOT NULL,
  `ResourceStatus` VARCHAR(45) NOT NULL DEFAULT 'Available',
  `ModelName` VARCHAR(45) NULL,
  `ResourceOwner` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`ResourceID`),
  FOREIGN KEY (`ResourceOwner`) REFERENCES User(Username),
  FOREIGN KEY (`PrimaryESFNumber`) REFERENCES ESF(ESFNumber),
  FOREIGN KEY (`CostType`) REFERENCES Cost(TypeName));



DROP TABLE IF EXISTS ResourceCapability;
CREATE TABLE `ResourceCapability` (
  	`ResourceID` INT UNSIGNED NOT NULL,
  	`Capability` VARCHAR(45) NOT NULL,
  	PRIMARY KEY (`ResourceID`, `Capability`),
  	FOREIGN KEY (`ResourceID`) REFERENCES Resource(ResourceID));


DROP TABLE IF EXISTS SecondaryESF;
CREATE TABLE `SecondaryESF` (
  	`ResourceID` INT UNSIGNED NOT NULL,
  	`ESFNumber` INT UNSIGNED NOT NULL,
  	PRIMARY KEY (`ResourceID`, `ESFNumber`),
  	FOREIGN KEY (`ResourceID`) REFERENCES Resource(ResourceID),
  	FOREIGN KEY (`ESFNumber`) REFERENCES ESF(ESFNumber));


DROP TABLE IF EXISTS Repair;
CREATE TABLE `Repair` (
  	`ResourceID` INT UNSIGNED NOT NULL,
  	`RepairStartDate` DATETIME NOT NULL,
  	`LastingDays` INT UNSIGNED NOT NULL,
    `RepairSatuts` VARCHAR(45) NOT NULL DEFAULT 'Scheduled',
  	PRIMARY KEY (`ResourceID`, `RepairStartDate`),
  	FOREIGN KEY (`ResourceID`) REFERENCES Resource(ResourceID));

DROP TABLE IF EXISTS Request;
CREATE TABLE `Request` (
  	`ResourceID` INT UNSIGNED NOT NULL,
  	`IncidentID` INT UNSIGNED NOT NULL,
  	`ExpectedReturnDate` DATETIME NOT NULL,
  	`Status` VARCHAR(45) NOT NULL DEFAULT 'Waiting',
  	`UseStartDate` DATETIME NULL,
  	PRIMARY KEY (`ResourceID`, `IncidentID`),
  	FOREIGN KEY (`ResourceID`) REFERENCES Resource(ResourceID),
  	FOREIGN KEY (`IncidentID`) REFERENCES Incident(IncidentID));


INSERT INTO User (Username,Name,Password) VALUES ('ywang','Yue Wang','ywang123');
INSERT INTO Individual (Username,JobTitle,HiredDate) VALUES('ywang','Data Analyst','2015-01-01');
INSERT INTO User (Username,Name,Password) VALUES ('ychai','Yixiao Chai','ychai123');
INSERT INTO Individual (Username,JobTitle,HiredDate) VALUES('ychai','IOS Developer','2014-01-01');


INSERT INTO User (Username,Name,Password) VALUES ('atlanta','City of Atlanta','atlanta123');
INSERT INTO Municipality(Username,PopulationSize) VALUES('atlanta',300154);
INSERT INTO User (Username,Name,Password) VALUES ('hoboken','City of Hoboken','hoboken123');
INSERT INTO Municipality(Username,PopulationSize) VALUES('hoboken',50005);

INSERT INTO User (Username,Name,Password) VALUES ('google','Google Inc.','google123');
INSERT INTO Company (Username,LocationOfHeadquarter) VALUES ('google','Mountain View CA');
INSERT INTO User (Username,Name,Password) VALUES ('twitter','Twitter Inc.','twitter123');
INSERT INTO Company (Username,LocationOfHeadquarter) VALUES ('twitter','San Francisco CA');

INSERT INTO User (Username,Name,Password) VALUES ('humc','Hoboken University Medical Center','humc123');
INSERT INTO GovernmentAgency (Username,Jurisdiction) VALUES ('humc','Local');
INSERT INTO User (Username,Name,Password) VALUES ('hudsonfd','Hudson County Fire Departments','hudsonfd123');
INSERT INTO GovernmentAgency (Username,Jurisdiction) VALUES ('hudsonfd','County');
INSERT INTO User (Username,Name,Password) VALUES ('fcc','Federal Communications Commission','fcc123');
INSERT INTO GovernmentAgency (Username,Jurisdiction) VALUES ('fcc','Federal');
INSERT INTO User (Username,Name,Password) VALUES ('nrcc','NJ Response Coordination Center','nrcc123');
INSERT INTO GovernmentAgency (Username,Jurisdiction) VALUES ('nrcc','State');
INSERT INTO User (Username,Name,Password) VALUES ('njdd','NJ Department of Defense','njdd123');
INSERT INTO GovernmentAgency (Username,Jurisdiction) VALUES ('njdd','State');


INSERT INTO Resource (ResourceName,CostType,PrimaryESFNumber,CostValue,Description,
  ResourceLatitude,ResourceLongitude,ModelName,ResourceOwner,NextAvailableDate)
VALUES ('2015 Hummer','Each',1,25000,'Hummer for Transportation',40.744741,-74.024972,'Hummer','ywang','2017-01-01');

INSERT INTO Resource (ResourceName,CostType,PrimaryESFNumber,CostValue,Description,
  ResourceLatitude,ResourceLongitude,ModelName,ResourceOwner,NextAvailableDate)
VALUES ('Radio communications systems','Day',2,4000,'Support firefighters, law enforcement officers, and incident response operations',
  40.748084,-73.978130,'Radio communications systems','fcc',CURDATE());

INSERT INTO Resource (ResourceName,CostType,PrimaryESFNumber,CostValue,Description,
  ResourceLatitude,ResourceLongitude,ModelName,ResourceOwner,NextAvailableDate)
VALUES ('roadblock','Each',3,200,'temporary protection of roads',40.740192,-74.037702,'Road','hudsonfd',CURDATE());

INSERT INTO Resource (ResourceName,CostType,PrimaryESFNumber,CostValue,Description,
  ResourceLatitude,ResourceLongitude,ModelName,ResourceOwner,NextAvailableDate)
VALUES ('fire truck','Each',4,100000,'assist in fire',40.740192,-74.037702,'Fire','hudsonfd','2016-12-25');

INSERT INTO Resource (ResourceName,CostType,PrimaryESFNumber,CostValue,Description,
  ResourceLatitude,ResourceLongitude,ModelName,ResourceOwner,NextAvailableDate)
VALUES ('experienced staff','Day',5,5000,'assist in emergency',40.812070,-74.996084,'emergency','nrcc',CURDATE());

INSERT INTO Resource (ResourceName,CostType,PrimaryESFNumber,CostValue,Description,
  ResourceLatitude,ResourceLongitude,ModelName,ResourceOwner,NextAvailableDate)
VALUES ('shelter','Each',6,50000,'shelter in CA',33.995423,-118.476451,'shelter','google',CURDATE());

INSERT INTO Resource (ResourceName,CostType,PrimaryESFNumber,CostValue,Description,
  ResourceLatitude,ResourceLongitude,ModelName,ResourceOwner,NextAvailableDate)
VALUES ('Office Equipment','Each',7,4000,'Office Equipment',34.007782,-118.489858,'Office Equipment','twitter',CURDATE());

INSERT INTO Resource (ResourceName,CostType,PrimaryESFNumber,CostValue,Description,
  ResourceLatitude,ResourceLongitude,ModelName,ResourceOwner,NextAvailableDate)
VALUES ('Ambulance','Each',8,50000,'Hospital Ambulance',40.741133,-74.033908,'Hospital Equipment','humc',CURDATE());

INSERT INTO Resource (ResourceName,CostType,PrimaryESFNumber,CostValue,Description,
  ResourceLatitude,ResourceLongitude,ModelName,ResourceOwner,NextAvailableDate)
VALUES ('Helicopter','Each',9,1000000,'Helicopter for Search',33.747082,-84.424048,'Helicopter for Rescure','atlanta',CURDATE());

INSERT INTO Resource (ResourceName,CostType,PrimaryESFNumber,CostValue,Description,
  ResourceLatitude,ResourceLongitude,ModelName,ResourceOwner,NextAvailableDate)
VALUES ('Cleaning Device','Each',10,50000,'Cleaning Device for Contamination',33.747082,-84.424048,'Contamination Cleaning','atlanta',CURDATE());

INSERT INTO Resource (ResourceName,CostType,PrimaryESFNumber,CostValue,Description,
  ResourceLatitude,ResourceLongitude,ModelName,ResourceOwner,NextAvailableDate)
VALUES ('Veterinary Personnel','Week',11,5000,'Veterinary Personnel for Animal Disease',40.744700,-74.025051,'Animal Disease','hoboken',CURDATE());

INSERT INTO Resource (ResourceName,CostType,PrimaryESFNumber,CostValue,Description,
  ResourceLatitude,ResourceLongitude,ModelName,ResourceOwner,NextAvailableDate)
VALUES ('Engineering Support','Week',12,8000,'Engineering Support for Energy-related Infrastructure',40.744700,-74.025051,
  'Energy-related Infrastructure','hoboken',CURDATE());

INSERT INTO Resource (ResourceName,CostType,PrimaryESFNumber,CostValue,Description,
  ResourceLatitude,ResourceLongitude,ModelName,ResourceOwner,NextAvailableDate)
VALUES ('Body Armor','Each',13,3000,'Body Armor for Defense',40.729122,-74.162486,'Defense','njdd',CURDATE());

INSERT INTO Resource (ResourceName,CostType,PrimaryESFNumber,CostValue,Description,
  ResourceLatitude,ResourceLongitude,ModelName,ResourceOwner,NextAvailableDate)
VALUES ('Job Training','Day',14,6000,'Job Training for Community Recovery',40.744700,-74.025051,'Job Training','hoboken',CURDATE());

INSERT INTO Resource (ResourceName,CostType,PrimaryESFNumber,CostValue,Description,
  ResourceLatitude,ResourceLongitude,ModelName,ResourceOwner,NextAvailableDate)
VALUES ('Guest House','Day',15,10000,'Guest House for foreigners',40.991878,-73.981402,'External Affairs','nrcc',CURDATE());



INSERT INTO Incident (IncidentDescription,IncidentLongitude,IncidentLatitude,OwnerUsername,IncidentDate) VALUES 
('Request firefighters',39.936268,-75.165947,'njdd','2016-06-30');







