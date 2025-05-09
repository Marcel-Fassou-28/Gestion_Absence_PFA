# Conception d'une application web pour la gestion des absences

Ce projet qui vise la conception et la réalisation d'une application web pour la gestion des absences,
d'un établissement, il se situe dans le cadre de notre module de PFA (Projet de Fin d'Année)

## Introduction générale :
La gestion des absences scolaires ou universitaires est un enjeu crucial pour les 
établissements éducatifs d’enseignements préuniversitaire et universitaire, car elle impacte 
directement la réussite des élèves et l’organisation pédagogique. Un suivi efficace permet non 
seulement d’identifier rapidement les élèves en difficulté, mais aussi de mettre en place des 
mesures d’accompagnement adaptées. 
Avec l’avancement de l’informatique, et la digitalisation croissante des écoles, instituts, etc… 
il devient urgent de mettre en place des solutions idéales pour automatiser la saisie, le 
traitement et l’analyse des absences, ce qui contribue énormément à l’amélioration de la 
qualité de formation, d’apprentissage, d’enseignement et la valorisation de l’excellence 
académique. 
C’est dans ce contexte que nous avons misé sur le développement d’une solution web dédiée 
à la gestion des absences d’un établissement dans le cadre de notre projet de fin d’année 
(PFA).
L’objectif principale de cette application est de moderniser le système de gestion des absences
et de le faciliter en proposant à l’administration, aux professeurs et aux étudiants, une plateforme
intuitive, adaptée aux réalités d'un établissement. 

## Objectifs du Projet 
Les Objectifs de la réalisation de ce projet sont : 
 
### • Accès facile et rapide aux informations d’absences des étudiants,
Offrir une plateforme conviviale où les étudiants, les professeurs et l’administrations 
peuvent avoir accès à tous ce qui concerne les informations d’absences des étudiants. 
Les étudiants accèdent à leur propre information d’absences ; 
les professeurs, les informations d’absences des classes où ils 
enseignent et l’administration accède à tous.

### • Faciliter la communication entre étudiants et administration dans la justification de leur absence, 
bien que l’absence soit obligatoire, mais il existe des cas exceptionnels où les étudiants 
peuvent avoir des situations comme des cas de maladie, donc il faut leur permettre de justifier 
facilement leur absence au besoin.  

### • Tenir informer les étudiants sur leur situation d’absence et aussi les sanctions qui les concernent, 
certains étudiants n’ont pas conscience de ce qu’ils font surtout pour ceux qui sont 
nouveaux à l’école, donc un système de notification par mail, devient utile et très important 
pour ces derniers afin de leur informer de leurs situations d’absence. 

### • Faciliter la gestion des absences en réduisant les charges qui incombent ce travail, 
bien que gérer les peut paraitre anodine mais un travail qui demande beaucoup d’effort, 
donc un système qui permet de faciliter cela, devient primordial.

## Portée du Projet 
Le projet porte sur la mise en place d’une application web de gestion d’absence au sein de 
l’établissement. Le système permettra : 

• Aux professeurs d'enregistrer les absences de leurs étudiants selon leurs matières et leurs 
classes respectives. 
• Aux administrateurs de consulter, gérer et exporter en format PDF la liste des étudiants qui ont 
atteint un seuil, qui les envoie en rattrapage direct, selon les règles de l’établissement. 
• Aux étudiants de consulter leurs propres historiques d'absences par matières et de leur donner 
la possibilité de justifier leurs absences avec des justificatifs. 
• D'assurer un suivi statistique des absences par filière, par classe ou par matière. 

### Description Générale du système 
L’application de gestion des absences est une application web conçue pour faciliter le suivi et la 
gestion des absences au sein de l’établissement. Il vise à automatiser les processus liés à la déclaration, 
au suivi et à l’analyse des absences, tout en assurant une accessibilité et une transparence accrues 
pour les différents utilisateurs. 

#### Les utilisateurs principaux 

Les principaux acteurs du système sont : 
__Professeurs__ : Ils soumettent les fiches d’absence déjà remplies en classes, consultent les 
historiques des absences des étudiants de leur classe, et font l’état des absences sur le site. 

__Administrateurs__ : Ils supervisent tous le système, l’état des absences par filières, classes et 
matières, ils vérifient les justificatifs des absences, vérifie la correspondance entre les fiches 
d’absences soumis par les profs et l’état des absences effectués sur le site, gèrent les 
matières, les classes, les filières et génèrent des rapports statistiques des absences. 

__Étudiants__ : Ils consultent leurs historiques d’absences, justifie des absences via la soumission 
d’un justificatif, peuvent contacter l’administrateur. 
Le système interagit uniquement avec une base de données interne où sont stockées toutes 
les informations nécessaires (utilisateurs, matières, classes, absences, etc…).

L’application est accessible depuis un navigateur web, tant sur ordinateur que sur appareil mobile, et 
nécessite une authentification sécurisée pour accéder aux fonctionnalités. 

#### Fonctionnalités Principales 
Les fonctionnalités principales de l’application web sont : 

+ Authentification sécurisée des utilisateurs (professeurs, étudiants et administrateurs) 
avec système de réinitialisation de mot de passe. 

+ Saisie des absences par les professeurs avec possibilités de consulter l‘historique des 
absents dans sa matière. 

+ Consultation des historiques d’absences par les étudiants avec possibilités de justifier 
une absence. 

+ Génération de rapports statistiques par les administrateurs concernant les étudiants 
privées de passer l’examen de la session normale et génération de fichier PDF.

+ Chaque utilisateur possède un Dashboard approprié contenant les informations en 
temps réels des utilisateurs.

### Outils Utilisés

__PHP__ : PHP est un langage de script côté serveur, et un outil puissant pour créer des pages Web 
dynamiques et interactives. PHP est une alternative largement utilisée, gratuite et efficace face 
à des concurrents tels que l’ASP de Microsoft. 

__JAVASCRIPT, HTML, CSS__: HTML, CSS et JavaScript sont les technologies fondamentales du 
développement web. HTML structure le contenu des pages, CSS assure leur présentation 
visuelle, et JavaScript apporte l'interactivité et le dynamisme nécessaires pour créer des 
applications web modernes. 
                                                             
__BOOTSTRAP__ : est un Framework frontend open-source qui facilite et accélère le 
développement de sites web réactifs et modernes. Basé sur HTML, CSS et JavaScript, il propose 
une collection de composants prêts à l’emploi (boutons, formulaires, grilles, barres de 
navigation, etc…) permettant de construire des interfaces cohérentes, esthétiques et adaptées 
à tous types d’écrans. 

  **Pour la conception**
nous avons choisi d’utiliser ***DRAW.IO*** comme outil pour modéliser nos diagrammes UML. 
***DRAW.IO*** est un outil utilisé pour faire de la modélisation, il offre plusieurs éléments 
pour faciliter le travail des développeurs, un de ces atouts majeurs est qu’il est intégré à Visual Studio 
Code qui est un IDE.
