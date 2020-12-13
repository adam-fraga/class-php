<?php

// Par convention une classe commencera toujours par une majuscule

//Nouveaté PHPstorm 2020.3
//Signalez les fonctions qui ne produisent aucun effet indésirable comme #[Pure] pour améliorer l'analyse du flux de code dans PhpStorm. L'IDE mettra en évidence les appels redondants de fonctions pures.
use JetBrains\PhpStorm\Pure;

class User
{
    //par convention les attribut sont privé et on les récupere et modifie à l'aide methodes (getter et setter)
    //par convention un attribut commencera toujours par underscore.
    //par convention les method getter et setter porte le même nom que l'attribut renvoyé ou modifier avec prefixe 'get' ou 'set'
    private int $_id;
    public string $_login;
    public string $_email;
    public string $_firstname;
    public string $_lastname;

//Methode enregistre utilisateur en DB et recup ses données dans un tableau prend en param info utilisateur (MYSQLI OBJET)
    public function register($login, $password, $email, $firstname, $lastname): array
    {
        //CONNEXIONDB

        $mysqli = new mysqli("localhost", "root", "", "classes");

        if ($mysqli->connect_errno) {
            echo "Echec lors de la connexion à MySQL : " . $mysqli->connect_error;
        }

        //Initialisation des variables de requetes
        $password = password_hash($password, CRYPT_BLOWFISH);
        $sql_insert_user = "INSERT INTO classes.utilisateurs (login , password , email , firstname , lastname) VALUES ('$login','$password','$email','$firstname','$lastname')";
        $sql_fetch_user = "SELECT * FROM utilisateurs WHERE login = '$login'";
        //Reqête d'insertion base de donnée
        $mysqli->query($sql_insert_user);
        //Requête de recup info user base de donnée
        $result = $mysqli->query($sql_fetch_user);
        $data = $result->fetch_assoc();


        return $data;
    }

//CONNECTE L'UTILISATEUR (MYSQLI PROCEDURALE)
    public function connect($login, $password): array
    {
        //CONNEXION
        $mysqli = new mysqli("localhost", "root", "", "classes");
        if ($mysqli->connect_errno) {
            echo "Echec lors de la connexion à MySQL : " . $mysqli->connect_error;
        }
        $login = htmlspecialchars($login);
//        Selectionne info useur grace à son login
        $fetchUserInfos = "SELECT * FROM utilisateurs WHERE login = '$login'";

//        Requete  mysqli procedural recup info from db
        $res = mysqli_query($mysqli, $fetchUserInfos);
        // Stoch tableau associatif contenant données user dans variabe data
        $data = mysqli_fetch_assoc($res);
//        Si login strictement égal a login dans DB et Pass = pass decrypté depuis DB
        if ($login == $data['login'] && password_verify($password, $data['password']) == true) {

//            Modifie attributs de la class User pour l'objet courant
            $this->_id = $data['id'];
            $this->_login = $data['login'];
            $this->_email = $data['email'];
            $this->_firstname = $data['firstname'];
            $this->_lastname = $data['lastname'];

            // Retourne tableau associatif de l'utilisateur
            return $data;
            // Sinon retourne chaine de carac sous forme de tableau (Ai déclaré fonction retournant tableau)
        } else {
            return ["Mauvais identifiant"];
        }
    }


    public function disconnect(): void // Void = ne retourne rien
    {
//            Vide les propriétés (attributs) de l'objet courant
        unset($this->_id);
        unset($this->_login);
        unset($this->_email);
        unset($this->_firstname);
        unset($this->_lastname);
//            Redirige sur page de connexion (permet d'actualiser la page)
        header("location: connexion.php");
    }

//    Suppriem utilisateur et le deconnecte (MYSQLI PROCEDURAL)
    public function delete(): void
    {
        //CONNEXION
        $mysqli = new mysqli("localhost", "root", "", "classes");
        if ($mysqli->connect_errno) {
            echo "Echec lors de la connexion à MySQL : " . $mysqli->connect_error;
        }
//        ini requete suppression DB
        $sql_delete = "DELETE FROM utilisateurs WHERE login='$this->_login'";
//        Execution de la requete de supression en base de donnée
        mysqli_query($mysqli, $sql_delete);
//        Detruit les variable de l'objet en cours
        unset($this->_id);
        unset($this->_login);
        unset($this->_email);
        unset($this->_firstname);
        unset($this->_lastname);
    }

//    Fonction de modification donné utilisateurs dans la db
    public function update($login, $password, $email, $firstname, $lastname): void
    {
//        Crypt password
        $password = password_hash($password, CRYPT_BLOWFISH);
        //connexion DB
        $mysqli = new mysqli("localhost", "root", "", "classes");
        if ($mysqli->connect_errno) {
            echo "Echec lors de la connexion à MySQL : " . $mysqli->connect_error;
        }
//        Ini requete ?=en attente des valeur attention à les bind dans l'ordre
        $sql_insert = "UPDATE utilisateurs SET login=?,password=?,email=?,firstname=?,lastname=? WHERE login='$this->_login'";
//      prepare requete
        $res = $mysqli->prepare($sql_insert);
        // bind_param permet de lier les parametre a la requete
        $res->bind_param('sssss', $login, $password, $email, $firstname, $lastname);
        // execution requete
        $res->execute();
    }

//    fonction test status de connexion
    public function isConnected(): bool
    {
//        Definit bool NULL
        $isConnected = NULL;
//         si attribut de mon objet different de vide
        if (!empty($this->_id) && !empty($this->_login) && !empty($this->_name) && !empty($this->_lastname)) {
//           sinon return bool true
            $isConnected = true;
            return $isConnected;
        } else {
//            sinon return bool false
            $isConnected = false;
            return $isConnected;
        }
    }

//        retourne tableau associatif des infos de l'objet courant'
    public function getAllInfos(): array
    {
        return $allinfo = [
            'id' => $this->_id,
            'login' => $this->_login,
            'email' => $this->_email,
            'firstname' => $this->_firstname,
            'lastname' => $this->_lastname];
    }

//  (getter)  Si utilisateur connecté renvoi son login
    public function getLogin(): string
    {
        if ($this->isConnected() == true) {
            return $this->_login;
        }
    }

    //  (getter)  Si utilisateur connecté renvoi son email
    public function getEmail(): string
    {
        if ($this->isConnected() == true) {
            return $this->_email;
        }
    }

//  (getter)  Si utilisateur connecté renvoi son prénom

    public function firstName(): string
    {
        if ($this->isConnected() == true) {
            return $this->_firstname;
        }
    }

//  (getter)  Si utilisateur connecté renvoi son nom

    public function lastName(): string
    {
        if ($this->isConnected() == true) {
            return $this->_lastname;
        }
    }

    //  Actualise les attribut de la classe depuis DB
    public function refresh(): void
    {
        $mysqli = new mysqli('localhost', 'root', '', 'classes');
        if ($mysqli->connect_errno) {
            echo "Echec lors de la connexion à MySQL : " . $mysqli->connect_error;
        }
//        Requete recup info from DB
        $sql = "SELECT * FROM utilisateurs WHERE login='$this->_login'";
        $stmt = $mysqli->query($sql);
        $infoUser = $stmt->fetch_assoc();
        $this->_login = $infoUser['login'];
        $this->_login = $infoUser['email'];
        $this->_login = $infoUser['firstname'];
        $this->_login = $infoUser['lastname'];
    }
}
