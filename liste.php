<?php

// Appel de la Base de Données

include "inclusions/database.php";

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Lien Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    <!-- Lien Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <!-- Lien Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <!-- Lien CSS -->
    <link rel="stylesheet" href="listeStyle.css">
    <title>Liste des Annonces</title>
</head>

<?php

include "inclusions/header.php";

?>

<body>

    <!-- Tableau accueillant les données de la bd -->
    <table class="table">
        <thead>
            <tr>
                <th scope="col">Annonce n°</th>
                <th scope="col">Titre</th>
                <th scope="col">Adresse</th>
                <th scope="col">Ville</th>
                <th scope="col">Code Postal</th>
                <th scope="col">Surface (m²)</th>
                <th scope="col">Prix (€)</th>
                <th scope="col">Photo(s)</th>
                <th scope="col">Type</th>
                <th scope="col">Description</th>
            </tr>
        </thead>

        <?php

        // Faire apparaitre les annonces de la bd sous forme de tableau

        // Requête SQL pour selectionner les logements

        $sql = "SELECT * FROM logement";
        $resultat = $bdd->query($sql);

        // Faire apparaitre les infos des logements dans chaque ligne

        if (isset($resultat)) {
            while ($row = $resultat->fetch()) {
        ?>

                <tbody>
                    <tr>
                        <th><?= $row['id_logement']; ?></th>
                        <td><a href="ficheLogement.php?id=<?= $row['id_logement']; ?>" target="_blank"><?= $row['titre']; ?></a></td>
                        <td><?= $row['adresse']; ?></td>
                        <td><?= $row['ville']; ?></td>
                        <td><?= $row['cp']; ?></td>
                        <td><?= $row['surface']; ?></td>
                        <td><?= $row['prix']; ?></td>
                        <td><img src="<?= $row['photo'] ?>" alt="photoLogement"></td>
                        <td><?= $row['type']; ?></td>
                        <td><?= substr($row['description'], 0, 15) . '...' ?>
                            <!-- substr pour couper le texte affiché quand trop long-->
                        </td>
                    </tr>
                </tbody>
        <?php

            }
        }

        ?>

    </table>

    <!-- Retour à l'accueil -->
    <a class="btn btn-primary" href="form.php">Retour à l'accueil</a>

</body>

<?php

include "inclusions/footer.php";

?>

</html>