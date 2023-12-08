<?php

namespace App\Controllers;

use \Core\View;

/**
 * Etudiant controller
 *
 * PHP version 7.0
 */
class Stamp extends \Core\Controller
{

    /**
     * lister tous etudiants
     *
     * @return void
     */
    public function listerAction()
    {
        // check if the user has log in
        $user = null;
        if (isset($_SESSION['user'])) $user = $_SESSION['user'];

        // obtain data
        $list = \App\Models\Stamp::getAll();

        View::renderTemplate('Stamp/index.html',
                             ['user' => $user,
                              'stamps' => $list]);
    }

    /**
     * lister un timbre
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
        $list = \App\Models\Stamp::getOne($id);

        View::renderTemplate('Stamp/instance.html',
                             ['user' => $user,
                              'st_id' => $id,
                              'stamp' => $list]);
    }

    /**
     * ajouter un etudiant par formulaire
     *
     * @return void
     */
    public function ajouterAction()
    {
        if (!empty($_POST)){
            //on enlève le champ "envoyer" pour que le tableau corresponde aux champs de la table en base de données
            unset($_POST["envoyer"]);
        }

        $categories = \App\Models\Stamp::getCategories();
        $last_st = \App\Models\Stamp::getLast();
        $next_st_id = $last_st['st_id'] + 1;
        
        View::renderTemplate('Stamp/ajouter.html',
                              ['categories' => $categories,
                              'next_st_id' => $next_st_id
                              ]);
    }

    /**
     * Show the modifier page
     *
     * @return void
     */
    public function modifierAction()
    {
        $id = $this->route_params['id'];
        View::renderTemplate('Stamp/modifier.html',
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
        echo  \App\Models\Stamp::delete($id);
    }
}

