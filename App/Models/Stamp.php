<?php

namespace App\Models;

use PDO;


class Stamp extends \Core\Model
{
    /**
     * Get all stamps
     *
     * @return array
     */
    public static function getAll()
    {
        $db = static::getDB();
        $stmt = $db->query("SELECT * FROM stamp
                            JOIN category ON st_cat_id = cat_id
                            JOIN stamp_color ON st_id = sc_st_id
                            JOIN color ON sc_color_id = color_id
                            JOIN auction ON st_au_id = au_id
                            WHERE st_active = 1");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get 4 stamps
     *
     * @return array
     */
    public static function get4()
    {
        $db = static::getDB();
        $stmt = $db->query("SELECT * FROM stamp
                            JOIN stamp_color ON st_id = sc_st_id
                            JOIN color ON sc_color_id = color_id
                            JOIN auction ON st_au_id = au_id
                            WHERE st_active = 1
                            LIMIT 4");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * lister un timbre
     * @param int $st_id
     * @return array
     */
    public static function getOne($st_id)
    {
        $db = static::getDB();
        $stmt = $db->prepare("SELECT * FROM stamp 
                              JOIN stamp_color ON st_id = sc_st_id
                              JOIN color ON sc_color_id = color_id
                              JOIN auction ON st_au_id = au_id
                              WHERE st_id = :st_id");
        $stmt->bindParam(':st_id', $st_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * get the id of the last stamp available
     * @return int $st_id of the last element in the table
     */
    public static function getLast()
    {
        $db = static::getDB();
        $stmt = $db->prepare("SELECT * FROM stamp 
                              ORDER BY st_id DESC
                              LIMIT 1");
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Get all stamps of one user
     * @param int $user_id
     * @return array
     */
    public static function getByUser($user_id)
    {
        $db = static::getDB();
        $stmt = $db->prepare("SELECT * FROM stamp 
                              JOIN stamp_color ON st_id = sc_st_id
                              JOIN color ON sc_color_id = color_id
                              JOIN auction ON st_au_id = au_id
                              WHERE au_user_id = :user_id");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * ajouter un timbre
     * @param array $data
     * @return boolean true si suppression effectuée, false sinon
     */

    public static function insert($data)
    {
        $db = static::getDB();
        $stmt = $db->prepare("INSERT INTO stamp SET nom = :nom, age = :age");
        $nomsParams = array_keys($data);
        foreach ($nomsParams as $nomParam) $stmt->bindParam(':' . $nomParam, $data[$nomParam]);
        $stmt->execute();

        if ($stmt->rowCount() <= 0)  return false;
        if ($db->lastInsertId() > 0) return $db->lastInsertId();
        return true;
    }

    /**
     * supprimer un timbre
     * @param int $st_id
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
