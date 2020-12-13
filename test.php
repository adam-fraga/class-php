<?php

//Par convention class = 1ere lettre Mauscule
class Personnage
{
//    Par convention attribut d'une classe toujours privé
    private int $_hp = 150;
    private int $_mana = 100;

    public int $_stamina = 50;
    public int $_agility = 40;
    public int $_intelligence = 80;
    public int $_strength = 80;


// Un attribut ou une methode 'public' est accessible en dehors de la classe contrairement à 'private'
    public function showCarac()
    {
        // $this represente l'objet on utilise l'operateur -> pour appeler un attribut ou une methode de l'objet
        // ($this est l'appelation d'un objet que l'on a pas encore instancié et qui sert à definir son comportement)

        // dans le cas de plusieurs objets $this est une variable représentant l'objet à partir duquel
        // on a appelé la méthode ou l'attribut.
        echo "Point de vie: $this->_hp </br> ";
        echo "Point de Mana: $this->_mana </br>";
        echo "Endurance: $this->_stamina </br>";
        echo "Agilité: $this->_agility </br>";
        echo "Intelligence: $this->_intelligence </br>";
        echo "Force: $this->_strength </br>";
    }
//        Si la methode prends en param un Objet il faut stipuler qu'il s'agit d'un objet
//         en notifiant le type d'objet avant le parametre de la methode
    public function frapper(Personnage $personnage)
    {

    }

    public function move()
    {
        echo "Le personnage se déplace";
    }
}

$Kaiser = new Personnage();

$Kaiser->showCarac();
$Kaiser->atack();


?>
