<?php 

//nuskaito infomracija json formatu ar pagamina masyva
function readJson($file) {
    $json = file_get_contents($file);
    $result = json_decode($json, true);
    return $result;

}

//kuri masyva pavercia i json ir iraso i faila
//void funkcija
function writeJson($file, $array) {
    $json = json_encode($array);
    file_put_contents($file, $json);
}

function addClient() {
    //1. readJson
    //2. papildysime nuskaityta masyva nauju klientu
    //3. writeJson
    $klientai=readJson("klientai.json");

    if(isset($_POST["addClient"])){
        $naujasKlientas = array(
            "vardas" => $_POST["vardas"],
            "pavarde" => $_POST["pavarde"],
            "amzius" => $_POST["amzius"],
            "miestas" => $_POST["miestas"]
        );
        $klientai[] = $naujasKlientas;
        writeJson("klientai.json", $klientai);
        $_SESSION["zinute"] ="Klientas sukurtas sėkmingai";
        header("Location: klientai.php");
    }
}

function showMessage() {
    if(isset($_SESSION["zinute"])){  
       echo '<div class="alert alert-success" role="alert">';
            echo $_SESSION["zinute"];
        echo '</div>';
    } 
}

//void tuscia
function getClients() {
    $klientai = readJson("klientai.json");
    $klientai = array_reverse($klientai);
    foreach($klientai as  $i => $klientas) {
        echo "<tr>";
            echo "<td>$i</td>";
            echo "<td>".$klientas["vardas"]."</td>";
            echo "<td>".$klientas["pavarde"]."</td>";
            echo "<td>".$klientas["amzius"]."</td>";
            echo "<td>".$klientas["miestas"]."</td>";       
        echo "</tr>";
    }
}

//mes pildome sita faila
?>