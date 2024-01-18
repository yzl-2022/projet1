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
                            JOIN photo on st_id = photo_st_id
                            JOIN category ON st_cat_id = cat_id
                            JOIN auction ON st_au_id = au_id
                            WHERE photo_principal = 1");
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
                            JOIN photo on st_id = photo_st_id
                            JOIN auction ON st_au_id = au_id
                            WHERE photo_principal = 1
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
                              JOIN photo on st_id = photo_st_id
                              JOIN auction ON st_au_id = au_id
                              WHERE st_id = :st_id
                              AND photo_principal = 1");
        $stmt->bindParam(':st_id', $st_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * lister toutes images supplémentaires d'un timbre
     * @param int $st_id
     * @return array
     */
    public static function getPhoto($st_id)
    {
        $db = static::getDB();
        $stmt = $db->prepare("SELECT * FROM photo 
                              WHERE photo_st_id = :st_id
                              AND photo_principal = 0");
        $stmt->bindParam(':st_id', $st_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
                              JOIN photo on st_id = photo_st_id
                              JOIN auction ON st_au_id = au_id
                              WHERE au_user_id = :user_id
                              AND photo_principal = 1");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get all categories of stamps
     * 
     * @return array
     */
    public static function getCategories()
    {
        $db = static::getDB();
        $stmt = $db->query("SELECT * FROM category");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * ajouter un timbre
     * @param array $data
     * @return boolean true si insertion effectuée, false sinon
     */

    public static function insert($data)
    {
        $db = static::getDB();
        $stmt = $db->prepare("INSERT INTO stamp SET st_au_id = :st_au_id,
                                                    st_condition = :st_condition,
                                                    st_width = :st_width,
                                                    st_height = :st_height,
                                                    st_title = :st_title,
                                                    st_description = :st_description,
                                                    st_country = :st_country,
                                                    st_continent = :st_continent,
                                                    st_certifie = :st_certifie,
                                                    st_tirage = :st_tirage,
                                                    st_color = :st_color,
                                                    st_cat_id = :st_cat_id");
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
        $stmt = $db->prepare('DELETE FROM stamp WHERE st_id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        if ($stmt->rowCount() <= 0)  return false;
        return true;
    }

    /**
     * modifier un timbre
     * @param array $data
     * @return boolean
     */
    public static function modifier($data)
    {
        $db = static::getDB();
        $stmt = $db->prepare("UPDATE stamp SET st_au_id = :st_au_id,
                                               st_condition = :st_condition,
                                               st_width = :st_width,
                                               st_height = :st_height,
                                               st_title = :st_title,
                                               st_description = :st_description,
                                               st_country = :st_country,
                                               st_continent = :st_continent,
                                               st_certifie = :st_certifie,
                                               st_tirage = :st_tirage,
                                               st_color = :st_color,
                                               st_cat_id = :st_cat_id
                                          WHERE st_id = :st_id");
        $nomsParams = array_keys($data);
        foreach ($nomsParams as $nomParam) $stmt->bindParam(':' . $nomParam, $data[$nomParam]);
        $stmt->execute();

        if ($stmt->rowCount() <= 0)  return false;
        if ($db->lastInsertId() > 0) return $db->lastInsertId();
        return true;
    }
    
    /**
     * ajouter un photo d'un timbre
     * @param int $st_id
     * @param int $photo_name
     * @param int $principal
     * @return boolean
     */
    public static function insertPhoto($st_id, $photo_name, $principal)
    {
        $db = static::getDB();
        $stmt = $db->prepare('INSERT INTO photo SET photo_st_id = :photo_st_id,
                                                    photo_name = :photo_name,
                                                    photo_principal = :photo_principal');
        $stmt->bindParam(':photo_st_id', $st_id);
        $stmt->bindParam(':photo_name', $photo_name);
        $stmt->bindParam(':photo_principal', $principal);
        $stmt->execute();

        if ($stmt->rowCount() <= 0)  return false;
        if ($db->lastInsertId() > 0) return $db->lastInsertId();
        return true;
    }

    /**
     * supprimer une image supplémentaire d'un timbre
     * @param int $photo_id
     * @return boolean
     */
    public static function deletePhoto($id)
    {
        $db = static::getDB();
        $stmt = $db->prepare('DELETE FROM photo WHERE photo_id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        if ($stmt->rowCount() <= 0)  return false;
        return true;
    }

    /**
     * modifier l'image prinpicale pour un timbre
     * @param int $st_id
     * @param int $photo_name
     * @return boolean
     */
    public static function modifierPhoto($st_id, $photo_name)
    {
        $db = static::getDB();
        $stmt = $db->prepare('UPDATE photo SET photo_name = :photo_name
                                           WHERE photo_st_id = :photo_st_id 
                                           AND photo_principal = 1');
        $stmt->bindParam(':photo_st_id', $st_id);
        $stmt->bindParam(':photo_name', $photo_name);
        $stmt->execute();

        if ($stmt->rowCount() <= 0)  return false;
        if ($db->lastInsertId() > 0) return $db->lastInsertId();
        return true;
    }
}
