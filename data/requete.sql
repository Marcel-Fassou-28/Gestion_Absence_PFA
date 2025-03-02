CREATE DATABASE IF NOT EXISTS gaensaj;
USE gaensaj;

-- Table for Filière
CREATE TABLE Filiere (
    code_filiere VARCHAR(10) PRIMARY KEY,
    nom VARCHAR(15) NOT NULL,
    departement VARCHAR(15) NOT NULL
);

-- Table for Professeur
CREATE TABLE Professeur (
    CIN_Prof VARCHAR(20) PRIMARY KEY, 
    Nom VARCHAR(50) NOT NULL,
    Prenom VARCHAR(50) NOT NULL,
    Password VARCHAR(255) NOT NULL,
    IdentifiantAdmin VARCHAR(50), -- Foreign key to Administration table
    FOREIGN KEY (IdentifiantAdmin) REFERENCES Administration(identifiant_Admin)
);

-- Table for Module
CREATE TABLE Module (
    code_module VARCHAR(10) PRIMARY KEY,
    intitule_module VARCHAR(100) NOT NULL,
    code_filiere VARCHAR(10) , -- Foreign key to Filiere
    CIN_Prof VARCHAR(20), -- Foreign key to Professeur
    FOREIGN KEY (code_filiere) REFERENCES Filiere(code_filiere),
    FOREIGN KEY (CIN_Prof) REFERENCES Professeur(CIN_Prof)
);

-- Table for Etudiant
CREATE TABLE Etudiant (
    CIN_Etudiant VARCHAR(20) PRIMARY KEY NOT NULL,
    Nom VARCHAR(50) NOT NULL,
    Prenom VARCHAR(50) NOT NULL,
    Password VARCHAR(255) NOT NULL,
    CNE VARCHAR(20) UNIQUE NOT NULL, -- Student National Code
    Email VARCHAR(100) NOT NULL,
    IdentifiantAdmin VARCHAR(50), -- Foreign key to Administration
    code_filiere VARCHAR(100), -- Foreign key to Filiere
    FOREIGN KEY (IdentifiantAdmin) REFERENCES Administration(identifiant_Admin),
    FOREIGN KEY (code_filiere) REFERENCES Filiere(code_filiere)
);

-- Table for Administration
CREATE TABLE Administration (
    identifiant_Admin VARCHAR(50) PRIMARY KEY,
    Nom VARCHAR(100) NOT NULL,
    Prenom VARCHAR(100) NOT NULL,
    Email VARCHAR(100) NOT NULL,
    Password VARCHAR(255) NOT NULL
);

-- Table for Absence (Absence Record)
CREATE TABLE Absence (
    id_Absence INT PRIMARY KEY AUTO_INCREMENT,
    Date DATE NOT NULL,
    Horaire TIME NOT NULL
);