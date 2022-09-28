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
        //nutraukia viso php failo veikima nuo sitos vietos
        exit();
    }
}

function showMessage() {
    if(isset($_SESSION["zinute"])){  
       echo '<div class="alert alert-success" role="alert">';
            echo $_SESSION["zinute"];
            unset($_SESSION["zinute"]);
        echo '</div>';
    } 
}

function getCollumns() {
    $klientai = readJson("klientai.json");
    $klientas = $klientai[0];
    $collumms = array_keys($klientas);

    foreach($collumms as $collumn) {

        if(isset($_GET["sortCollumn"]) && $collumn == $_GET["sortCollumn"]) {
            echo "<option value='$collumn' selected>$collumn</option>";
        } else {
            echo "<option value='$collumn'>$collumn</option>";
        }
       
    }
}

function getCities() {
    $klientai = readJson("klientai.json");
    $cities = [];

    foreach ($klientai as $klientas) {
        $cities[] = $klientas["miestas"];
    }

    $cities=array_unique($cities);

    foreach($cities as $key=>$city) {
        if(isset($_GET["miestas"]) && $city == $_GET["miestas"]) {
            echo "<option value='$city' selected>$city</option>";
        } else {
            echo "<option value='$city'>$city</option>";
        }
    }

}
//jinai gauna kaip parametra nerikiuota masyva
//ir grazina rikiuota masyva
function sortClients($klientai) {
    if(isset($_GET["sortCollumn"]) && isset($_GET["sortOrder"])) {
        $sortCollumn = $_GET["sortCollumn"];
        $sortOrder = $_GET["sortOrder"];
        if($sortCollumn == "id") {
        //ASC ir DESC
            if($sortOrder == "ASC") {
                ksort($klientai);
            } else if($sortOrder == "DESC") {
                krsort($klientai);
            }
            //uasort funkcija
            // teksto rikiavimas


        } else {

            $order = [-1, 1]; //ASC
        
            if ($sortOrder == "DESC") {
                $order = [1, -1]; //DESC
            }

            uasort($klientai, function($dabartinis, $busimas) use($sortCollumn, $order) {    
                //$sordOrder = ASC    -1 1
                //$sortOrder = DESC   1 -1
        
               // $order = [-1, 1]; //ASC
        
                //if ($sortOrder == "DESC") {
                //    $order = [1, -1]; //DESC
                //}
                
                if($dabartinis[$sortCollumn] == $busimas[$sortCollumn]) {
                    return 0;
                } else if($dabartinis[$sortCollumn] < $busimas[$sortCollumn]) {
                    return $order[0];
                } else {
                    return $order[1];
                }
            });
        }        
    } else {
        //pagal id mazejimo tvarka
        krsort($klientai);
    }

    return $klientai;
}
//jjinai gauna kaip parametra nefiltruota masyva
//ir grazina filtruota masyva
function filterClients($klientai) {

    $miestas = "visi";

    if(isset($_GET["miestas"])) {
        $miestas = $_GET["miestas"];
    }

    $klientai=array_filter($klientai,function($klientas) use($miestas) {
        if($miestas == "visi") {
            return true;
        } else if ($klientas["miestas"] == $miestas) {
            return true;
        } else {
            return false;
        }
    });
    //filtruojame duomenis pagal miesta
    return $klientai;
}


function pagination() {}
//void tuscia
function getClients() {
    $klientai = readJson("klientai.json");
    //$klientai = sortClients($klientai);

    $klientai = filterClients($klientai);

    foreach($klientai as  $i => $klientas) {
        echo "<tr>";
            echo "<td>$i</td>";
            echo "<td>".$klientas["vardas"]."</td>";
            echo "<td>".$klientas["pavarde"]."</td>";
            echo "<td>".$klientas["amzius"]."</td>";
            echo "<td>".$klientas["miestas"]."</td>";
            echo "<td>";
                echo "<a href='edit.php?id=$i' class='btn btn-secondary'>Edit</a>";
                echo "<form method='post' action='klientai.php'>
                        <button type='submit' name='delete' value='$i' class='btn btn-danger'>Delete</button>
                    </form>";
            echo "</td>";       
        echo "</tr>";
    }
}

function getClient($id) {
    $klientai = readJson("klientai.json");
    return $klientai[$id];
}

//trinti klientus
function deleteClient() {
    if(isset($_POST["delete"])) {
        $klientai = readJson("klientai.json");
        unset($klientai[$_POST["delete"]]);
        writeJson("klientai.json", $klientai);

        $_SESSION["zinute"] ="Ištrynėme klientą numeriu" . $_POST["delete"];

        header("Location: klientai.php");
        exit();
    }
}
//redaguoti klientus

function updateClient() {
    $klientai=readJson("klientai.json");

    if(isset($_POST["updateClient"])){
        $klientas = array(
            "vardas" => $_POST["vardas"],
            "pavarde" => $_POST["pavarde"],
            "amzius" => $_POST["amzius"],
            "miestas" => $_POST["miestas"]
        );
        //kliento numeris
        //$_GET["id"] - sitoje vietoje egzistuoja? nebeegzistuoja
        //jei ne, kaip gauti?
        //ir ar $_POST["id"] egzistuoja
        $klientai[$_POST["id"]] = $klientas;
        
        writeJson("klientai.json", $klientai);
        $_SESSION["zinute"] ="Klientas atnaujintas sėkmingai ". $_POST["id"];

        header("Location: klientai.php");
        //nutraukia viso php failo veikima nuo sitos vietos
        exit();
    }
}

//rikiuoti klientus

//filtruoti klientus

?>