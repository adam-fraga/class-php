<?php

class User
{
    private $id;
    public $login;
    public $email;
    public $firstname;
    public $lastname;


//Methode enregistre utilisateur en DB et recup ses données dans un tableau prend en param info utilisateur
    public function register($login, $password, $email, $firstname, $lastname) : array
    {

        $mysqli = new mysqli("localhost", "root", "", "classes");
        if ($mysqli->connect_errno) {
            echo "Echec lors de la connexion à MySQL : " . $mysqli->connect_error;
        }
        //CONNEXIONDB

        //Initialisation des variables de requetes
        $password = password_hash($password,CRYPT_BLOWFISH);
        $sql_insert_user = "INSERT INTO utilisateurs (login , password , email , firstname , lastname) VALUES ('$login','$password','$email','$firstname','$lastname')";
        $sql_fetch_user = "SELECT * FROM utilisateurs WHERE login = '$login'";

        //Reqête d'insertion base de donnée
        $mysqli->query($sql_insert_user);
        //Requête de recup info user base de donnée
        $result = $mysqli->query($sql_fetch_user);
        $data = $result->fetch_assoc();

        return $data;
    }

    public function connect($login,$password) :array
    {


    }
}







