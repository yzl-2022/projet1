<?php

namespace App\Models;

use PDO;


class Auction extends \Core\Model
{

    /**
     * Get 4 auctions --> diff by date?
     *
     * @return array
     */
    public static function getAll()
    {
        $db = static::getDB();
        $stmt = $db->query("SELECT * FROM auction
                            JOIN stamp ON au_id = st_au_id
                            GROUP BY au_id
                            LIMIT 4");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    /**
     * Get one auction
     * @param int $au_id
     * @return array
     */
    public static function getOne($au_id)
    {
        $db = static::getDB();
        $stmt = $db->prepare("SELECT * FROM auction
                              WHERE au_id = :au_id");
        $stmt->bindParam(':au_id', $au_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Create an auction
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
