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
        $user = null;
        if (isset($_SESSION['user'])) $user = $_SESSION['user'];

        // show the current stamps and/or auctions
        $stamp4 = \App\Models\Stamp::getAll();
        $auction4 = \App\Models\Auction::getAll();

        View::renderTemplate('Home/index.html',
                            ['user' => $user,
                             'stamps' => $stamp4,
                             'auctions' => $auction4]);
    }
}

