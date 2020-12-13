<?php

//lpdo est une classe simplifié de pdo qui utilise les fonctions mysqli
class lpdo
{
    private $_mysqli; //Variable de connexion mysqli
    private $_lastQuery; // Derniere requete passé
    private $_lastResult; //Resultat de la denire requete

    public function __construct($host, $username, $password, $db)
    {
//        connect à la DB via mysqli
        $mysqli = mysqli_connect($host, $username, $password, $db);
//        renvoi une erreur si connexion fail
        if (!$mysqli) {
            echo 'Erreur:' . mysqli_connect_error();
        }
        //Sinon place la variable de connexion dans l'attribut privé de la classe lpdo'
        // Et definit l'attribut de connexion de l'objet courant à True
        else {
            $this->_mysqli = $mysqli;
        }
    }

    public function connect($host, $username, $password, $db)
    {
//        Si le resultat de la method de connexion de mysqly renvoi autre chose que false
        if (!$this->_mysqli == false) {
//            ferme la connexion àa la base de donné
            mysqli_close($this->_mysqli);
        }
//        Réenclenche une connexion via les parametre passé à la method puis
// stock de nouveau dans l'attribut de l'objet courant la variable de connexion
        $mysqli = mysqli_connect($host, $username, $password, $db);
        $this->_mysqli = $mysqli;
    }

//    Destructeur ferme la connexion à la fin du script
    public function __destruct()
    {
//        ferme la connexion de l'objet courant'
        mysqli_close($this->_mysqli);
//        Vide l'attribut variable de connexion de l'objet courant
        unset($this->_mysqli);
    }

//    Ferme la conenxion au serveur
    public function close()
    {
//        ferme la connexion a la DB
        mysqli_close($this->_mysqli);
    }

//    Execute la requete de l'utilisateur et retourne un tableau'
    public function execute($query): array
    {  // echape query pour eviter mauvaise intention user
        $safeQuery = htmlspecialchars($query);
//        execute requete mysql
        $stmt = mysqli_query($this->_mysqli, $safeQuery);
//        Creation de l'attribut lastquery qu contient la requete passé'
        $this->_lastQuery = $safeQuery;
//        return tab associatif de la requete passé en param
        $result = mysqli_fetch_assoc($stmt);
//        modifie l'attribut de resultat de l'objet
        $this->_lastResult = $result;
        return $result;
    }

//    Retourne la dernierer requete passé
    public function getLastQuey(): string
    {
        if ($this->_lastQuery) {
            return $this->_lastQuery;
        } else {
            return false;
        }
    }

//    Retourne derniere requete passé
    public function getLastResult(): array
    {
        if (!$this->_lastResult) {
            return false;
        } else return $this->_lastResult;
    }

//retourne la liste des tables présente dans la db
    public function getTables(): array
    {
        $sql = "SHOW TABLES";
        $stmt = mysqli_query($this->_mysqli, $sql);
        return $stmt->fetch_assoc();
    }
//Retourne la liste des champs présent dans la table passé en param + ses informations
    public function getFields($table):array
    {
        $safeTable = htmlspecialchars($table);
        $sql = "SHOW COLUMNS FROM classes.$table";
        $stmt = mysqli_query($this->_mysqli, $sql);
       return $result = $stmt->fetch_all(MYSQLI_ASSOC);

    }
}