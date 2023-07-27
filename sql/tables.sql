DROP TABLE IF EXISTS world_records;

DROP TABLE IF EXISTS marks;

DROP TABLE IF EXISTS inscriptions;

DROP TABLE IF EXISTS swimmers;

DROP TABLE IF EXISTS races;

DROP TABLE IF EXISTS options;

DROP TABLE IF EXISTS questions;

DROP TABLE IF EXISTS events;

DROP TABLE IF EXISTS sessions;

DROP TABLE IF EXISTS journeys;

DROP TABLE IF EXISTS competitions;
 
CREATE TABLE swimmers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(60) NOT NULL,
    surname VARCHAR(60) NOT NULL,
    gender VARCHAR(10) NOT NULL,
    birthYear YEAR NOT NULL,
    picture VARCHAR(256) DEFAULT "./public/img/no-picture.svg",
    licence VARCHAR(12) UNIQUE,
    email VARCHAR(60) UNIQUE NOT NULL,
    password VARCHAR(256) NOT NULL,
    isAdmin BOOLEAN NOT NULL DEFAULT FALSE,
    resetPassToken VARCHAR(256) UNIQUE,
    tokenExpDate DATETIME,
    forceNewPass BOOLEAN NOT NULL DEFAULT TRUE
);

-- Insert admin default user with 1234 password
INSERT INTO swimmers (name,surname,gender,birthYear,email,password,isAdmin)
VALUES ('Admin','Súper','male','1981','admin@admin.com','$2y$10$D1mtoUjzftq3/5UVIBLOJegbAtyq9M8gZury0G4uZQLqkMdcoSU6y',1);

CREATE TABLE marks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    swimmerId INT NOT NULL,
    style VARCHAR(12) NOT NULL, 
    distance VARCHAR(5) NOT NULL, 
    pool VARCHAR(3) NOT NULL, 
    gender VARCHAR(6) NOT NULL, 
    category VARCHAR(4) NOT NULL,
    time TIME(2) NOT NULL,
    CONSTRAINT FK_SwimmerId FOREIGN KEY (swimmerid)
    REFERENCES swimmers(id)
    ON UPDATE CASCADE
    ON DELETE CASCADE
);

CREATE TABLE world_records (
    style VARCHAR(12) NOT NULL, 
    distance VARCHAR(8) NOT NULL, 
    pool VARCHAR(3) NOT NULL, 
    gender VARCHAR(6) NOT NULL, 
    time TIME(2) NOT NULL,
    category VARCHAR(4) NOT NULL,
    PRIMARY KEY (style,distance,pool,gender,category)
);


CREATE TABLE competitions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(60) NOT NULL,
    place VARCHAR(60) NOT NULL,
    location VARCHAR(256),
    description LONGTEXT,
    picture VARCHAR(256) DEFAULT "./public/img/no-picture.svg",
    startDate DATE NOT NULL,
    endDate DATE NOT NULL,
    deadLine DATETIME NOT NULL,
    inscriptionsLimit INT(2),
    state VARCHAR(20) NOT NULL DEFAULT "closed"
);

CREATE TABLE journeys (
    id INT AUTO_INCREMENT PRIMARY KEY,
    competitionId INT NOT NULL,
    name VARCHAR(60) NOT NULL,
    date DATE NOT NULL,
    inscriptionsLimit INT(2),
    CONSTRAINT FK_CompetitionId FOREIGN KEY (competitionId)
    REFERENCES competitions(id)
    ON UPDATE CASCADE
    ON DELETE CASCADE
);

CREATE TABLE sessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    journeyId INT NOT NULL,
    name VARCHAR(60) NOT NULL,
    time TIME NOT NULL,
    inscriptionsLimit INT(2),
    CONSTRAINT FK_JourneyId FOREIGN KEY (journeyId)
    REFERENCES journeys(id)
    ON UPDATE CASCADE
    ON DELETE CASCADE
);

CREATE TABLE races (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sessionId INT NOT NULL,
    number INT,
    style VARCHAR(12) NOT NULL, 
    distance VARCHAR(10) NOT NULL, 
    gender VARCHAR(10) NOT NULL,
    isRelay BOOLEAN NOT NULL DEFAULT FALSE,
    CONSTRAINT FK_Races_SessionId FOREIGN KEY (sessionId)
    REFERENCES sessions(id)
    ON UPDATE CASCADE
    ON DELETE CASCADE
);

CREATE TABLE events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    parentId INT,
    name VARCHAR(60) NOT NULL,
    place VARCHAR(60),
    location VARCHAR(256),
    description LONGTEXT,
    picture VARCHAR(256) DEFAULT "./public/img/no-picture.svg",
    startDate DATE,
    endDate DATE,
    deadLine DATETIME,
    state VARCHAR(20),
    CONSTRAINT FK_parentId FOREIGN KEY (parentId)
    REFERENCES events(id)
    ON UPDATE CASCADE
    ON DELETE CASCADE
);

CREATE TABLE questionaries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(256) NOT NULL,
    description LONGTEXT,
    picture VARCHAR(256) DEFAULT "./public/img/no-picture.svg",
    deadLine DATETIME,
    state VARCHAR(20) NOT NULL DEFAULT "closed"
);

CREATE TABLE questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    eventId INT,
    questionaryId INT,
    text LONGTEXT NOT NULL,
    type VARCHAR(10),
    number INT,
    CONSTRAINT FK_Questions_EventId FOREIGN KEY (eventId)
    REFERENCES events(id)
    ON UPDATE CASCADE
    ON DELETE CASCADE,
    CONSTRAINT FK_Questions_QuetionaryId FOREIGN KEY (questionaryId)
    REFERENCES questionaries(id)
    ON UPDATE CASCADE
    ON DELETE CASCADE
);

CREATE TABLE options (
    id INT AUTO_INCREMENT PRIMARY KEY,
    questionId INT NOT NULL,
    text LONGTEXT NOT NULL,
    number INT,
    CONSTRAINT FK_Options_QuestionId FOREIGN KEY (questionId)
    REFERENCES questions(id)
    ON UPDATE CASCADE
    ON DELETE CASCADE
);

CREATE TABLE answers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    questionaryId INT,
    topEventId INT,
    swimmerId INT NOT NULL,
    questionId INT NOT NULL,
    text LONGTEXT NOT NULL,
    CONSTRAINT FK_Answers_QuestionaryId FOREIGN KEY (questionId)
    REFERENCES questions(id)
    ON UPDATE CASCADE
    ON DELETE CASCADE,
    CONSTRAINT FK_Inscriptions_EventId FOREIGN KEY (topEventId)
    REFERENCES inscriptions(eventId)
    ON UPDATE CASCADE
    ON DELETE CASCADE,
    CONSTRAINT FK_Answers_EventId FOREIGN KEY (topEventId)
    REFERENCES events(id)
    ON UPDATE CASCADE
    ON DELETE CASCADE,
    CONSTRAINT FK_Answers_SwimmerId FOREIGN KEY (swimmerId)
    REFERENCES swimmers(id)
    ON UPDATE CASCADE
    ON DELETE CASCADE,
    CONSTRAINT FK_Answers_QuestionId FOREIGN KEY (questionId)
    REFERENCES questions(id)
    ON UPDATE CASCADE
    ON DELETE CASCADE
);

CREATE TABLE inscriptions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    swimmerId INT NOT NULL,
    eventId INT,
    competitionId INT,
    raceId INT,
    mark TIME(2),
    CONSTRAINT FK_Inscripcions_SwimmerId FOREIGN KEY (swimmerId)
    REFERENCES swimmers(id)
    ON UPDATE CASCADE
    ON DELETE CASCADE,
    CONSTRAINT FK_Inscripcions_EventId FOREIGN KEY (eventId)
    REFERENCES events(id)
    ON UPDATE CASCADE
    ON DELETE CASCADE,
    CONSTRAINT FK_Inscripcions_CompetitionId FOREIGN KEY (competitionId)
    REFERENCES competitions(id)
    ON UPDATE CASCADE
    ON DELETE CASCADE,
    CONSTRAINT FK_Inscriptions_RaceId FOREIGN KEY (raceId)
    REFERENCES races(id)
    ON UPDATE CASCADE
    ON DELETE CASCADE
);

ALTER TABLE inscriptions
  ADD CONSTRAINT uq_inscriptions_raceId UNIQUE(swimmerId, raceId),
   ADD CONSTRAINT uq_inscriptions_eventId UNIQUE(swimmerId, eventId);