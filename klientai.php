<?php include "functions.php";  ?>
<?php session_start(); ?>
<?php addClient(); ?>

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
    <!-- Kaip prideti nauja klienta i faila per forma pasinaudojant funkciniu budu -->

        <div class="row">
            <div class="col-lg-4">
                <form method="post" action="klientai.php">
                    <div class="form-group">
                        <label for="vardas">Vardas</label>
                        <input type="text" class="form-control" id="vardas" name="vardas">
                    </div>
                    <div class="form-group">
                        <label for="pavarde">Pavarde</label>
                        <input type="text" class="form-control" id="pavarde" name="pavarde">
                    </div>
                    <div class="form-group">
                        <label for="amzius">Amzius</label>
                        <input type="text" class="form-control" id="amzius" name="amzius">
                    </div>
                    <div class="form-group">
                        <label for="miestas">Miestas</label>
                        <input type="text" class="form-control" id="miestas" name="miestas">
                    </div>
                    <button type="submit" class="btn btn-primary" name="addClient">Add Client</button>
                </form>
            </div
        </div>

        <?php showMessage(); ?>
        <table class="table table-striped">
            <tr>
                <th>Eil nr.</th>
                <th>Vardas</th>
                <th>Pavardė</th>
                <th>Amžius</th>
                <th>Miestas</th>
            </tr>
            <?php getClients(); ?>
        </table>

    
    </div>
</body>
</html>