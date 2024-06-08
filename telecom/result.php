<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultats du dimensionnement</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            width: 100%;
            text-align: center;
        }
        h1 {
            color: #333;
            font-size: 24px;
            margin-bottom: 20px;
        }
        p {
            color: #555;
            font-size: 18px;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Résultats du dimensionnement</h1>

        <?php
        // Récupération des données du formulaire
        $total_subscribers = $_POST['total_subscribers'];
        $penetration_rate = $_POST['penetration_rate'] / 100; // Convertir le taux de pénétration en décimal
        $traffic_per_subscriber = $_POST['traffic_per_subscriber'];
        $total_frequencies = $_POST['total_frequencies'];
        $reutilization_pattern = $_POST['reutilization_pattern'];
        $it_reserve = $_POST['it_reserve'];
        $taux_blocage = $_POST['taux_blocage'] / 100; // Convertir le taux de blocage en décimal

        // Calcul du trafic total à l'heure de pointe
        $total_traffic = $total_subscribers * $penetration_rate * $traffic_per_subscriber;

        // Calcul du nombre de porteuses par cellule
        $porteuses_par_cellule = $total_frequencies / $reutilization_pattern;

        // Calcul du nombre IT restant
        $it_restant = ($porteuses_par_cellule * 8) - $it_reserve;

        // Calcul du nombre de cellules nécessaires
        $nombre_cellules_necessaires = ceil($total_traffic / $it_restant);

        // Calcul du trafic par cellule
        function calculateOfferedTraffic($it_restant, $taux_blocage) {
            $k = -log10($taux_blocage);
            $a = 1;
            $b = 2 * $k;
            $c = -$it_restant * $it_restant;
            $discriminant = $b * $b - 4 * $a * $c;
            
            // Vérifier si le discriminant est positif pour avoir une solution réelle
            if ($discriminant >= 0) {
                $A1 = (-$b + sqrt($discriminant)) / (2 * $a);
                $A2 = (-$b - sqrt($discriminant)) / (2 * $a);
                
                // Retourner la solution positive
                return $A1 >= 0 ? $A1 : $A2;
            } else {
                return null; // Pas de solution réelle
            }
        }

        $trafic_par_cellule = calculateOfferedTraffic($it_restant, $taux_blocage);

        // Affichage des résultats
        echo "<p>Trafic total à l'heure de pointe : $total_traffic Erlangs</p>";
        echo "<p>Nombre de porteuses par cellule : $porteuses_par_cellule</p>";
        echo "<p>Nombre IT restant : $it_restant Erlangs</p>";
        echo "<p>Trafic par cellule : $trafic_par_cellule Erlangs</p>";
        echo "<p>Nombre de cellules nécessaires : $nombre_cellules_necessaires</p>";

        ?>
    </div>
</body>
</html>
