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

                if( !isset($_FILES) ){
                    echo "<script>alert('Il fault ajouter une image');history.back();</script>";
                    exit;
                }else{
                    //treatment of image upload
                    if($_FILES['photo']['error']){
                        echo "<script>alert('erreur dans image');history.back();</script>";
                        exit;
                    }
                    if(!empty($_FILES['photo']['name'])){  // upload exists
                        //maximum file size -- int in ['size']
                        if($_FILES['photo']['size'] > 3000){
                            echo "<script>alert('image trops large');history.back();</script>";
                            exit;
                        }
                        //file format -- string in ['type']
                        $allowType = array('image/png','image/gif','image/jpg','image/jpeg','image/pjpeg');
                        if (!in_array($_FILES['photo']['type'], $allowType)){
                            echo "<script>alert('wrong image format');history.back();</script>";
                            exit;
                        }
                        //file path
                        $uploaddir = "assets/images/"; //root folder is /public, write anything directly under it
                        $uploadfile = $uploaddir . $_FILES['photo']['name'];

                        if(move_uploaded_file($_FILES['photo']['tmp_name'], $uploadfile)){
                            //prepare data from the formulaire
                            unset($_POST['MAX_FILE_SIZE']);
                            $photo_name = $_FILES['photo']['name'];
                            //insert into the table `stamp`
                            $st_id = \App\Models\Stamp::insert($_POST);
                            //insert into the table `photo`
                            if (\App\Models\Stamp::insertPhoto($st_id, $photo_name,1)) echo "<script>location.href='/user/profil';</script>";
                        }else{
                            echo "<script>alert('image non trouvé');history.back();</script>";
                            exit;
                        }
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

            if (!empty($_POST)) {

                
                if( empty($_FILES['photo']['name']) ){    // no photo uploaded, do not change the photo
                    unset($_POST['MAX_FILE_SIZE']);

                    if (\App\Models\Stamp::modifier($_POST)) echo "<script>location.href='/user/profil';</script>";

                    exit;
                    
                }else{  // upload exists

                    //treatment of image upload
                    if($_FILES['photo']['error'] AND ($_FILES['photo']['error'] != 4)){
                        echo "<script>alert('erreur dans image');</script>";
                        exit;
                    }
                    //maximum file size -- int in ['size']
                    if($_FILES['photo']['size'] > 3000){
                        echo "<script>alert('image trops large');history.back();</script>";
                        exit;
                    }
                    //file format -- string in ['type']
                    $allowType = array('image/png','image/gif','image/jpg','image/jpeg','image/pjpeg');
                    if (!in_array($_FILES['photo']['type'], $allowType)){
                        echo "<script>alert('wrong image format');history.back();</script>";
                        exit;
                    }
                    //file path
                    $uploaddir = "assets/images/"; //root folder is /public, write anything directly under it
                    $uploadfile = $uploaddir . $_FILES['photo']['name'];

                    if(move_uploaded_file($_FILES['photo']['tmp_name'], $uploadfile)){
                        //prepare data from the formulaire
                        unset($_POST['MAX_FILE_SIZE']);
                        $photo_name = $_FILES['photo']['name'];
                        //insert into the table `stamp`
                        $bool = \App\Models\Stamp::modifier($_POST);
                        //insert into the table `photo`
                        if (\App\Models\Stamp::modifierPhoto($st_id, $photo_name)) echo "<script>location.href='/user/profil';</script>";
                    }else{
                        echo "<script>alert('image non trouvé');history.back();</script>";
                        exit;
                    }
                }
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

