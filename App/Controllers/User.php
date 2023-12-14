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
            $stamps = \App\Models\Stamp::getByUser($user_id);

            View::renderTemplate('User/profil.html',
                                ['user' => $_SESSION['user'],
                                 'auctions' => $auctions,
                                 'stamps' => $stamps,
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
     * gerer tous les utilisateur
     * 
     * @return void
     */
    public function adminAction()
    {
        if ( isset($_SESSION['user']) && ($_SESSION['user']['role'] == 'administrateur' || $_SESSION['user']['role'] == 'propriétaire')){
            $all_users = \App\Models\User::getAll();
            $all_auctions = \App\Models\Auction::getAllWithEmpty(); // Auction::getAll() only returns auctions with at least one stamp
            $all_stamps = \App\Models\Stamp::getAll();

            //var_dump($all_stamps);

            View::renderTemplate('User/admin.html',
                                ['user' => $_SESSION['user'],
                                 'users' => $all_users,
                                 'auctions' => $all_auctions,
                                 'stamps' => $all_stamps
                                ]);
        }else{
            echo "<script>alert('Vous devez se connecter comme administrateur ou propriétaire pour visiter cette page.');location.href='/user/login';</script>";
        }
    }

    /**
     * ajouter un utilisateur par formulaire
     *
     * @return void
     */
    public function ajouterAction()
    {
        if ( isset($_SESSION['user']) && ($_SESSION['user']['role'] == 'administrateur' || $_SESSION['user']['role'] == 'propriétaire')){
            
            $user = $_SESSION['user'];

            if (!empty($_POST)){

                //on enlève le champ "envoyer" pour que le tableau corresponde aux champs de la table en base de données
                unset($_POST["envoyer"]);
    
                $id_insertion = \App\Models\User::insert($_POST);
                echo "<br>L'id de l'utilisateur inséré est $id_insertion";
            }
            View::renderTemplate('User/ajouter.html',['user' => $user]);

        }else{
            echo "<script>alert('Vous devez se connecter comme administrateur ou propriétaire pour visiter cette page.');location.href='/user/login';</script>";
        }
    }

    /**
     * Show the modifier page
     *
     * @return void
     */
    public function modifierAction()
    {
        if ( isset($_SESSION['user']) && ($_SESSION['user']['role'] == 'administrateur' || $_SESSION['user']['role'] == 'propriétaire')){
            
            $user = $_SESSION['user'];

            $id = $this->route_params['id'];
            $userToModify = \App\Models\User::getOne($id);

            if (!empty($_POST)){

                //on enlève le champ "envoyer" pour que le tableau corresponde aux champs de la table en base de données
                unset($_POST["envoyer"]);
    
                $id_insertion = \App\Models\User::modifier($_POST);
                echo "<br>L'id de l'utilisateur modifié est $id_insertion";
                
            }
            View::renderTemplate('User/modifier.html',
                                ['user' => $user,
                                 'userToModify' => $userToModify]);

        }else{
            echo "<script>alert('Vous devez se connecter comme administrateur ou propriétaire pour visiter cette page.');location.href='/user/login';</script>";
        }
    }
    /**
     * supprimer un utillisateur
     *
     * @return void
     */
    public function supprimerAction()
    {
        $id = $this->route_params['id'];
        if (\App\Models\User::delete($id)) echo "<script>location.href='/user/admin';</script>";
    }
}

