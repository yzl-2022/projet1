<?php

namespace App\Controllers;

use \Core\View;

/**
 * Etudiant controller
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
        // check if the user has log in
        $user = null;
        if (isset($_SESSION['user'])) $user = $_SESSION['user'];

        // obtain data
        $id = $this->route_params['id'];
        $list = \App\Models\Auction::getOne($id);

        View::renderTemplate('Auction/instance.html',
                             ['user' => $user,
                              'au_id' => $id,
                              'stamps' => $list
                            ]);
    }

    /**
     * ajouter une enchere par formulaire
     *
     * @return void
     */
    public function ajouterAction()
    {
        // check if the user has log in
        $user = null;
        if (isset($_SESSION['user'])) $user = $_SESSION['user'];

        // get the id of auction to be added
        $id = $this->route_params['id'];

        var_dump($_POST);

        if (!empty($_POST)){

            unset($_POST["envoyer"]); // clear the content of $_POST

            $id_insertion = \App\Models\Auction::insert($_POST);
            echo "<br>L'id de l'étudiant inséré est $id_insertion";
        }
        View::renderTemplate('Auction/ajouter.html',
                             ['user' => $user,
                              'au_id' => $id]);
    }

    /**
     * modifier une enchere
     *
     * @return void
     */
    public function modifierAction()
    {
        $id = $this->route_params['id'];
        View::renderTemplate('Auction/modifier.html',
                            ['id' => $id]
        );
    }

    /**
     * supprimer une enchere
     *
     * @return void
     */
    public function supprimerAction()
    {
        $id = $this->route_params['id'];
        echo  \App\Models\Auction::delete($id);
    }
}

