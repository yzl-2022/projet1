<?php

namespace App\Controllers;

use \Core\View;

/**
 * Etudiant controller
 *
 * PHP version 7.0
 */
class Etudiant extends \Core\Controller
{

    /**
     * lister tous etudiants
     *
     * @return void
     */
    public function listerAction()
    {
        $list = \App\Models\Etudiant::getAll();
        View::renderTemplate('Etudiant/index.html',
                             ['etudiants' => $list]);
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

