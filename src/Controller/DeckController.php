<?php

declare(strict_types=1); // strict mode

namespace App\Controller;

use App\Helper\HTTP;
use App\Model\Deck;
use App\Model\Administrateur;


class DeckController extends Controller
{
    public function index()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start(); // Démarrer la session si nécessaire
        }
        if (empty($_SESSION['admin_id'])) {
            HTTP::redirect('/');
        }
        else{
            $administrateurs = Administrateur::getInstance()->findAll(); // Méthode qui récupère tous les administrateurs
            $this->display('/Admin/admincreadeck.html.twig', ['administrateurs' => $administrateurs]);
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
            if (empty($_SESSION['admin_id'])) {
                HTTP::redirect('/');
            }
            else {
                $titre = trim($_POST['titre']);
            $dateDebut = trim($_POST['dateDebut']);
            $dateFin = trim($_POST['dateFin']);
            $nombre = trim($_POST['nombre']);
            $jaime = trim($_POST['jaime']);
            $idAdmin = trim($_POST['administrateur']);


            Deck::getInstance()->create([
                'titre_deck' => $titre,
                'date_debut_deck' => $dateDebut,
                'date_fin_deck' => $dateFin,
                'nb_cartes' => $nombre,
                'nb_jaime' => $jaime,
                'id_administrateur' => $idAdmin,
            ]);
            // 3. Rediriger vers la page de connexion
            HTTP::redirect('/adminaccueilverif');
            }
            
        }
        
    }
    
}
