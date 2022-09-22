<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Klientai</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>


</head>
<body>
    <div class="container">
    <!-- 1 eilute su 5 -->
    <table class="table table-striped">
        <tr>
            <th>Eil nr.</th>
            <th>Vardas</th>
            <th>Pavardė</th>
            <th>Amžius</th>
            <th>Miestas</th>
        </tr>
                <?php 
            
            //Visus klientus atvaizduoti lenteleje(table)
            
            //viska turime nuskaityti is failo ir dekoduoti is json x
            // 1 tr eilute = 1 klientas
            //turime atvaizduoti tiek <tr> kiek klientu yra faile
            //musu failas yra asociatyvus masyvas
            //vadinasi galime naudoti foreach funkcija

            $klientai = json_decode(file_get_contents("klientai.json"), true);
            
            foreach($klientai as $klientas) {
                echo "<tr>";
                    echo "<td>0</td>";
                    echo "<td>".$klientas["vardas"]."</td>";
                    echo "<td>".$klientas["pavarde"]."</td>";
                    echo "<td>".$klientas["amzius"]."</td>";
                    echo "<td>".$klientas["miestas"]."</td>";
                    
                echo "</tr>";
            }
            ?>
    </table>

    
    
    </div>
</body>
</html>