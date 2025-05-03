<?php

$date = new DateTime('now', new DateTimeZone('Africa/Casablanca'));
$dateSql = $date->format('Y-m-d H:i');
$title = "A Propos";
?>
<div class="presence">
    <div class="intro">
        <h1>A Propos de nous</h1>
        <div class="date-group">
            <span><?= htmlspecialchars($dateSql) ?></span>
        </div>
    </div>
    <div class="hr"></div>
    <div class="documentation">
        <div class="documentation-container">
        <section class="container how-to-scan">
            <h2>Qui sommes-nous ?</h2>
            <div class="hr"></div>
            <div class="use-info">
            <h3>GAENSAJ </h3><br>
            Nous sommes une équipe d'étudiants passionnés en ingénierie informatique, réunis autour d’un objectif commun : moderniser et simplifier la gestion des absences dans les établissements d’enseignement. Conscients des défis quotidiens que rencontrent les enseignants, les responsables pédagogiques et les étudiants, à l'aide de notre professeur encadrant <strong style="color:#8bb0f0;">Prof. Nourredine Assad</strong> nous avons conçu GAENSAJ, une application web intuitive, performante et évolutive.
            Notre projet est né d’un besoin réel observé sur le terrain : le manque d’outils centralisés, fluides et accessibles pour suivre efficacement les présences, les justifications d’absence et la communication entre les différents acteurs pédagogiques. GAENSAJ répond à cette problématique en offrant une plateforme tout-en-un qui intègre la gestion des utilisateurs (administrateurs, professeurs, étudiants), le suivi des absences en temps réel, l’association intelligente des matières aux enseignants, et bien plus encore.
            Nous combinons des technologies avancées du web avec une approche orientée utilisateur pour offrir une expérience fluide, fiable et sécurisée, aussi bien pour les petites structures que pour les établissements de grande taille.
            Chez GAENSAJ, nous croyons que la technologie peut transformer positivement l’expérience éducative, en rendant les processus administratifs plus simples, plus transparents, et plus collaboratifs.
            </div>
            
        </section>
        <section class="container how-to-check">
            <h2>Quels sont nos objectifs ?</h2>
            <div class="hr"></div>
            <div class="use-info">
            <h3>Offrir une solution numérique efficace et accessible pour optimiser la gestion des absences dans les établissements scolaires et universitaires.</h3> <br>

Pour y parvenir, nous nous fixons plusieurs objectifs spécifiques :
<dl>
<dt>🔹 Automatiser et simplifier la saisie, le suivi et la justification des absences : </dt> <dd>En réduisant la charge administrative des enseignants et du personnel éducatif.</dd>

<dt>🔹 Renforcer la communication entre les différents acteurs de l’établissement (professeurs, étudiants, administration) :</dt> <dd>Cela  à travers une plateforme centralisée. </dd>

<dt>🔹 Assurer la transparence et la traçabilité des données liées aux absences : </dt> <dd>En permettant une consultation claire et instantanée des historiques pour chaque étudiant. </dd>

<dt>🔹 Adapter l’outil aux besoins réels du terrain, en maintenant une structure modulable et évolutive : </dt> <dd>En étant apable de s’adapter aux spécificités de chaque établissement. </dd>

<dt>🔹 Encourager la responsabilité et l’autonomie des étudiants :</dt> <dd>En leur donnant un accès direct à leurs statistiques d’assiduité et à la gestion de leurs justificatifs. </dd>

<dt>🔹 Favoriser l’innovation pédagogique :</dt> <dd>En intégrant des fonctionnalités d’analyse, de reporting, et de visualisation pour aider à la prise de décision. </dd>
</dl>
À long terme, nous aspirons à faire de GAENSAJ une référence dans le domaine de la gestion numérique éducative, en continuant d’innover, d’écouter nos utilisateurs et d’améliorer l’outil selon leurs retours. </dd>

        </section>
        </div>
    </div>
</div>
