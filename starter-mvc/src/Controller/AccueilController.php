<?php

declare(strict_types=1); // strict mode

namespace App\Controller;

use App\Helper\HTTP;
use App\Model\Carte;
use App\Model\CarteAleatoire;
use App\Model\Deck;


class AccueilController extends Controller
{


    public function accueil()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start(); // Démarrer la session si nécessaire
        }
        if (isset($_SESSION['admin_password'])) {
            unset($_SESSION['admin_password']);
        }
        if (isset($_SESSION['admin'])) {
            unset($_SESSION['admin']);
        }

        if (empty($_SESSION['createur_id']) && empty($_SESSION['admin_id'])) {
            HTTP::redirect('/');
        } else {
            $decks = Deck::getInstance()->findAll();
            $date_actuelle = new \DateTime();
            // Tableau pour stocker les decks en cours
            $decks_en_cours = [];



            foreach ($decks as $deck) {
                $date_fin = new \DateTime($deck['date_fin_deck']);

                if ($date_fin > $date_actuelle) {
                    $decks_en_cours[] = $deck;
                }
            }

            if (empty($decks_en_cours)) {
                $carte_alea = [];
                $cartes = [];
                $verifie = false;
                $vide = false;
                $this->display('accueil.html.twig', ['decks' => $decks_en_cours, 'cartes' => $cartes, 'carteAleatoire' => $carte_alea, 'verifie' => $verifie, 'vide' => $vide]);
            } else {

                $carte_alea = CarteAleatoire::getInstance()->findOneBy(['id_deck' => $decks_en_cours[0]['id_deck']]);

                $cartes = Carte::getInstance()->findAllBy(['id_deck' => $decks_en_cours[0]['id_deck']]);

                if (empty($cartes) && empty($_SESSION['admin_id'])) {
                    $carte_alea = [];
                    $cartes = [];
                    $verifie = false;
                    $vide = false;
                    $this->display('accueil.html.twig', ['decks' => $decks_en_cours, 'cartes' => $cartes, 'carteAleatoire' => $carte_alea, 'verifie' => $verifie, 'vide' => $vide]);
                } else {



                    $ordreMax = 0;
                    // Parcourir les cartes pour trouver celle avec l'ordre de soumission le plus grand
                    foreach ($cartes as $carte) {
                        if ($carte['ordre_soumission'] > $ordreMax) {
                            $ordreMax = $carte['ordre_soumission'];
                        }
                    }

                    if ($ordreMax > $decks_en_cours[0]['nb_cartes']) {
                        $carte_alea = [];
                        $cartes = [];
                        $verifie = false;
                        $vide = false;
                        $this->display('accueil.html.twig', ['decks' => $decks_en_cours, 'cartes' => $cartes, 'carteAleatoire' => $carte_alea, 'verifie' => $verifie, 'vide' => $vide]);
                    } else {

                        $vide = true;
                        $verifie = true;

                        if (!empty($_SESSION['createur_id'])) {
                            foreach ($cartes as $carte) {
                                if ($carte['id_createur'] == $_SESSION['createur_id']) {

                                    $verifie = false;
                                }
                            }
                        }
                        if (!empty($_SESSION['admin_id'])) {
                            foreach ($cartes as $carte) {
                                if ($carte['id_administrateur'] == $_SESSION['admin_id']) {

                                    $verifie = false;
                                }
                            }
                        }

                        $this->display('accueil.html.twig', ['decks' => $decks_en_cours, 'cartes' => $cartes, 'carteAleatoire' => $carte_alea, 'verifie' => $verifie, 'vide' => $vide]);
                    }
                }
            }
        }
    }

    public function creation()
    {
        if ($this->isGetMethod()) {
            $this->display('/Auth/inscription.html.twig');
        } else {
            if (session_status() == PHP_SESSION_NONE) {
                session_start(); // Démarrer la session si nécessaire
            }
            if (!empty($_SESSION['admin_id'])) {
                $id_admin = $_SESSION['admin_id'];
            }
            if (!empty($_SESSION['createur_id'])) {
                $id_crea = $_SESSION['createur_id'];
            }

            $decks = Deck::getInstance()->findAll();
            $date_actuelle = new \DateTime();
            // Tableau pour stocker les decks en cours
            $decks_en_cours = [];



            foreach ($decks as $deck) {
                $date_fin = new \DateTime($deck['date_fin_deck']);

                if ($date_fin > $date_actuelle) {
                    $decks_en_cours[] = $deck;
                }
            }
            $id_deck = $decks_en_cours[0]['id_deck'];
            $text_carte = htmlspecialchars(trim($_POST['text_carte']));
            $val1 = htmlspecialchars(trim($_POST['valeurs_choix1']));
            $val2 = htmlspecialchars(trim($_POST['valeurs_choix2']));
            $val3 = htmlspecialchars(trim($_POST['valeurs_choix3']));
            $val4 = htmlspecialchars(trim($_POST['valeurs_choix4']));
            $valeur1 = "{$val1} / {$val2}";
            $valeur2 = "{$val3} / {$val4}";

            $date = new \DateTime();
            $date_format = $date->format('Y-m-d');

            $nb_cartes = Carte::getInstance()->findAllBy(['id_deck' => $id_deck]);
            $nb_cartes = count($nb_cartes) + 1;
            $_SESSION['nb_cartes'] = $nb_cartes;

            if (!empty($id_admin)) {
                Carte::getInstance()->create([
                    'texte_carte' => $text_carte,
                    'valeurs_choix1' => $valeur1,
                    'valeurs_choix2' => $valeur2,
                    'date_soumission' => $date_format,
                    'ordre_soumission' => $nb_cartes,
                    'id_deck' => $id_deck,
                    'id_administrateur' => $id_admin,
                ]);
            } else {
                Carte::getInstance()->create([
                    'texte_carte' => $text_carte,
                    'valeurs_choix1' => $valeur1,
                    'valeurs_choix2' => $valeur2,
                    'date_soumission' => $date_format,
                    'ordre_soumission' => $nb_cartes,
                    'id_deck' => $id_deck,
                    'id_createur' => $id_crea,
                ]);
            }

            HTTP::redirect('/accueil');
        }
    }

    public function ajoutaleatoire()
    {

        if (session_status() == PHP_SESSION_NONE) {
            session_start(); // Démarrer la session si nécessaire
        }
        if (empty($_SESSION['createur_id']) && empty($_SESSION['admin_id'])) {
            HTTP::redirect('/');
        } else {
            if (!empty($_SESSION['admin_id'])) {
                $id_admin = $_SESSION['admin_id'];
            }
            if (!empty($_SESSION['createur_id'])) {
                $id_crea = $_SESSION['createur_id'];
            }



            $decks = Deck::getInstance()->findAll();
            $date_actuelle = new \DateTime();
            // Tableau pour stocker les decks en cours
            $decks_en_cours = [];

            if (empty($decks)) {
                HTTP::redirect('/accueil');
            } else {
                foreach ($decks as $deck) {
                    $date_fin = new \DateTime($deck['date_fin_deck']);

                    if ($date_fin > $date_actuelle) {
                        $decks_en_cours[] = $deck;
                    }
                }
                if (empty($decks_en_cours)) {
                    HTTP::redirect('/accueil');
                } else {
                    $cartes = Carte::getInstance()->findAllBy([
                        'id_deck' => $decks_en_cours[0]['id_deck']
                    ]);
                    if (empty($cartes)) {
                        HTTP::redirect('/accueil');
                    } else {

                        $ordreMax = 0;
                        // Parcourir les cartes pour trouver celle avec l'ordre de soumission le plus grand
                        foreach ($cartes as $carte) {
                            if ($carte['ordre_soumission'] > $ordreMax) {
                                $ordreMax = $carte['ordre_soumission'];
                            }
                        }
                        if ($ordreMax >= $decks_en_cours[0]['nb_cartes']) {

                            HTTP::redirect('/accueil');
                        } else {

                            $numero = rand(1, $ordreMax);

                            $id_card = Carte::getInstance()->findOneBy([
                                'id_deck' => $decks_en_cours[0]['id_deck'],
                                'ordre_soumission' => $numero
                            ]);

                            if (!empty($id_admin)) {
                                $exists = CarteAleatoire::getInstance()->findOneBy([
                                    'id_deck' => $decks_en_cours[0]['id_deck'],
                                    'id_administrateur' => $id_admin
                                ]);
                            }
                            if (!empty($id_crea)) {
                                $exists = CarteAleatoire::getInstance()->findOneBy([
                                    'id_deck' => $decks_en_cours[0]['id_deck'],
                                    'id_createur' => $id_crea
                                ]);
                            }
                            if ($exists == null) {

                                if (!empty($id_admin)) {
                                    CarteAleatoire::getInstance()->create([
                                        'num_carte' => $numero,
                                        'id_deck' => $decks_en_cours[0]['id_deck'],
                                        'id_carte' => $id_card['id_carte'],
                                        'id_administrateur' => $id_admin,
                                    ]);
                                } else {
                                    CarteAleatoire::getInstance()->create([
                                        'num_carte' => $numero,
                                        'id_deck' => $decks_en_cours[0]['id_deck'],
                                        'id_carte' => $id_card['id_carte'],
                                        'id_createur' => $id_crea,
                                    ]);
                                }
                            }

                            HTTP::redirect('/accueil');
                        }
                    }
                }
            }
        }
    }
}
