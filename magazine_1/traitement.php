<?php
// Chemin du fichier JSON
$file_name = 'data.json';

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer le titre du formulaire
    $title = isset($_POST['titre']) ? $_POST['titre'] : '';

    // Charger les données existantes depuis le fichier JSON s'il existe
    $data0 = [];
    if (file_exists($file_name)) {
        $json_data_0 = file_get_contents($file_name);
        $data0 = json_decode($json_data_0, true);
        // Vérifier si les données sont null
        if ($data0 === null) {
            $data0 = [];
        }
    }

    // Générer un nom de variable unique pour chaque donnée
    $variable_name = 'titre_' . (count($data0) + 1);

    // Ajouter les données du titre au tableau de données
    $data0[$variable_name] = $title;

    // Convertir les données en format JSON
    $json_data_0 = json_encode($data_0, JSON_PRETTY_PRINT);

    // Écrire les données JSON dans un fichier
    file_put_contents($file_name, $json_data_0);

    // Rediriger l'utilisateur vers la page du formulaire
    header("Location: index.php");
    exit;
}
?>