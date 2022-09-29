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

    if(isset($_GET["sortOrder"]) && $_GET["sortOrder"] == "RAND") {
        
        //nesumaiso masyvo indeksu
        //kad sumaisytu su indeksais
        shuffle($klientai);
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

//puslapiu mygtuku atvaizdavimas
function pagination() {
    
    $klientai = readJson("klientai.json");
    $kiek = count($klientai);//klientu kieki
    $irasaiPerPuslapi = 15;//kiek irasu bus rodoma viename puslapyje

    if(isset($_GET["limit"])) {
        $irasaiPerPuslapi = $_GET["limit"];
    }

    $page = 1;
    if(isset($_GET["page"])) {
        $page = $_GET["page"];
    }

    //ceil(lubos) - apvalina visalaika i didesne puse
    //floor(grindys) - apvalina visalaika i mazesne puse
    
    // ceil(46/ 15) = 3.11111 = 4
    // floor(46/15) = 3.11111 = 3


    $puslapiuKiekis = ceil($kiek/$irasaiPerPuslapi);
    echo "<span>Jūs esate $page iš $puslapiuKiekis </span>";
    echo "<ul class='pagination'>";
    for($i=1;$i<$puslapiuKiekis+1;$i++) {
        if($i==$page) {
            echo "<li class='page-item active'><a class='page-link' href='klientai.php?page=$i&limit=$irasaiPerPuslapi'>$i</a></li>";
        } else {
            echo "<li class='page-item'><a class='page-link' href='klientai.php?page=$i&limit=$irasaiPerPuslapi'>$i</a></li>";
        }
       
    }
    echo "</ul>";


    

    //var_dump($puslapiuKiekis);
}

//irasu nukarpymas

function limitValues() {
    $limitValues = array(
        "15" => "15",
        "30" => "30",
        "45" => "45",
        "visi" => "Visi"
    );

    foreach ($limitValues as $key => $value) {
        if(isset($_GET["limit"]) && $key == $_GET["limit"]) {
            echo "<option value='$key' selected>$value</option>";
        } else {
            echo "<option value='$key'>$value</option>";
        }
    }
}



function paginate($klientai) {
    $page = 1;
    if(isset($_GET["page"])){
        $page = $_GET["page"];
    }

    $kiek = count($klientai);
    $irasuKiekisPuslapyje = 15;

    if(isset($_GET["limit"])) {
        $irasaiPerPuslapi = $_GET["limit"];
    }

    $puslapiuKiekis = ceil($kiek/$irasuKiekisPuslapyje );

    if($puslapiuKiekis < $page || $page < 1) {
        $page = 1;
    }

    $offset = ($page * $irasuKiekisPuslapyje) - $irasuKiekisPuslapyje;

    $klientai = array_slice($klientai,$offset,$irasuKiekisPuslapyje, true);
    return $klientai;
}
//void tuscia
function getClients() {
    $klientai = readJson("klientai.json");
    
    $klientai = sortClients($klientai);
    $klientai = filterClients($klientai);

    if((isset($_GET["limit"]) && $_GET["limit"] != "visi") || !isset($_GET["limit"])) {
        $klientai = paginate($klientai);    
    }  


    //a)Sujungti filtravimo ir rikiavimo formas
    // visi kintamieji nueina i nuoroda ir juos galima pasiimti per GET
    // ir suveikia tiek sortClients tiek filterClients
    //b) musu formos gali likti atskiros, taciau jos turi tureti
    //pasleptus input laukelius su kitos formos informacija

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
//puslapiavimas


function rikiuok($masyvas) {
    rsort($masyvas); //reverse sort - atvirkstine tvarka
    return $masyvas;
}

function filtruok($masyvas) {
    //array_filter
    $masyvas = array_filter($masyvas, function($elementas){
        if($elementas % 2 == 0) {
            return true;
        } else {
            return false;
        }
    });
    
    return $masyvas;
}


function pavyzdys() {
    $masyvas = array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20);
    var_dump($masyvas);
    

//filtravimo uzdavinys - atrinkti tik lyginius skaicius
    $masyvas = filtruok($masyvas);
    var_dump($masyvas);

    //rikiavimo uzdavinys - surikiuoti atvirkstine tvarka
    //20 iki 1 (DESC)
    $masyvas = rikiuok($masyvas);

    var_dump($masyvas);
    
}

//array_slice - funkcija, kuri atrenka masyvo dali pagal nurodyta kriteriju

function masyvoPjaustymas() {
    $masyvas = array(1,2,3,4,5,6,7,8,9,10,11);
    //masyvas, nuo kurios vietos norime imti duomenis, kiek duomenu norime paimti, ar norime isaugoti sena indeksa(true)
    //nuo pasirinkto puslapio 3, man atvaizduoja duomenis

    $page = $_GET["page"];
    $irasuKiekisPuslapyje = 2;
    $offset = ($page * $irasuKiekisPuslapyje) - $irasuKiekisPuslapyje;

    $dinaminisPuslapis = array_slice($masyvas,$offset,$irasuKiekisPuslapyje, true);

    var_dump($dinaminisPuslapis);
    $pirmasPuslapis = array_slice($masyvas,0,2, true); // (1 * 2) - 2 = 0
    // var_dump($pirmasPuslapis);
    $antrasPuslapis = array_slice($masyvas,2,2, true); //  (2 * 2) - 2 = 2
    // var_dump($antrasPuslapis);
    $treciasPuslapis = array_slice($masyvas,4,2, true); // ($page * $irasuKiekisPuslapyje) - irasuKiekisPuslapyje = 4
    // var_dump($treciasPuslapis);
}

?>