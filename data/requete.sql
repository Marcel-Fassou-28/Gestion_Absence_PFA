CREATE DATABASE gaensaj;
USE gaensaj;

-- Table Administrateur
CREATE TABLE Administrateur (
    idAdmin INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(50),
    prenom VARCHAR(50),
    email VARCHAR(100) UNIQUE,
    password VARCHAR(50),
);

-- Table Utilisateur
CREATE TABLE Utilisateur (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE,
    nom VARCHAR(50) NOT NULL,
    prenom VARCHAR(50) NOT NULL,
    password VARCHAR(50) NOT NULL,
    photo LONGBLOB,
    role ENUM('admin', 'professeur', 'etudiant') NOT NULL
);

-- Table RecuperationPassword
CREATE TABLE RecuperationPassword (
    id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(100),
    date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    code VARCHAR(10),
    FOREIGN KEY (email) REFERENCES Utilisateur(email)
);

-- Table Filière
CREATE TABLE Filiere (
    idFiliere INT PRIMARY KEY AUTO_INCREMENT,
    nomFiliere VARCHAR(100),
    niveau VARCHAR(50),
    departement VARCHAR(100)
);

-- Table Matière
CREATE TABLE Matiere(
    idMatiere INT PRIMARY KEY AUTO_INCREMENT,
    idProf INT,
    nomMatiere VARCHAR(100),
    idFiliere INT,
    FOREIGN KEY (idProf) REFERENCES Professeur(idProf),
    FOREIGN KEY (idFiliere) REFERENCES Filiere(idFiliere) ON DELETE CASCADE
);

-- Table Professeur
CREATE TABLE Professeur (
    idProf INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(50),
    prenom VARCHAR(50),
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255),
);



-- Table Étudiant
CREATE TABLE Etudiant (
    idEtudiant INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(50),
    prenom VARCHAR(50),
    cne VARCHAR(20) UNIQUE,
    cin VARCHAR(20) UNIQUE,
    email VARCHAR(100) UNIQUE,
    password VARCHAR(50),
    idFiliere INT,
    FOREIGN KEY (idFiliere) REFERENCES Filiere(idFiliere) ON DELETE CASCADE
);

-- Table Absence
CREATE TABLE Absence (
    idAbsence INT PRIMARY KEY AUTO_INCREMENT,
    date DATE,
    horaire TIME,
    idEtudiant INT,
    idMatiere INT,
    FOREIGN KEY (idMatiere) REFERENCES Matiere(idMatiere),
    FOREIGN KEY (idEtudiant) REFERENCES Etudiant(idEtudiant)
);

-- Table Justificatif
CREATE TABLE Justificatif (
    idJustificatif INT PRIMARY KEY AUTO_INCREMENT,
    dateSoumission DATE,
    statut ENUM('accepté', 'refusé', 'en attente') DEFAULT 'en attente',
    message TEXT,
    idAbsence INT,
    FOREIGN KEY (idAbsence) REFERENCES Absence(idAbsence) ON DELETE CASCADE
);

-- Table ListePrésence
CREATE TABLE ListePresence (
    id INT PRIMARY KEY AUTO_INCREMENT,
    date DATE,
    niveau VARCHAR(50),
    idFiliere INT,
    imageJustificatif LONGBLOB,
    FOREIGN KEY (idFiliere) REFERENCES Filiere(idFiliere)
);