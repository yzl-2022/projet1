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
            View::renderTemplate('User/profil.html',
                                ['user' => $_SESSION['user']]);
        }else{
            $user = null;
            $msgErr = '';
            if (count($_POST) !== 0){
                $user = \App\Models\User::connecter($_POST);
                if ($user !== false){
                    $_SESSION['user'] = $user;
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

