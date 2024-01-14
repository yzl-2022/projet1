<?php

namespace App;

/**
 * Application configuration
 *
 * PHP version 7.0
 */
class Config
{

    /**
     * Database host
     * @var string
     */
    const DB_HOST = 'localhost';

    /**
     * Database name
     * @var string
     */
    const DB_NAME = 'stampee';
    //const DB_NAME = 'e2296635';

    /**
     * Database user
     * @var string
     */
    const DB_USER = 'root';
    //const DB_USER = 'e2296635';

    /**
     * Database password
     * @var string
     */
    const DB_PASSWORD = '';
    //const DB_PASSWORD = 'mQMnfdYh312WKWrugX9R';

    /**
     * Show or hide error messages on screen
     * @var boolean
     */
    const SHOW_ERRORS = true;

    /**
     * l’url racine -- ajouter cette url à Twig
     */
    const URL_RACINE = "http://localhost";
    //const URL_RACINE = "https://e2296635.webdev.cmaisonneuve.qc.ca/projet1/public";
}
