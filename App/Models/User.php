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
                            ORDER BY user_nom");
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
        $stmt = $db->prepare("SELECT user_id, user_nom, user_prenom, user_email, role FROM user
                              JOIN user_role ON role_id = user_role_id
                              WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
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
                              WHERE user_email = LOWER(TRIM(:user_email)) AND user_mdp = SHA2( LOWER(TRIM(:user_mdp)), 512)");
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
                                  user_mdp     = SHA2( LOWER(TRIM(:user_mdp)), 512),
                                  user_active  = 1,  
                                  user_role_id = 3");
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
}
