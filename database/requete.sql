CREATE DATABASE IF NOT EXISTS gaensaj;
USE gaensaj;

-- Table Administrateur
CREATE TABLE Administrateur (
    idAdmin INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(250) NOT NULL,
    prenom VARCHAR(250) NOT NULL,
    email VARCHAR(250) UNIQUE,
    password VARCHAR(250) NOT NULL,
    cin VARCHAR(20) UNIQUE NOT NULL,
    CONSTRAINT fk_admin_user FOREIGN KEY (idAdmin) REFERENCES Utilisateur(id) ON DELETE CASCADE
);

-- Table Utilisateur
CREATE TABLE Utilisateur (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(250) UNIQUE,
    nom VARCHAR(250) NOT NULL,
    prenom VARCHAR(250) NOT NULL,
    email VARCHAR(250) NOT NULL,
    password VARCHAR(250) NOT NULL,
    cin VARCHAR(20) UNIQUE NOT NULL,
    role ENUM('admin', 'professeur', 'etudiant') NOT NULL,
    photo MEDIUMBLOB -- à modifier 
);

-- Table RecuperationPassword
CREATE TABLE RecuperationPassword (
    id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(100) NOT NULL,
    dateReset TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    validationCode VARCHAR(10) NOT NULL,
    CONSTRAINT fk_recuperation_user FOREIGN KEY (email) REFERENCES Utilisateur(email)
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
    idProf INT NOT NULL,
    nomMatiere VARCHAR(100) NOT NULL,
    idFiliere INT NOT NULL,
    CONSTRAINT fk_matiere_professeur FOREIGN KEY (idProf) REFERENCES Professeur(idProf) ON DELETE CASCADE,
    CONSTRAINT fk_matiere_filiere FOREIGN KEY (idFiliere) REFERENCES Filiere(idFiliere) ON DELETE CASCADE
);

-- Table Professeur
CREATE TABLE Professeur (
    idProf INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(250) NOT NULL,
    prenom VARCHAR(250) NOT NULL,
    email VARCHAR(250) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL NOT NULL,
    cin VARCHAR(20) UNIQUE NOT NULL,
    CONSTRAINT fk_admin_professeur FOREIGN KEY (idProf) REFERENCES Utilisateur(id) ON DELETE CASCADE
);



-- Table Étudiant
CREATE TABLE Etudiant (
    idEtudiant INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(250) NOT NULL,
    prenom VARCHAR(250) NOT NULL,
    cne VARCHAR(20) UNIQUE NOT NULL,
    cin VARCHAR(20) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(250) NOT NULL,
    idFiliere INT NOT NULL,
    CONSTRAINT fk_etudiant_filiere FOREIGN KEY (idFiliere) REFERENCES Filiere(idFiliere) ON DELETE CASCADE,
    CONSTRAINT fk_admin_etudiant FOREIGN KEY (idEtudiant) REFERENCES Utilisateur(id) ON DELETE CASCADE
);

-- Table Absence
CREATE TABLE Absence (
    idAbsence INT PRIMARY KEY AUTO_INCREMENT,
    date DATE NOT NULL,
    horaire TIME NOT NULL,
    idEtudiant INT NOT NULL,
    idMatiere INT NOT NULL,
    CONSTRAINT fk_absence_matiere FOREIGN KEY (idMatiere) REFERENCES Matiere(idMatiere),
    CONSTRAINT fk_absence_etudiant FOREIGN KEY (idEtudiant) REFERENCES Etudiant(idEtudiant)
);

-- Table Justificatif
CREATE TABLE Justificatif (
    idJustificatif INT PRIMARY KEY AUTO_INCREMENT,
    dateSoumission DATE NOT NULL,
    statut ENUM('accepté', 'refusé', 'en attente') DEFAULT 'en attente',
    message TEXT NOT NULL,
    idAbsence INT NOT NULL,
    CONSTRAINT fk_justificatif_absence FOREIGN KEY (idAbsence) REFERENCES Absence(idAbsence) ON DELETE CASCADE
);

-- Table ListePrésence
CREATE TABLE ListePresence (
    id INT PRIMARY KEY AUTO_INCREMENT,
    date DATE NOT NULL,
    niveau VARCHAR(50) NOT NULL,
    idFiliere INT NOT NULL,
    imageJustificatif LONGBLOB,
    CONSTRAINT fk_liste_filiere FOREIGN KEY (idFiliere) REFERENCES Filiere(idFiliere)
);