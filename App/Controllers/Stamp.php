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
        // check if the user has log in -- this page is not accessible without login
        if (!isset($_SESSION['user'])){
            echo "<script>alert('Vous devez se connecter pour visiter cette page.');location.href='/user/login';</script>";
        }else{
            $user = $_SESSION['user'];

            //get a list of all categories from SQL query
            $categories = \App\Models\Stamp::getCategories();

            //get the au_id from url
            $st_au_id = $this->route_params['id'];

            if (!empty($_POST)) {
                //unset($_POST["envoyer"]);

                var_dump($_FILES);

                //treatment of image upload
                if($_FILES['photo_name']['error']){
                    echo "<script>alert('erreur dans image');history.back();</script>";
                    exit;
                }
                if(!empty($_FILES['photo_name']['name'])){
                    //maximum file size
                    if($_FILES['photo_name']['size'] > 3000){
                        echo "<script>alert('image trops large');history.back();</script>";
                        exit;
                    }
                    //file format
                    $allowType = array('png','gif','jpg','jpeg');
                    $arrCut = explode('.',$_FILES['photo_name']['name']);
                    $ext = $arrCut[count($arrCut) - 1];
                    if (!in_array($ext, $allowType)){
                        echo "<script>alert('wrong image format');history.back();</script>";
                        exit;
                    }
                    //file path
                    $uploaddir = "../../assets/images/";
                    $uploadfile = $uploaddir . basename($_FILES['photo_name']['name']);

                    if(move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)){
                        $id_insertion = \App\Models\Stamp::insert($_POST);
                        echo "<br>L'id de l'timbre inséré est $id_insertion";
                    }else{
                        echo "<script>alert('image non trouvé');history.back();</script>";
                        exit;
                    }
                }
            }

            View::renderTemplate('Stamp/ajouter.html',
                                ['user' => $user,
                                 'categories' => $categories,
                                 'st_au_id' => $st_au_id
                                ]);
        }
    }

    /**
     * modifier un timbre
     *
     * @return void
     */
    public function modifierAction()
    {
        // check if the user has log in -- this page is not accessible without login
        if (!isset($_SESSION['user'])){
            echo "<script>alert('Vous devez se connecter pour visiter cette page.');location.href='/user/login';</script>";
        }else{
            $user = $_SESSION['user'];

            $st_id = $this->route_params['id'];
            $st = \App\Models\Stamp::getOne($st_id);
            
            //get a list of all categories from SQL query
            $categories = \App\Models\Stamp::getCategories();

            //var_dump($st);

            if (!empty($_POST)) {
                unset($_POST["envoyer"]);

                $id_insertion = \App\Models\Stamp::modifier($_POST);
                echo "<br>L'id du timbre modifié est $id_insertion";
            }

            View::renderTemplate('Stamp/modifier.html',
                                ['st_id' => $st_id,
                                 'st' => $st,
                                 'categories' => $categories,
                                 'user' => $user]
            );
        }
    }

    /**
     * supprimer un timbre
     *
     * @return void
     */
    public function supprimerAction()
    {
        // check if the user has log in -- this page is not accessible without login
        if (!isset($_SESSION['user'])){
            echo "<script>alert('Vous devez se connecter pour visiter cette page.');location.href='/user/login';</script>";
        }else{
            $id = $this->route_params['id'];
            if (\App\Models\Stamp::delete($id)) echo "<script>location.href='/user/profil';</script>";
        }
    }
}

