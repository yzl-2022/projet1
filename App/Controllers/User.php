<?php

namespace App\Controllers;

use \Core\View;

/**
 * User controller
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
            $offers = \App\Models\User::getOffers($user_id);
            $favoris = \App\Models\User::getFavoris($user_id);

            View::renderTemplate('User/profil.html',
                                ['user' => $_SESSION['user'],
                                 'auctions' => $auctions,
                                 'stamps' => $stamps,
                                 'offers' => $offers,
                                 'favoris' => $favoris
                                ]);
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

            View::renderTemplate('User/admin.html',
                                ['user' => $_SESSION['user'],
                                 'users' => $all_users,
                                 'auctions' => $all_auctions,
                                 'stamps' => $all_stamps
                                ]);
        }else{
            echo "<script>alert('Vous devez se connecter comme administrateur ou propriétaire pour visiter cette page.');location.href='".\App\Config::URL_RACINE."/user/login';</script>";
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

            //get a list of all roles from SQL query
            $roles = \App\Models\User::getRoles();

            if (!empty($_POST)){
    
                $id_insertion = \App\Models\User::insert($_POST);
                echo "<br>L'id de l'utilisateur inséré est $id_insertion";
            }
            View::renderTemplate('User/ajouter.html',
                                ['user' => $user,
                                 'roles' => $roles]);

        }else{
            echo "<script>alert('Vous devez se connecter comme administrateur ou propriétaire pour visiter cette page.');location.href='".\App\Config::URL_RACINE."/user/login';</script>";
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

            //get a list of all roles from SQL query
            $roles = \App\Models\User::getRoles();

            $id = $this->route_params['id'];
            $userToModify = \App\Models\User::getOne($id);

            if (!empty($_POST)){
    
                $id_insertion = \App\Models\User::modifier($_POST);
                echo "<br>L'id de l'utilisateur modifié est $id_insertion";
                
            }
            View::renderTemplate('User/modifier.html',
                                ['user' => $user,
                                 'roles' => $roles,
                                 'userToModify' => $userToModify]);

        }else{
            echo "<script>alert('Vous devez se connecter comme administrateur ou propriétaire pour visiter cette page.');location.href='".\App\Config::URL_RACINE."/user/login';</script>";
        }
    }

    /**
     * supprimer un utillisateur
     *
     * @return void
     */
    public function supprimerAction()
    {
        if ( isset($_SESSION['user']) && ($_SESSION['user']['role'] == 'administrateur' || $_SESSION['user']['role'] == 'propriétaire')){
            $id = $this->route_params['id'];
            if (\App\Models\User::delete($id)) echo "<script>location.href='".\App\Config::URL_RACINE."/user/admin';</script>";
        }else{
            echo "<script>alert('Vous devez se connecter comme administrateur ou propriétaire pour visiter cette page.');location.href='".\App\Config::URL_RACINE."/user/login';</script>";
        }
    }

    /**
     * placer un mise
     * 
     * @return void
     */
    public function miserAction()
    {
        if (isset($_SESSION['user'])) { //only the members signed up can do this

            $user = $_SESSION['user'];
            $au_id = $this->route_params['id'];
            $au = \App\Models\Auction::getOne($au_id);

            if (!empty($_POST)){

                if (\App\Models\User::miser($_POST)) echo "<script>alert('Vous avez placé un mise avec succès');location.href='".\App\Config::URL_RACINE."/auction/instance/$au_id';</script>";
            }
            View::renderTemplate('User/miser.html',
                                ['user' => $user,
                                 'au_id' => $au_id,
                                 'au' => $au]);

        }else{
            echo "<script>alert('Vous devez se connecter pour placer un mise.');location.href='".\App\Config::URL_RACINE."/user/login';</script>";
        }
    }

    /**
     * ajouter au favoris (favorisé)
     * 
     * @return void
     */
    public function favoriserAction()
    {
        if (isset($_SESSION['user'])) { //only the members signed up can do this

            $user = $_SESSION['user'];
            $au_id = $this->route_params['id'];

            if (\App\Models\User::favoriser($user['user_id'], $au_id)) echo "<script>history.back()</script>";

        }else{
            echo "<script>alert('Vous devez se connecter pour ajouter à la liste des favoris.');location.href='".\App\Config::URL_RACINE."/user/login';</script>";
        }
    }

    /**
     * supprimer au favoris (unfavorisé)
     * 
     * @return void
     */
    public function unfavoriserAction()
    {
        if (isset($_SESSION['user'])) { //only the members signed up can do this

            $user = $_SESSION['user'];
            $au_id = $this->route_params['id'];

            if (\App\Models\User::unfavoriser($user['user_id'], $au_id)) echo "<script>history.back()</script>";

        }else{
            echo "<script>alert('Vous devez se connecter pour supprimer dans la liste des favoris.');location.href='".\App\Config::URL_RACINE."/user/login';</script>";
        }
    }
}

