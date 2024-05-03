<?php

// Anlegen der Daten für die UI
function createDatapool()
{
    $conn = getDBConnection();
    require_once 'dbConnection.php';
    getPersonenDaten($conn);
    getArtikel($conn);
    getAntag_Gruppe($conn);

    $conn->close();
}

function getDataFromDB($query, $filename, $isBlob, $conn)
{
    $result = $conn->query($query);
    if ($isBlob) {
        if ($result->num_rows > 0) {
            $rows = array();
            while ($row = $result->fetch_assoc()) {
                $imageJson = json_encode(['image_data' => base64_encode($row['Bild'])]);
                var_dump($imageJson);
                $json_data = array(
                    "Titel" => htmlspecialchars_decode($row['Titel']),
                    "Datum" => htmlspecialchars_decode($row['Datum']),
                    "Bild" => json_encode(base64_encode($row['Bild'])),
                    "ArtikelText" => htmlspecialchars_decode($row['ArtikelText'])
                );
                array_push($rows, $json_data);
            }
            $jsonData = json_encode($rows, true);
            file_put_contents($filename, $jsonData);
        }
    } else {
        if ($result->num_rows > 0) {
            $rows = array();
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
            $json_data = json_encode($rows, JSON_PRETTY_PRINT);
            file_put_contents($filename, $json_data);
        }
    }
}