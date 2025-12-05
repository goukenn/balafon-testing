<?php

// unicode_symbol_on_jobs_description.php

// p - info 
// @command: balafon --run .test/project/foremjobs/scripts/unicode_symbol_on_jobs_description.php

use com\igkdev\projects\ForemJobDashboard\Models\Jobs;

$jobs = Jobs::GetCache('id', 1469);
$desc = <<<EOF
Vous maîtrisez JavaScript et vous adorez concevoir des applications SIG performantes, intuitives et utilisables même offline ?

Rejoignez Gate-16 et participez à la création de solutions web cartographiques innovantes et résilientes.


🎯 Votre mission
Vous développez et optimisez des applications web SIG répondant à des exigences de haute disponibilité et de sécurité :

    Développement d'applications web et mobiles avec support offline complet, permettant la consultation, l'édition et la synchronisation différées des données en contexte déconnecté

    Priorisez les flux critiques et automatisez la résolution d'incidents lors du retour en ligne

    Intégrez les données temps réel : configuration de flux issus de capteurs et autres protocoles, avec automatisation de leur ingestion et visualisation en temps réel

    Intervenez dans les sessions de formations (utilisateur ou administrateur) avec focus sur la résilience opérationnelle


Ce que vous apportez

    Connaissance approfondie des contraintes spécifiques aux systèmes critiques : exigences de haute disponibilité, sécurité, fonctionnement hors ligne, continuité de services

    Maîtrise des technologies JavaScript (ArcGIS API for JS, Web AppBuilder, Experience Builder), HTML/CSS, et Python (arcpy, Flask)

    Capacité à développer des applications cartographiques interactives avec fonctionnement offline

    Connaissance des bonnes pratiques DevOps : CI/CD, versioning Git, tests unitaires

    Certification Esri Web Application Developer Specialty souhaitée


Vous êtes passionné(e) par le développement web et souhaitez créer des applications cartographiques innovantes, intuitives et résilientes ?

Faites partie de l’aventure Gate-16. Postulez dès maintenant !
EOF;

// $ad = ForemJobDashboardController::ctrl(true)->getDataAdapter();
// $ad->set_charset('utf8mb4');


$jobs->description = $desc;

$jobs->update();

igk_exit();