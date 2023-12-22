<?php

namespace App\Models;

use PDO;


class Auction extends \Core\Model
{
    /**
     * Get all auctions
     *
     * @return array
     */
    public static function getAll()
    {
        $db = static::getDB();
        $stmt = $db->query("SELECT * FROM auction
                            JOIN stamp ON au_id = st_au_id
                            JOIN user ON au_user_id = user_id
                            JOIN photo on st_id = photo_st_id
                            WHERE photo_principal = 1
                            GROUP BY au_id");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get all auctions even those without no stamps
     *
     * @return array
     */
    public static function getAllWithEmpty()
    {
        $db = static::getDB();
        $stmt = $db->query("SELECT * FROM auction
                            JOIN user ON au_user_id = user_id");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get 4 auctions
     *
     * @return array
     */
    public static function get4()
    {
        $db = static::getDB();
        $stmt = $db->query("SELECT * FROM auction
                            JOIN stamp ON au_id = st_au_id
                            JOIN photo on st_id = photo_st_id
                            WHERE photo_principal = 1
                            GROUP BY au_id
                            LIMIT 4");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get one auction by its id
     * @param int $au_id
     * @return array
     */
    public static function getOne($au_id)
    {
        $db = static::getDB();
        $stmt = $db->prepare("SELECT * FROM auction WHERE au_id = :au_id");
        $stmt->bindParam(':au_id', $au_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Get all stamps of one auction
     * @param int $au_id
     * @return array
     */
    public static function getStamps($au_id)
    {
        $db = static::getDB();
        $stmt = $db->prepare("SELECT * FROM stamp 
                              JOIN photo on st_id = photo_st_id
                              WHERE st_au_id = :au_id");
        $stmt->bindParam(':au_id', $au_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get all offers by all users of an auction
     * @param int $au_id
     * @return array
     */
    public static function getOffers($au_id)
    {
        $db = static::getDB();
        $stmt = $db->prepare("SELECT offre_price, offre_date, user_nom, user_prenom FROM offre 
                              JOIN user on offre_user_id = user_id
                              WHERE offre_au_id = :au_id");
        $stmt->bindParam(':au_id', $au_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get all auctions of one user
     * @param int $user_id
     * @return array
     */
    public static function getByUser($user_id)
    {
        $db = static::getDB();
        $stmt = $db->prepare("SELECT * FROM auction 
                              WHERE au_user_id = :user_id
                              GROUP BY au_id");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * ajouter une enchère
     * @param array $data
     * @return boolean true si addition effectuée, false sinon
     */

    public static function insert($data)
    {
        $db = static::getDB();
        $stmt = $db->prepare("INSERT INTO auction SET au_user_id = :au_user_id, 
                                                      au_prix_plancher = :au_prix_plancher, 
                                                      au_start_date = CONCAT( :au_start_date, ' ', :au_start_time, ':00'),
                                                      au_end_date = CONCAT( :au_end_date, ' ', :au_end_time, ':00')");
        $nomsParams = array_keys($data);
        foreach ($nomsParams as $nomParam) $stmt->bindParam(':' . $nomParam, $data[$nomParam]);
        $stmt->execute();

        if ($stmt->rowCount() <= 0)  return false;
        if ($db->lastInsertId() > 0) return $db->lastInsertId();
        return true;
    }

    /**
     * supprimer une enchère
     * @param int $au_id
     * @return boolean true si suppression effectuée, false sinon
     */
    public static function delete($id)
    {
        $db = static::getDB();
        $stmt = $db->prepare('DELETE FROM auction WHERE au_id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        if ($stmt->rowCount() <= 0)  return false;
        return true;
    }

    /**
     * modifier une enchère
     * @param array $data
     * @return boolean
     */
    public static function modifier($data)
    {
        $db = static::getDB();
        $stmt = $db->prepare("UPDATE auction SET au_user_id = :au_user_id, 
                                                 au_prix_plancher = :au_prix_plancher, 
                                                 au_start_date = CONCAT( :au_start_date, ' ', :au_start_time, ':00'),
                                                 au_end_date = CONCAT( :au_end_date, ' ', :au_end_time, ':00')
                                             WHERE au_id = :au_id");
        $nomsParams = array_keys($data);
        foreach ($nomsParams as $nomParam) $stmt->bindParam(':' . $nomParam, $data[$nomParam]);
        $stmt->execute();

        if ($stmt->rowCount() <= 0)  return false;
        if ($db->lastInsertId() > 0) return $db->lastInsertId();
        return true;
    }
}
