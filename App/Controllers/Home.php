<?php

namespace App\Controllers;

use \Core\View;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class Home extends \Core\Controller
{

    /**
     * Show the index page
     *
     * @return void
     */
    public function indexAction()
    {
        // check if the user has log in
        if (isset($_SESSION['user'])){
            $user = $_SESSION['user'];
        }else{
            $user = null;
        }

        // obtain data
        $stamp4 = \App\Models\Stamp::get4();
        $auction4 = \App\Models\Auction::get4();

        View::renderTemplate('Home/index.html',
                            ['user' => $user,
                             'stamps' => $stamp4,
                             'auctions' => $auction4]);
    }
}

