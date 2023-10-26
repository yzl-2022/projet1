<?php

namespace App\Controllers;

use \Core\View;

/**
 * Etudiant controller
 *
 * PHP version 7.0
 */
class User extends \Core\Controller
{

    /**
     * se connecter
     *
     * @return void
     */
    public function loginAction()
    {
        if (isset($_SESSION['user'])) unset($_SESSION['user']);

        if (count($_POST) !== 0){
            $newUser = \App\Models\User::insert($_POST);
        }
        //afficher le page de se connecter
        View::renderTemplate('User/login.html');
    }
    
    /**
     * s'inscrire
     *
     * @return void
     */
    public function signupAction()
    {
        if (isset($_SESSION['user'])) unset($_SESSION['user']);
        //afficher le page de s'inscrire
        View::renderTemplate('User/signup.html');
    }

    /**
     * afficher les timbres et encheres d'un utilisateur
     *
     * @return void
     */
    public function profilAction()
    {
        if (isset($_SESSION['user'])) { // if the user already login
            // get stamps and auctions of this user
            $user_id = $_SESSION['user']['user_id'];
            $auctions = \App\Models\Auction::getByUser($user_id);
            $last_au = \App\Models\Auction::getLast();
            $next_au_id = $last_au['au_id'] + 1;
            $stamps = \App\Models\Stamp::getByUser($user_id);
            $last_st = \App\Models\Stamp::getLast();
            $next_st_id = $last_st['st_id'] + 1;

            View::renderTemplate('User/profil.html',
                                ['user' => $_SESSION['user'],
                                 'auctions' => $auctions,
                                 'stamps' => $stamps,
                                 'next_au_id' => $next_au_id,
                                 'next_st_id' => $next_st_id
                                ]);
        }else{
            $user = null;
            $msgErr = '';
            if (count($_POST) !== 0){
                $user = \App\Models\User::connecter($_POST);
                if ($user !== false){
                    $_SESSION['user'] = $user;
                    exit(header('Location:/user/profil')); //$_SESSION need to refresh the page
                    View::renderTemplate('User/profil.html',
                                        ['user' => $user]);
                }else{ // if connection fails
                    $msgErr = "Courriel ou mot de passe incorrect.";
                    View::renderTemplate('User/login.html',
                                        ['msgErr' => $msgErr]);
                }
            }
        } 
    }

    /**
     * ajouter un utilisateur par formulaire
     *
     * @return void
     */
    public function ajouterAction()
    {
        if (!empty($_POST)){

            //on enlève le champ "envoyer" pour que le tableau corresponde aux champs de la table en base de données
            unset($_POST["envoyer"]);

            $id_insertion = \App\Models\Etudiant::insert($_POST);
            echo "<br>L'id de l'étudiant inséré est $id_insertion";
        }
        View::renderTemplate('Etudiant/ajouter.html');
    }

    /**
     * Show the modifier page
     *
     * @return void
     */
    public function modifierAction()
    {
        $id = $this->route_params['id'];
        View::renderTemplate('Etudiant/modifier.html',
                            ['id' => $id]
        );
    }
    /**
     * Show the delete page
     *
     * @return void
     */
    public function supprimerAction()
    {
        $id = $this->route_params['id'];
        echo  \App\Models\Etudiant::delete($id);
    }
}

