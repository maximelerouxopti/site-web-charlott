<?php

// error_reporting(E_ALL);
// ini_set("display_errors", 1);

require_once("database.php");
require_once("mail.php");


$TABLE = "subscribers";
$FIELDS = array(
    "name" => "s",
    "firstname" => "s",
    "company" => "s",
    "position" => "s",
    "address" => "s",
    "zip" => "s",
    "city" => "s",
    "email" => "s",
    "phone" => "s",
    "mobile" => "s",
    "info" => "i"
);


if(!empty($_POST)) {
    
    // Open a socket
    $db = new Database();
    
    // Build the request
    $columns = "";
    $placeholders = "";
    $types = "";
    $values = array();
    
    foreach($FIELDS as $field => $type) {
        // Columns
        $columns .= $field . ",";
        
        // Placeholders
        $placeholders .= "?,";
        
        // Values
        if($type == "i") {
            $value = empty($_POST[$field]) ? 0 : 1;
        } else {
            $value = $_POST[$field];
        }
        $types .= $type;
        $values = array_merge($values, array($field => $value));
    }
    
    $sql = "INSERT INTO $TABLE (" . trim($columns, ",") . ")
            VALUES (" . trim($placeholders, ",") . ");";
    
    if($db->query($sql, $types, $values)) {
        $mail = new Mail(
            $_POST["email"],
            "Prix Blaise Pascal",
            "Bonjour " . $_POST["firstname"] . " " . $_POST["name"] . ",<br/><br/>
            Nous confirmons avoir bien pris en compte votre inscription au Colloque Innovation Technologique et Santé Publique 
            qui verra la remise des Prix Blaise Pascal par Marisol Touraine, Ministre des Affaires Sociales, de la Santé et des Droits des Femmes 
            et le Député Gérard Bapt, président du groupe d'Etude Parlementaire Numérique et Santé.<br/><br/>
            Nous vous attendons le 23 janvier à la Cité des Sciences et de l'Industrie à Paris à 10h30.<br/>
            Tous les éléments de programme et d'accès sont consultables sur le site <a href='http://www.prixblaisepascal.fr'>www.prixblaisepascal.fr</a><br/><br/>
            Cordialement,<br/><br/>
            L'équipe du Prix Blaise Pascal<br/>
            <img src='http://www.prixblaisepascal.fr/images/title.png' width='200px'/>"
        );
        $mail->send();
    }

    $db->close();
}

?>






