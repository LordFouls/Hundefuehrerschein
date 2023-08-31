<?php 
    include 'db.php';
?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pruefung</title>
    <link href="index.css" rel="stylesheet">

    <script>
        
        function lableClick(counter){
           
            document.getElementById("radio" + counter).checked = true;
        
        }

    </script>

    <style>
        #ani_falsch{
            display: none;
        }

        #ani_richtig{
            display: none;
        }
    </style>

</head>

<body>

<nav class="main-navbar">
    <?php include 'nav.php'; ?>
</nav>

<div class="body-container">
    <div class="column">
        <form method="GET" action="auswertung.php">
        <?php

            $sql = "SELECT text_frage, id_frage FROM frage";
            $data = $conn->query($sql);
            $frage = $data->fetch_all();

            $rand = rand(0, count($frage) - 1);
            $choose = array($rand);


            $anzahl_fragen = 30;
            while(count($choose) < $anzahl_fragen){

                // echo count($choose) . '<br>';
                $rand = rand(0, count($frage) - 1);
                $take = true;
                
                foreach($choose as $zahl){

                    if($zahl == $rand){
                        $take == false;
                    }
                
                }
                
                if($take){
                    
                    echo '<h3>' . count($choose) . '. ' . $rand . ' => ' . $frage[$rand][0] . '</h3><br>';
                    array_push($choose, $rand);

                    $data = $conn->query('SELECT text_antwort, id_antwort FROM antwort WHERE id_frage = ' . $frage[$rand][1]);
                    while($row = $data->fetch_assoc()){
                        echo  "<input type='radio' id='radio" . $row["id_antwort"] . 
                        "' name='" . $frage[$rand][1] . "' value='" . $row["id_antwort"] . "'>" . 
                        "<lable onclick='lableClick(" . $row["id_antwort"] . ")' id='radioLable" . 
                        $row["id_antwort"] . "for='radio" . $row["id_antwort"] . "'>" . $row["text_antwort"] . 
                        "</lable><br>" . "<br>";
                    }
                    
                }

            }
        ?>
            <button>Submit</button>
        </form>
    </div>
    <div class="column">
        <?php
            for($index = 0; $index < $anzahl_fragen; $index++){
                echo $index + 1 . "<br>";
            }
        ?>

    </div>
</div>

<div class="footer">
    <?php
        include 'footer.php';
    ?>
</div>  

</body>

</html>

<?php
    $conn->close();
?>