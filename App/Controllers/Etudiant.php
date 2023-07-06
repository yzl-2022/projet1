<?php

namespace App\Controllers;

use \Core\View;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class Etudiant extends \Core\Controller
{

    /**
     * Show the index page
     *
     * @return void
     */
    public function indexAction()
    {
        $list = \App\Models\Etudiant::getAll();
        View::renderTemplate('Etudiant/index.html',
                             ['etudiants' => $list]
        );
    }
    /**
     * Show the ajouter page
     *
     * @return void
     */
    public function ajouterAction()
    {
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
        View::renderTemplate('Etudiant/supprimer.html');
    }
}

