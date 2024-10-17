<?php

declare(strict_types=1); // strict mode

namespace App\Controller;

use App\Helper\HTTP;
use App\Model\Administrateur;
use App\Model\Deck;


class AdminController extends Controller
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
        var_dump($_SESSION);

        if (empty($_SESSION['admin_id'])) {
            HTTP::redirect('/');
        } else {


            $adminId = $_SESSION['admin_id'];

            $decks = Deck::getInstance()->findAllBy([
                'id_administrateur' => $adminId
            ]);

            $currentDate = date('Y-m-d');
            $validDecks = [];
            foreach ($decks as $deck) {
                if ($deck['date_fin_deck'] > $currentDate) {
                    $validDecks[] = $deck; // Ajouter les decks valides au tableau
                }
            }

            // Si des decks existent, $isVerifie sera vrai
            $isVerifie = !empty($validDecks);


            // Affiche la vue avec la variable isVerifie
            $this->display('indexadmin.html.twig', ['isVerifie' => $isVerifie]);
        }
    }

    public function verification()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start(); // Démarrer la session si nécessaire
        }

        if (isset($_SESSION['admin']) && isset($_SESSION['admin_password'])) {
            $email = $_SESSION['admin'];
            $password = $_SESSION['admin_password'];

            $admin = Administrateur::getInstance()->findOneBy(['ad_mail_admin' => $email]);

            if ($admin && password_verify($password, $admin['mdp_admin'])) {
                // Authentification réussie
                if (isset($_SESSION['admin_password'])) {
                    unset($_SESSION['admin_password']);
                }
                if (isset($_SESSION['admin'])) {
                    unset($_SESSION['admin']);
                }
                HTTP::redirect('/adminaccueilverif');
            } else {
                if (isset($_SESSION['admin_password'])) {
                    unset($_SESSION['admin_password']);
                }
                if (isset($_SESSION['admin'])) {
                    unset($_SESSION['admin']);
                }
                HTTP::redirect('/Auth/connectionadmin');
            }
        } else {
            // Si la session n'est pas définie, rediriger vers la connexion
            HTTP::redirect('/');
        }
    }



    public function connection()
    {
        if ($this->isGetMethod()) {
            $this->display('/Auth/connectionadmin.html.twig');
        } else {
            if (empty($_POST)) {
                HTTP::redirect('/');
            } else {
                $email = htmlspecialchars(trim($_POST['mail']));
                $password = htmlspecialchars(trim($_POST['password']));

                $admin = Administrateur::getInstance()->findOneBy(['ad_mail_admin' => $email]);
                var_dump($admin);
                if ($admin) {
                    if (password_verify($password, $admin['mdp_admin'])) {
                        // Authentification réussie
                        session_start();
                        $_SESSION['admin_id'] = $admin['id_administrateur'];
                        $_SESSION['admin'] = $email;
                        $_SESSION['admin_password'] = $password;
                        HTTP::redirect('/adminaccueil');
                    } else {
                        HTTP::redirect('/Auth/connectionadmin');
                    }
                } else {
                    HTTP::redirect('/');
                }
            }
        }
    }
}
