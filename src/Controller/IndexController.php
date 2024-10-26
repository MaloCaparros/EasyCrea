<?php

declare(strict_types=1); // strict mode

namespace App\Controller;

use App\Helper\HTTP;
use App\Model\Createur;

class IndexController extends Controller
{
    /**
     * Page d'accueil pour lister tous les avatars.
     * @route [get] /
     *
     */

    public function index()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start(); // Démarrer la session si nécessaire
        }
    
        // Supprimer toutes les variables de session
        session_unset();
        $this->display('index.html.twig');
    }
    

    public function connection()
    {
        if ($this->isGetMethod()) {
            $this->display('/Auth/connection.html.twig');
        } else {
            $email = trim($_POST['mail']);
            $password = trim($_POST['password']);
          
            $createur = Createur::getInstance()->findOneBy(['ad_mail_createur' => $email]);
            if ($createur) {
                // Vérifier le mot de passe pour le créateur
                if (password_verify($password, $createur['mdp_createur'])) {
                    if (session_status() == PHP_SESSION_NONE) {
                        session_start(); // Démarrer la session si nécessaire
                    }
                    // Authentification réussie, enregistrer le créateur dans la session
                    $_SESSION['createur'] = $password;
                    $_SESSION['createur_id'] = $createur['id_createur'];
                    HTTP::redirect('/avantaccueil');
                    return;
                } else {
                    // Mot de passe incorrect
                    HTTP::redirect('/');
                }
            }
            if(!$createur){
                HTTP::redirect('/');
            }
                  
        }
    }

    public function inscription()
    {
        if ($this->isGetMethod()) {
            $this->display('/Auth/inscription.html.twig');
        } else {
            $email = trim($_POST['email']);
            $password = htmlspecialchars(trim($_POST['password']));
            $displayName = htmlspecialchars(trim($_POST['display_name']));
            $genre = htmlspecialchars(trim($_POST['genre']));
            $dateNaissance = htmlspecialchars(trim($_POST['dateNaissance']));

            // 2. Validation des données
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                // Gérer l'erreur : email non valide
                $this->display('/Auth/inscription.html.twig', ['error' => 'Email invalide.']);
                return;
            }
            $hashedPassword = password_hash(trim($password), PASSWORD_DEFAULT);
            var_dump($_POST);
            // 2. exécuter la requête d'insertion

            Createur::getInstance()->create([
                'nom_createur' => $displayName,
                'ad_mail_createur' => $email,
                'mdp_createur' => $hashedPassword,
                'genre' => $genre,
                'ddn' => $dateNaissance,
            ]);
            // 3. Rediriger vers la page de connexion
            HTTP::redirect('/');
        }
    }
}
