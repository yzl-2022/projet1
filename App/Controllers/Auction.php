<?php

namespace App\Controllers;

use \Core\View;

/**
 * Auction controller
 *
 * PHP version 7.0
 */
class Auction extends \Core\Controller
{

    /**
     * lister tous encheres
     *
     * @return void
     */
    public function listerAction()
    {
        // check if the user has log in
        $user = null;
        if (isset($_SESSION['user'])) $user = $_SESSION['user'];

        // obtain data
        $list = \App\Models\Auction::getAll();

        View::renderTemplate('Auction/index.html',
                             ['user' => $user,
                              'auctions' => $list]);
    }

    /**
     * lister tous timbres d'une enchere
     *
     * @return void
     */
    public function instanceAction()
    {
        // obtain data
        $au_id = $this->route_params['id'];
        $stamps = \App\Models\Auction::getStamps($au_id);
        $offers = \App\Models\Auction::getOffers($au_id);
        $au = \App\Models\Auction::getOne($au_id);

        // check if the user has log in
        $user = null;
        if (isset($_SESSION['user'])) $user = $_SESSION['user'];
        $like = null;
        if ($user) $like = \App\Models\User::isLiked($user['user_id'],$au_id);

        View::renderTemplate('Auction/instance.html',
                             ['user' => $user,
                              'au_id' => $au_id,
                              'stamps' => $stamps,
                              'offers' => $offers,
                              'au' => $au,
                              'like' => $like
                            ]);
    }

    /**
     * ajouter une enchère par formulaire
     *
     * @return void
     */
    public function ajouterAction()
    {
        // check if the user has log in -- this page is not accessible without login
        if (!isset($_SESSION['user'])){
            echo "<script>alert('Vous devez se connecter pour visiter cette page.');location.href='".\App\Config::URL_RACINE."/user/login';</script>";
        }else{
            $user = $_SESSION['user'];

            if (!empty($_POST)) {

                if (\App\Models\Auction::insert($_POST)) echo "<script>location.href='".\App\Config::URL_RACINE."/user/profil';</script>";
            }
    
            View::renderTemplate('Auction/ajouter.html',
                                 ['user' => $user]);
        }
    }

    /**
     * modifier une enchere
     *
     * @return void
     */
    public function modifierAction()
    {
        // check if the user has log in -- this page is not accessible without login
        if (!isset($_SESSION['user'])){
            echo "<script>alert('Vous devez se connecter pour visiter cette page.');location.href='".\App\Config::URL_RACINE."/user/login';</script>";
        }else{
            $user = $_SESSION['user'];

            $au_id = $this->route_params['id'];
            $au = \App\Models\Auction::getOne($au_id);
            
            if (!empty($_POST)) {

               if (\App\Models\Auction::modifier($_POST))echo "<script>location.href='".\App\Config::URL_RACINE."/user/profil';</script>";

            }

            View::renderTemplate('Auction/modifier.html',
                                ['au_id' => $au_id,
                                 'au' => $au,
                                 'user' => $user]
            );
        }
    }

    /**
     * supprimer une enchere
     *
     * @return void
     */
    public function supprimerAction()
    {
        // check if the user has log in -- this page is not accessible without login
        if (!isset($_SESSION['user'])){
            echo "<script>alert('Vous devez se connecter pour visiter cette page.');location.href='".\App\Config::URL_RACINE."/user/login';</script>";
        }else{
            $id = $this->route_params['id'];
            if (\App\Models\Auction::delete($id)) echo "<script>location.href='".\App\Config::URL_RACINE."/user/profil';</script>";
        }
    }
}

