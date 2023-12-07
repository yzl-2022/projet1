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
     * Get one auction and all its stamps
     * @param int $au_id
     * @return array
     */
    public static function getOne($au_id)
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
     * Get the id of the last auction available
     * @return int $au_id of the last element in the table
     */
    public static function getLast()
    {
        $db = static::getDB();
        $stmt = $db->prepare("SELECT * FROM auction 
                              ORDER BY au_id DESC
                              LIMIT 1");
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
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
                              JOIN stamp ON st_au_id = au_id
                              JOIN photo on st_id = photo_st_id
                              WHERE au_user_id = :user_id
                              GROUP BY au_id");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * ajouter une enchère
     * @param array $data
     * @return boolean true si suppression effectuée, false sinon
     */

    public static function insert($data)
    {
        $db = static::getDB();
        $stmt = $db->prepare('INSERT INTO etudiant SET nom = :nom, age = :age');
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
     * @return boolean
     */
    public static function delete($id)
    {
        $db = static::getDB();
        $stmt = $db->prepare('DELETE FROM etudiant WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        if ($stmt->rowCount() <= 0)  return false;
        return true;
    }
}
