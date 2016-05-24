<?php
/**
 * @author: galicher
 * Date: 22/03/16
 * Time: 21:42
 */

namespace Aonyx\Classes;

class DbManager
{
    private $db;

    // Pousser les données en base de données
    public function insert($table, $values, $execute)
    {
        $requete = $this->db->prepare('INSERT INTO ' . $table . ' VALUES(' . $values . ')');
        $requete->execute($execute);

        return $requete;
    }

    // Updater des données
    public function update($table, $set, $where, $execute)
    {
        $requete = $this->db->prepare('UPDATE ' . $table . ' SET ' . $set . ' WHERE ' . $where .'');
        $requete->execute($execute);

        return $requete;
    }

    // Delete des données
    public function delete($table, $where)
    {
        return $this->db->exec('DELETE FROM ' . $table . ' WHERE ' . $where . '');
    }

    // Recupérer une donnée
    public function fetch($select, $from, $where, $execute, $fetchMode)
    {
        $requete = $this->db->prepare('SELECT ' . $select . ' FROM ' . $from . ' WHERE ' . $where . '');
        $requete->execute($execute);

        $requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, $fetchMode);

        return $requete->fetch();
    }

    //Jointure
    public function leftJoin($select, $from, $on, $where = null, $execute, $fetchMode)
    {
        $requete = $this->db->prepare('SELECT ' . $select . ' FROM ' . $from . ' ON ' . $on . ' ' . (null != $where ? 'WHERE ' . $where : null));
        $requete->execute($execute);

        $requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, $fetchMode);

        return $requete->fetch();
    }

    // Récupérer plusieurs données
    public function fetchAll($select, $from, $params = null, $start = null, $limit = null, $fetchMode)
    {
        $sql = 'SELECT ' . $select . ' FROM ' . $from . ' ' . $params;

        if(null != $start && null != $limit)
        {
            // On vérifie l'intégrité des paramètres fournis.
            if ($start != -1 || $limit != -1)
            {
                $sql .= ' LIMIT ' . (int) $limit . ' OFFSET ' . (int) $start;
            }
        }

        $requete = $this->db->query($sql);
        $requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, $fetchMode);

        return $requete->fetchAll();
    }

    // Compter un nombre d'entrées
    public function count($select, $from)
    {
        return $this->db->query('SELECT COUNT(' . $select . ') FROM ' . $from)->fetchColumn();
    }

    public function getDb()
    {
        return $this->db;
    }

    public function setDb(\PDO $db)
    {
        $this->db = $db;
        return $this;
    }
}