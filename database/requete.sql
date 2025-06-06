CREATE DATABASE IF NOT EXISTS gaensaj;
USE gaensaj;

-- Table Administrateur
CREATE TABLE Administrateur (
    idAdmin INT PRIMARY KEY AUTO_INCREMENT,
    cinAdmin VARCHAR(20) UNIQUE,
    nom VARCHAR(250) NOT NULL,
    prenom VARCHAR(250) NOT NULL,
    email VARCHAR(250) UNIQUE,
    CONSTRAINT fk_admin_user FOREIGN KEY (cinAdmin) REFERENCES Utilisateur(cin) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Table Utilisateur
CREATE TABLE Utilisateur (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(250) UNIQUE,
    cin VARCHAR(20) UNIQUE NOT NULL,
    nom VARCHAR(250) NOT NULL,
    prenom VARCHAR(250) NOT NULL,
    email VARCHAR(250) NOT NULL,
    password VARCHAR(250) NOT NULL,
    role ENUM('admin', 'professeur', 'etudiant') NOT NULL,
    nomPhoto VARCHAR(250) DEFAULT 'avatar.png',
    codeRecuperation VARCHAR(10),
    dateDerniereReinitialisation DATETIME,
    token VARCHAR(255)
);

-- Table des Departements
CREATE TABLE Departement (
    idDepartement INT PRIMARY KEY AUTO_INCREMENT,
    nomDepartement VARCHAR(250) NOT NULL
);


-- Table Filière
CREATE TABLE Filiere (
    idFiliere INT PRIMARY KEY AUTO_INCREMENT,
    nomFiliere VARCHAR(100) NOT NULL,
    alias VARCHAR(10) NOT NULL,
    idDepartement INT NOT NULL,
    CONSTRAINT fk_filiere_departement FOREIGN KEY (idDepartement) REFERENCES Departement(nomDepartement) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Table Niveau
CREATE TABLE Niveau (
    idNiveau INT PRIMARY KEY AUTO_INCREMENT,
    nomNiveau VARCHAR(50)
);

-- Table des Salle de Classe
CREATE TABLE Classe (
    idClasse INT PRIMARY KEY AUTO_INCREMENT,
    nomClasse VARCHAR(50),
    idNiveau INT NOT NULL,
    idFiliere INT NOT NULL,
    CONSTRAINT fk_classe_niveau FOREIGN KEY (idNiveau) REFERENCES Niveau(idNiveau) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_classe_filiere FOREIGN KEY (idFiliere) REFERENCES Filiere(idFiliere) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Table Matière
CREATE TABLE Matiere(
    idMatiere INT PRIMARY KEY AUTO_INCREMENT,
    cinProf VARCHAR(20) NOT NULL,
    nomMatiere VARCHAR(100) NOT NULL,
    idFiliere INT NOT NULL,
    idClasse INT NOT NULL,
    CONSTRAINT fk_matiere_professeur FOREIGN KEY (cinProf) REFERENCES Professeur(cinProf) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_matiere_filiere FOREIGN KEY (idFiliere) REFERENCES Filiere(idFiliere) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_matiere_classe FOREIGN KEY (idClasse) REFERENCES Classe(idClasse) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Table Créneaux
CREATE TABLE Creneaux (
    id INT PRIMARY KEY AUTO_INCREMENT,
    jourSemaine ENUM('Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'),
    heureDebut TIME NOT NULL,
    heureFin TIME NOT NULL,
    cinProf VARCHAR(20) NOT NULL,
    idMatiere INT NOT NULL,
    CONSTRAINT fk_creneaux_prof FOREIGN KEY (cinProf) REFERENCES Professeur(cinProf) ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_creneaux_matiere FOREIGN KEY (idMatiere) REFERENCES Matiere(idMatiere) ON UPDATE CASCADE ON DELETE CASCADE
);

-- Table Professeur
CREATE TABLE Professeur (
    idProf INT PRIMARY KEY AUTO_INCREMENT,
    cinProf VARCHAR(20) UNIQUE NOT NULL,
    nom VARCHAR(250) NOT NULL,
    prenom VARCHAR(250) NOT NULL,
    email VARCHAR(250) NOT NULL UNIQUE,
    CONSTRAINT fk_prof_utilisateur FOREIGN KEY (email) REFERENCES Utilisateur(email) ON UPDATE CASCADE,
    CONSTRAINT fk_admin_professeur FOREIGN KEY (cinProf) REFERENCES Utilisateur(cin) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Table Étudiant
CREATE TABLE Etudiant (
    idEtudiant INT PRIMARY KEY AUTO_INCREMENT,
    cinEtudiant VARCHAR(20) UNIQUE NOT NULL,
    nom VARCHAR(250) NOT NULL,
    prenom VARCHAR(250) NOT NULL,
    cne VARCHAR(20) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    idClasse INT NOT NULL,
    CONSTRAINT fk_etudiant_classe FOREIGN KEY (idClasse) REFERENCES Classe(idClasse) ON UPDATE CASCADE,
    CONSTRAINT fk_admin_etudiant FOREIGN KEY (cinEtudiant) REFERENCES Utilisateur(cin) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Table Absence
CREATE TABLE Absence (
    idAbsence INT PRIMARY KEY AUTO_INCREMENT,
    date DATETIME NOT NULL,
    cinEtudiant VARCHAR(50) NOT NULL,
    idMatiere INT NOT NULL,
    CONSTRAINT fk_absence_matiere FOREIGN KEY (idMatiere) REFERENCES Matiere(idMatiere) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_absence_etudiant FOREIGN KEY (cinEtudiant) REFERENCES Etudiant(cinEtudiant) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Table Justificatif
CREATE TABLE Justificatif (
    idJustificatif INT PRIMARY KEY AUTO_INCREMENT,
    dateSoumission DATE NOT NULL,
    statut ENUM('accepté', 'refusé', 'en attente') DEFAULT 'en attente',
    message TEXT NOT NULL,
    idAbsence INT NOT NULL,
    nomFichierJustificatif VARCHAR(250),
    CONSTRAINT fk_justificatif_absence FOREIGN KEY (idAbsence) REFERENCES Absence(idAbsence) ON DELETE SET NULL ON UPDATE CASCADE
);

-- Table ListePrésence
CREATE TABLE ListePresence (
    id INT PRIMARY KEY AUTO_INCREMENT,
    cinProf VARCHAR(20) NOT NULL,
    date DATETIME NOT NULL,
    classe VARCHAR(10) NOT NULL,
    nomFichierPresence VARCHAR(250) NOT NULL,
    matiere VARCHAR(100) NOT NULL,
    CONSTRAINT fk_liste_professeur FOREIGN KEY (cinProf) REFERENCES Professeur(cinProf) ON UPDATE CASCADE,
    CONSTRAINT fk_liste_classe FOREIGN KEY (classe) REFERENCES Classe(nomClasse) ON UPDATE CASCADE ON DELETE CASCADE
);

-- Table Message
-- Création de la table Message
CREATE TABLE Message (
    id INT PRIMARY KEY AUTO_INCREMENT,
    date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    objet VARCHAR(250) NOT NULL,
    contenu TEXT NOT NULL,
    cinExpediteur VARCHAR(20) NOT NULL,
    cinDestinataire VARCHAR(20) NOT NULL,
    typeDestinataire ENUM('admin', 'etudiant') NOT NULL,
    CONSTRAINT fk_message_expediteur FOREIGN KEY (cinExpediteur) 
        REFERENCES Utilisateur(cin) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_message_destinataire FOREIGN KEY (cinDestinataire) 
        REFERENCES Utilisateur(cin) ON DELETE CASCADE ON UPDATE CASCADE
);