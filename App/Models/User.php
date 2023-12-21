<?php

namespace App\Models;

use PDO;


class User extends \Core\Model
{

    /**
     * Get all the users as an associative array
     *
     * @return array
     */
    public static function getAll()
    {
        $db = static::getDB();
        $stmt = $db->query("SELECT user_id, user_nom, user_prenom, user_email, role FROM user
                            JOIN user_role ON role_id = user_role_id
                            ORDER BY user_id");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get one user as an associative array
     * @param int $user_id
     * @return array
     */
    public static function getOne($user_id)
    {
        $db = static::getDB();
        $stmt = $db->prepare("SELECT * FROM user
                              JOIN user_role ON role_id = user_role_id
                              WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Get all roles of users
     * 
     * @return array
     */
    public static function getRoles()
    {
        $db = static::getDB();
        $stmt = $db->query("SELECT * FROM user_role");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get all offres places by a user
     * @param int $user_id
     * @return array
     */
    public static function getOffers($user_id)
    {
        $db = static::getDB();
        $stmt = $db->prepare("SELECT * FROM offre
                              WHERE offre_user_id = :user_id");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Connecter un utilisateur
     * @param array $data, tableau avec les champs utilisateur_courriel et utilisateur_mdp
     * @return array
     */
    public static function connecter($data)
    {
        $db = static::getDB();
        $stmt = $db->prepare("SELECT user_id, user_nom, user_prenom, user_email, role FROM user 
                              JOIN user_role ON role_id = user_role_id
                              WHERE user_email = LOWER(TRIM(:user_email)) AND user_mdp = LOWER(TRIM(:user_mdp))");
/*
        $stmt = $db->prepare("SELECT user_id, user_nom, user_prenom, user_email, role FROM user 
                              JOIN user_role ON role_id = user_role_id
                              WHERE user_email = LOWER(TRIM(:user_email)) AND user_mdp = SHA2( LOWER(TRIM(:user_mdp)), 512)");
        **********************************************************************************************************************
        the above query no longer works? Because I updated to MySQL 8.0.31 and SHA2() no longer works?
*/
        $nomsParams = array_keys($data);
        foreach ($nomsParams as $nomParam) $stmt->bindParam(':' . $nomParam, $data[$nomParam], PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Ajouter un utilisateur
     * @param array $data
     * @return boolean true si suppression effectuée, false sinon
     */
    public static function insert($data)
    {
        $db = static::getDB();
        $stmt = $db->prepare("INSERT INTO user
                              SET user_nom     = LOWER(TRIM(:user_nom)), 
                                  user_prenom  = LOWER(TRIM(:user_prenom)),
                                  user_email   = LOWER(TRIM(:user_email)), 
                                  user_mdp     = LOWER(TRIM(:user_mdp)),
                                  user_role_id = :user_role_id");
        $nomsParams = array_keys($data);
        foreach ($nomsParams as $nomParam) $stmt->bindParam(':' . $nomParam, $data[$nomParam]);
        $stmt->execute();

        if ($stmt->rowCount() <= 0)  return false;
        if ($db->lastInsertId() > 0) return $db->lastInsertId();
        return true;
    }

    /**
     * Supprimer un utilisateur
     * @param int $user_id
     * @return boolean
     */
    public static function delete($user_id)
    {
        $db = static::getDB();
        $stmt = $db->prepare('DELETE FROM user WHERE user_id = :user_id');
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        if ($stmt->rowCount() <= 0)  return false;
        return true;
    }

    /**
     * modifier un utilisateur
     * @param array $data
     * @return boolean
     */
    public static function modifier($data)
    {
        $db = static::getDB();
        $stmt = $db->prepare("UPDATE user SET user_nom     = LOWER(TRIM(:user_nom)), 
                                              user_prenom  = LOWER(TRIM(:user_prenom)),
                                              user_email   = LOWER(TRIM(:user_email)), 
                                              user_mdp     = LOWER(TRIM(:user_mdp)),
                                              user_role_id = :user_role_id
                                          WHERE user_id = :user_id");
        $nomsParams = array_keys($data);
        foreach ($nomsParams as $nomParam) $stmt->bindParam(':' . $nomParam, $data[$nomParam]);
        $stmt->execute();

        if ($stmt->rowCount() <= 0)  return false;
        if ($db->lastInsertId() > 0) return $db->lastInsertId();
        return true;
    }

    /**
     * place un mise
     * @param array $data
     * @return boolean
     */
    public static function miser($data)
    {
        $db = static::getDB();
        $stmt = $db->prepare(' INSERT INTO offre 
                               SET offre_au_id = :offre_au_id,
                                   offre_user_id = :offre_user_id,
                                   offre_price = :offre_price,
                                   offre_date = NOW(),
                                   offre_success = 0');
        $nomsParams = array_keys($data);
        foreach ($nomsParams as $nomParam) $stmt->bindParam(':' . $nomParam, $data[$nomParam]);
        $stmt->execute();
        if ($stmt->rowCount() <= 0)  return false;
        return true;
    }

    /**
     * Supprimer un mise
     * @param int $offre_id
     * @return boolean
     */
    public static function unmiser($offre_id)
    {
        $db = static::getDB();
        $stmt = $db->prepare('DELETE FROM offre WHERE offre_id = :offre_id');
        $stmt->bindParam(':offre_id', $offre_id);
        $stmt->execute();
        if ($stmt->rowCount() <= 0)  return false;
        return true;
    }
}
