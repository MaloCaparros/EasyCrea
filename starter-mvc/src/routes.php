<?php

declare(strict_types=1);
/*
-------------------------------------------------------------------------------
les routes
-------------------------------------------------------------------------------
 */

return [

    // accueil et affichage pour les avatars
    ['GET', '/', 'index@index'], // page d'accueil

    ['GET', '/Auth/connection', 'index@connection'],
    ['POST', '/Auth/connection', 'index@connection'],
    
    ['GET', '/indexadmin', 'admin@connection'],
    ['GET', '/adminaccueil', 'admin@verification'],

    ['GET', '/adminaccueilverif', 'admin@index'],
    ['POST', '/Auth/connectionadmin', 'admin@connection'],

    ['GET', '/Auth/inscription', 'index@inscription'],
    ['POST', '/Auth/inscription', 'index@inscription'],

    ['GET', '/admincreadeck', 'deck@index'],
    ['POST', '/creadeck', 'deck@creation'],

    ['GET', '/accueil', 'accueil@accueil'],
    ['GET', '/avantaccueil', 'accueil@ajoutaleatoire'],
    ['POST', '/creacarte','accueil@creation' ],

];
