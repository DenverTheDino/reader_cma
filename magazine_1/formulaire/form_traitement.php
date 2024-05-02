<?php
    // Fonction pour traiter le texte du formulaire
    function traiterTexte() {
        if(isset($_POST["texte"])) {
            $texte = $_POST["texte"];
            // Vous pouvez traiter le texte ici
            return $texte;
        } else {
            return null;
        }
    }

    // Fonction pour traiter le fichier PDF téléchargé
    function traiterPDF() {
        if(isset($_FILES["pdf"]) && $_FILES["pdf"]["error"] == 0) {
            $destination = "formulaire/pdf/" . basename($_FILES["pdf"]["name"]);
            if(move_uploaded_file($_FILES["pdf"]["tmp_name"], $destination)) {
                return $destination;
            } else {
                return "Erreur lors du téléchargement du fichier PDF.";
            }
        } else {
            return "Aucun fichier PDF n'a été téléchargé.";
        }
    }

    // Traitement des données du formulaire
    $texte = traiterTexte();
    $cheminPDF = traiterPDF();

    // Affichage des résultats ou redirection
    if($texte !== null && $cheminPDF !== null) {
        // Afficher ou utiliser les données traitées
        echo "Le texte du formulaire : " . $texte . "<br>";
        echo "Chemin du fichier PDF : " . $cheminPDF;
    } else {
        // Redirection ou affichage d'un message d'erreur
        echo "Erreur : Les données du formulaire sont incomplètes.";
    }
    ?>
