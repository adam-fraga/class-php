<?php

//Class definti l'utilisateur
class Userpdo
{
    private int $_id;
    public string $_login;
    public string $_email;
    public string $_firstname;
    public string $_lastname;

    public function register($login, $password, $email, $firstname, $lastname)
    {
//            la syntaxe pdo differe de mysqli pour le dsn
//        Attention à la syntaxe DSN
        $dsn = 'mysql:dbname=classes;host=localhost';
        $dbUser = 'root';
        $dbPassword = '';
        $request_Status = NULL;
//        Crypt password
        $password = password_hash($password, CRYPT_BLOWFISH);
//      TRY Essaie de se connecter
//      Le constructeur de PDO qui s'execute lors de l'instanciation de la classe PDO
//      $PDO est la method de connextion qui prend en parametre host/bd/log/pass
        try {
            $PDO = new PDO($dsn, $dbUser, $dbPassword);
        } //        Capture les erreur de la classe PDOexception dans l'objet $e
        catch (PDOException $e) {
//            Affiche l'erreur via la method getmessage de l'objet $e
            echo 'Erreur de connexion:' . $e->getMessage();
        }

        $sql_insert = "INSERT INTO utilisateurs (login, password, email, firstname, lastname) VALUES (?,?,?,?,?)";
//        Prepa requete PDO
        $stmt = $PDO->prepare($sql_insert);
//        Permet de lier les parametres au interrogation present en requete (Attention à l'ordre')
        $stmt->bindParam(1, $login);
        $stmt->bindParam(2, $password);
        $stmt->bindParam(3, $email);
        $stmt->bindParam(4, $firstname);
        $stmt->bindParam(5, $lastname);
//        Execute la requete PDO
        $request_Status = $stmt->execute();
        if ($request_Status = true) {
            $sql_return = "SELECT * FROM utilisateurs WHERE login='$login'";
            $res = $PDO->query($sql_return);
            return $infoUser = $res->fetch(MYSQLI_ASSOC);
        } else {
            return ["Il y a une erreur dans la saisie des informations"];
        }
    }

//    fonction de connexion retourne infos utilisateurs
    public function connect($login, $password): array
    {
        $connexion = NULL;
        $dsn = 'mysql:dbname=classes;host=localhost';
        $dbUser = 'root';
        $dbPass = '';
        $PDO = new PDO($dsn, $dbUser, $dbPass);
        $sql = "SELECT * FROM utilisateurs WHERE login='$login'";
        $res = $PDO->query($sql);
        $infoUser = $res->fetch(PDO::FETCH_ASSOC);
        $passCheck = password_verify($password, $infoUser['password']);
//        Si login et password passé en param correspond à ceux présent en db
        if ($login == $infoUser['login'] && $passCheck == true) {
//            Set les valeur présent en DB dans les attribut de l'objet courant
            $this->_login = $infoUser['id'];
            $this->_login = $infoUser['login'];
            $this->_email = $infoUser['email'];
            $this->_firstname = $infoUser['firstname'];
            $this->_lastname = $infoUser['lastname'];
            $connexion = true;
            return $infoUser;
        } else {
            $connexion = false;
            return ['Info incorrect'];
        }
    }

//Fonction de deconnexion
    public function disconnect(): void
    {
//    Detruit les variable présente dans l'objet courant'
        unset($this->_id);
        unset($this->_login);
        unset($this->_email);
        unset($this->_firstname);
        unset($this->_lastname);
//Redirige sur la page X pour vider le "cache" navigateur
        header('location:connexion.php');
    }

//Fonction supprime utilisateur
    public function delete(): void
    {
        $dsn = 'mysql:dbname=classes;host=localhost';
        $dbUser = 'root';
        $dbPass = '';
//        Essaie de co DB avec PDO ou capture erreur dans objet e de la classe PDO Exception et l'affiche via la methode get message
        try {
            $PDO = new PDO($dsn, $dbUser, $dbPass);
        } catch (PDOException $e) {
            echo 'Error:' . $e->getMessage();
        }
//        Recherche utilisateur dans db via login de l'objet en cours
        $sql = "DELETE FROM utilisateurs WHERE login = '$this->_login'";
        $stmt = $PDO->exec($sql);
//Supprime les variable dans les attribut de la classe en cours
        unset($this->_id);
        unset($this->_login);
        unset($this->_email);
        unset($this->_firstname);
        unset($this->_lastname);
    }

//    Funtion up info DB depuis login stocké dans l'objet courant'
    public function update($login, $password, $email, $firstname, $lastname)
    {
        $dsn = 'mysql:dbname=classes;host=localhost';
        $dbUser = 'root';
        $dbPass = '';
//        Essaie de se co a la DBO via PDO
        try {
            $PDO = new PDO($dsn, $dbUser, $dbPass);
//            Capture erreur et l'affiche dans le cas ou co error'
        } catch (PDOException $e) {
            echo 'Error:' . $e->getMessage();
        }
//       Prepa requete PDO
        $sql = "UPDATE  utilisateurs SET login=?,password=?,email=?,firstname=?,lastname=? WHERE login='$this->_login'";
        $stmt = $PDO->prepare($sql);
//        Crypt novueau password
        $password = password_hash($password, CRYPT_BLOWFISH);
//        Lie les param bind au ? de la requête
        $stmt->bindParam(1, $login);
        $stmt->bindParam(2, $password);
        $stmt->bindParam(3, $email);
        $stmt->bindParam(4, $firstname);
        $stmt->bindParam(5, $lastname);
//        Execute la requete
        $stmt->execute();
    }

//    Retourne un boléen si tout les attribut de la classe user sont rempli
    public function isConnected(): bool
    {
        $isConnected = NULL;
//        Si tout les attribut de l'objet courant sont remplit'
        if (isset($this->_login) && isset($this->_email) && isset($this->_firstname) && isset($this->_lastname)) {
            $isConnected = true;
        } else {
            $isConnected = false;
        }
        return $isConnected;
    }

    public function getAllInfos(): array
    {
        $userInfo = [
            'CurrentUser_login' => $this->_login,
            'CurrentUser_email' => $this->_email,
            'CurrentUser_firstname' => $this->_firstname,
            'CurrentUser_lastname' => $this->_lastname
        ];
        return $userInfo;
    }

    //  (getter)  Si utilisateur connecté renvoi son login
    public function getLogin(): string
    {
        if ($this->isConnected() == true) {
            return $this->_login;
        } else {
            return false;
        }
    }

    //  (getter)  Si utilisateur connecté renvoi son email
    public function getEmail(): string
    {
        if ($this->isConnected() == true) {
            return $this->_email;
        } else {
            return false;
        }
    }

//  (getter)  Si utilisateur connecté renvoi son prénom

    public function firstName(): string
    {
        if ($this->isConnected() == true) {
            return $this->_firstname;
        } else {
            return false;
        }
    }

//  (getter)  Si utilisateur connecté renvoi son nom

    public function lastName(): string
    {
        if ($this->isConnected() == true) {
            return $this->_lastname;
        } else {
            return false;
        }
    }

//        Actualise attribut de la classe par rapport à DB
    public function refresh():void
    {
        $dsn = 'mysql:dbname=classes;host=localhost';
        $dbUser = 'root';
        $dbPass = '';
//        Connexion PDO
        try {
            $PDO = new PDO($dsn, $dbUser, $dbPass);
        } catch (PDOException $e) {
            echo 'Erreur:' . $e->getMessage();
        }
//        Requete selection sur login car unique
        $sql = "SELECT * FROM utilisateurs WHERE login='$this->_login'";
//        Requete non preparé car lecture de db
        $stmt = $PDO->query($sql);
//        Recup sous forme de tab assoc depuis db les infos user
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
//        Actualisatio ndes attribut de l'objet courant'
        $this->_login = $res['login'];
        $this->_email = $res['email'];
        $this->_firstname = $res['firstname'];
        $this->_lastname = $res['lastname'];
    }

}

