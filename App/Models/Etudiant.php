<?php

namespace App\Models;

use PDO;


class Etudiant extends \Core\Model
{

    /**
     * Get all the users as an associative array
     *
     * @return array
     */
    public static function getAll()
    {
        $db = static::getDB();
        $stmt = $db->query('SELECT * FROM etudiant');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    /**
     * Get one user as an associative array
     *
     * @return array
     */
    public static function getOne($id_etudiant)
    {
        $db = static::getDB();
        $stmt = $db->prepare('SELECT * FROM etudiant WHERE id_etudiant = :id_etudiant');
        $stmt->bindParam(':id_etudiant', $id_etudiant);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Ajouter un etudiant
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
     * Supprimer un etudiant
    * @param int $id_etudiant clé primaire
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
