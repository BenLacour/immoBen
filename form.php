<?php

// Appel de la Base de Données

include "inclusions/database.php";

// Récupération des inputs du form pour ajouter une annonce à la base de données

// Textarea "description" optionnel

$description = NULL;

// Var $photoApt NULL pour préparer l'upload de la photo optionnelle
$photoApt = NULL;

// Vérification des champs à remplir 

if (
    isset($_POST["titre"]) && isset($_POST["adresse"]) && isset($_POST["ville"]) && isset($_POST["codePostal"]) && isset($_POST["surface"])
    && isset($_POST["prix"]) && isset($_POST["type"])
    && $_POST["titre"] != "" && $_POST["adresse"] != "" && $_POST["ville"] != "" && $_POST["codePostal"] != "" && $_POST["surface"] != ""
    && $_POST["prix"] != "" && $_POST["type"] != ""
) {

    // Champ "description" optionnel

    $description = NULL;

    if (isset($_POST["description"]) && $_POST["description"] != "") {
        $description = htmlspecialchars($_POST["description"]);
    }

    // Vérification du Code Postal
    if (preg_match('#^((0[1-9])|([1-8][0-9])|(9[0-8])|(2A)|(2B))[0-9]{3}$#', $_POST["codePostal"])) {
        $codePostal = htmlspecialchars($_POST["codePostal"]);
    } else {
        $message = '<div class = "alert alert-warning mt-3">Code Postal erroné</div>';
    }

    // Input concernant l'upload de la photo

    $photoApt = NULL;

    // Vérification que la photo existe et a été stockée dans le TEMP

    if (isset($_FILES["photo"]) && $_FILES["photo"]["error"] == 0) {

        $caracsPhoto = pathinfo($_FILES["photo"]["name"]);
        $extensionPhoto = $caracsPhoto["extension"];
        $nomPhoto = $caracsPhoto["filename"];
        $extensionsTableau = array("jpg", "jpeg", "png");

        if ($_FILES["photo"]["size"] <= 2000000) { // l'image doit faire moins de 2mégas
            $photoApt = "photos/" . $nomPhoto . "_" . time() . "." . $extensionPhoto;

            $temp = $_FILES["photo"]["tmp_name"];


            // Création du dossier temp pour l'upload d'image
            if (!is_dir("photos")) {
                mkdir("photos", 0755, true);
            }

            // Déplacement de la photo dans le dossier créé
            move_uploaded_file($temp, $photoApt);
        }
    }



    // Création requête SQL pour ajouter l'élément à la base de données

    $sql = "INSERT INTO logement(titre, adresse, ville, cp, surface, prix, type, photo, description) ";
    $sql .= "VALUES(:titre, :adresse, :ville, :cp, :surface, :prix, :type, :photo, :description)";

    // Préparer la bd à la réception de ces informations

    $res = $bdd->prepare($sql);

    // Boucle if pour notifier l'utilisateur de la réussite de l'upload
    if ($res->execute(array(
        "titre" => htmlspecialchars($_POST["titre"]),
        "adresse" => htmlspecialchars($_POST["adresse"]),
        "ville" => htmlspecialchars($_POST["ville"]),
        "cp" => $codePostal,
        "surface" => (int) htmlspecialchars($_POST["surface"]), // vérification que c'est bien un entier
        "prix" => (int) htmlspecialchars($_POST["prix"]), // vérification que c'est bien un entier
        "type" => htmlspecialchars($_POST["type"]),
        "photo" => $photoApt,
        "description" => $description
    ))) {
        // Success
        $message = '<div class = "alert alert-success mt-3">Annonce ajoutée au site</div>';
    }
}


?>

<!DOCTYPE html>
<html lang="fr">

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
    <link rel="stylesheet" href="formStyle.css">
    <title>Immo'Ben</title>
</head>

<body>

    <?php

    include "inclusions/header.php";

    ?>

    <main>
        <!-- Formulaire -->
        <form action="form.php" method="post" enctype="multipart/form-data">
            <div class="mb-3">

                <!-- Titre de l'annonce -->
                <label for="titre" class="form-label mt-3">Titre de l'annonce :</label>
                <input type="text" class="form-control" id="titre" name="titre" required>

                <!-- Adresse du bien -->
                <label for="adresse" class="form-label mt-3">Adresse du bien :</label>
                <input type="text" class="form-control" id="adresse" name="adresse" required>

                <!-- Ville -->
                <label for="ville" class="form-label mt-3">Ville :</label>
                <input type="text" class="form-control" id="ville" name="ville" required>

                <!-- Code Postal -->
                <label for="codePostal" class="form-label mt-3">Code Postal :</label>
                <input type="number" class="form-control" id="codePostal" name="codePostal" required>

                <!-- Surface -->
                <label for="surface" class="form-label mt-3">Surface (en m²) :</label>
                <input type="number" class="form-control" id="surface" name="surface" required>

                <!-- Prix -->
                <label for="prix" class="form-label mt-3">Prix (en €) :</label>
                <input type="number" class="form-control" id="prix" name="prix" required> <br>

                <!-- Type de bien : vente ou location -->
                <label for="type">Type de contrat :</label>
                <select class="form-select" name="type" id="type" required>
                    <option selected disabled>Veuillez choisir le type de contrat :</option>
                    <option value="Vente">Vente</option>
                    <option value="Location">Location</option>
                </select> <br>

                <!-- Upload photo -->
                <div class="mb-3">
                    <label for="photo" class="form-label">Ajouter une (ou des) photo(s) :</label>
                    <input class="form-control" type="file" id="photo" name="photo">
                </div>

                <!-- Desciption optionnelle -->
                <label for="description">Détaillez votre annonce :</label> <br>
                <textarea name="description" id="description"></textarea> <br>

                <button type="submit" class="btn btn-success mt-3">Valider</button>

            </div>
        </form>

        <!-- Alert pop up -->
        <?= $message ?? '' ?>

    </main>

    <h2>Plus de 10 annonces ajoutées chaque seconde</h2>
    <a href="liste.php" class="btn btn-warning">Voir les annonces</a>

</body>

<?php

include "inclusions/footer.php";

?>

</html>