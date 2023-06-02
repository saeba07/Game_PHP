<?php

session_start();

// Classe de personnage
class Character {
    public $name;
    public $type;
    public $health;
    public $attack;
    public $defense;
    public $sleepTimer;

    public function __construct($name, $type) {
        $this->name = $name;
        $this->type = $type;
        $this->health = 100;

        if ($type == 'guerrier') {
            $this->attack = rand(20, 40);
            $this->defense = rand(10, 19);
        } elseif ($type == 'magicien') {
            $this->attack = rand(5, 10);
            $this->defense = 0;
            $this->sleepTimer = time();
        }
    }

    public function attack($target) {
        if ($this->type == 'guerrier') {
            $damage = $this->attack - $target->defense;
            $target->health -= $damage;
            echo $this->name . ' attaque ' . $target->name . ' et lui inflige ' . $damage . ' points de dégâts.<br>';
        } elseif ($this->type == 'magicien') {
            if (time() - $this->sleepTimer >= 120) {
                $target->sleepTimer = time() + 15;
                echo $this->name . ' endort ' . $target->name . ' pendant 15 secondes.<br>';
            } else {
                echo $this->name . ' ne peut pas endormir un joueur pour le moment.<br>';
            }
        }
    }
}

// Vérifier si le formulaire a été soumis
if (isset($_POST['name']) && isset($_POST['type'])) {
    // Créer un nouveau personnage
    $name = $_POST['name'];
    $type = $_POST['type'];

    // Créer un objet Character
    $character = new Character($name, $type);

    // Enregistrer le personnage dans la session
    $_SESSION['character'] = $character;
}

// Vérifier si le personnage est enregistré dans la session
if (isset($_SESSION['character'])) {
    $character = $_SESSION['character'];

    // Afficher les informations du personnage
    echo 'Nom du personnage : ' . $character->name . '<br>';
    echo 'Type du personnage : ' . $character->type . '<br>';
    echo 'Points de vie : ' . $character->health . '<br>';
    echo 'Attaque : ' . $character->attack . '<br>';
    echo 'Défense : ' . $character->defense . '<br>';

    // Effectuer une attaque
    $enemy = new Character('Ennemi', 'guerrier');
    $character->attack($enemy);
    echo 'Points de vie restants de l\'ennemi : ' . $enemy->health . '<br>';
}

?>

<!DOCTYPE html>
<html>
<head>
  <title>Jeu de combat</title>
</head>
<body>
  <h1>Jeu de combat</h1>
  <form action="create_character.php" method="post">
    <label for="name">Nom :</label>
    <input type="text" name="name" required><br>

    <label for="type">Type :</label>
    <select name="type" required>
      <option value="guerrier">Guerrier</option>
      <option value="magicien">Magicien</option>
    </select><br>

    <input type="submit" value="Créer">
  </form>
</body>
</html>
