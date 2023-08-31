<html>
<head>
    <?php
        include "weightedRandom.php";
    ?>
    <link href="quiz.css" rel="stylesheet">
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            
            let issetAntwort = "<?php echo isset($_GET['antwort'])?>";

            console.log(issetAntwort);

            if(issetAntwort == 1){

                document.getElementById("quiz").hidden = true;

                console.log(wahrheit);

                if(wahrheit == true){
                document.getElementById('ani_richtig').hidden = false;
                document.getElementById('ani_falsch').hidden = true;
                document.getElementById('ani_normal').hidden = true;
                }
                else{
                    document.getElementById('ani_richtig').hidden = true;
                    document.getElementById('ani_falsch').hidden = false;
                    document.getElementById('ani_normal').hidden = true;
                }
            }
            else{
                document.getElementById("lösung").hidden = true;
                document.getElementById('ani_richtig').hidden = true;
                document.getElementById('ani_falsch').hidden = true;
                document.getElementById('ani_normal').hidden = false;
            }

        }, false);

        function lösung(){
            document.getElementById("lösung").hidden = true;
            document.getElementById("quiz").hidden = false;
            document.getElementById('ani_richtig').hidden = true;
            document.getElementById('ani_falsch').hidden = true;
            document.getElementById('ani_normal').hidden = false;
        }

        function lableClick(counter){
            document.getElementById("radio" + counter).checked = true;
        }

        function submit_knochen() {
            document.getElementById('antwort').submit();
        }
    </script>
</head>
<body>
    <div class="content">
        <div id="quiz">
            <form method="GET" id="antwort">
                <?php
                    $sql = "SELECT `id_frage`, `weight` FROM punkte WHERE `username` = '$username'";
                    $question = $conn->query($sql);
                    
                    $question_list = array();
                    
                    if($question->num_rows > 0){

                        while($row = $question->fetch_assoc()){
                            array_push($question_list, $row);

                        }
                        
                        $ergebis = weightedRandom($question_list);

                        $id_frage  = $ergebis["id_frage"];
                    }
                
                    $sql = "SELECT `text_frage`, `id_bild` FROM frage WHERE `id_frage` = $id_frage";
                    $question_txt = $conn->query($sql);
                    $row = $question_txt->fetch_assoc();

                    if($row['id_bild']){
                        
                        echo '<div class="column">' . $row["text_frage"] . '</div>';
                        echo '<div class="column">' . '<img src="' . $row['id_bild'] . '.png" title="Bildfrage">' . '</div>';
                    
                    }
                    else{

                        echo '<div>' . $row["text_frage"] . '</div><br><br>';
                    
                    }

                    $sql = "SELECT text_antwort, id_antwort FROM antwort WHERE `id_frage` = $id_frage";
                    $antwort = $conn->query($sql);

                    $antwort_txt = $antwort->fetch_all();

                    shuffle($antwort_txt);

                    for($counter=0; $counter < count($antwort_txt); $counter++){
                        echo  "<input type='radio' id='radio$counter' name='antwort' value='" . $antwort_txt[$counter][1] . "'>" . 
                            "<lable onclick='lableClick($counter)' id='radioLable$counter for='radio$counter'>" . $antwort_txt[$counter][0] . "</lable><br>" .
                            "<br>";

                    }

                ?>
                <div class="container_knochen" onclick="submit_knochen()">
                    <img src="knochen.png" id="knochen_img" alt="knochen" style="width:100%">
                    <button id="knochen_btn" class="btn">Mampf</button>
                </div>  
            </form>
        </div>
        <div id="lösung">
            <?php
                if(isset($_GET["antwort"])){

                    $id_antwort = $_GET["antwort"];
                    $sql = "SELECT wahrheit, text_antwort, id_frage FROM antwort WHERE `id_antwort` = $id_antwort";
                    $antwort = $conn->query($sql);
                    $row = $antwort->fetch_assoc();

                    echo $row["text_antwort"] . '<br>';

                    $id_frage = $row["id_frage"];
                    // echo $id_frage;
                    $sql = "SELECT `weight` FROM punkte WHERE `username` = '$username' AND `id_frage` = $id_frage";
                    $weight = $conn->query($sql);
                    $row_weight = $weight->fetch_assoc();
                    $weight = $row_weight['weight'];
                    
                    // wenn die Antwort richtig ist
                    if($row["wahrheit"] == true){

                        $_SESSION['wahrheit'] = true;
                        
                        echo "Ist richtig!<br>";

                        $weight = $weight - 33;
                        
                        if($weight < 1){
                            $weight = 1;
                        }                        
                        
                    }
                    // wenn die Atwort falsch ist
                    else{
                        
                        $sql = "SELECT text_antwort FROM antwort WHERE `wahrheit` = 1 AND `id_frage` = $id_frage";
                        $antwort = $conn->query($sql);
                        $row_antwort = $antwort->fetch_assoc();

                        echo "Ist falsch.<br>Richtig wäre:<br>" . $row_antwort["text_antwort"] . "<br>";
                        
                        $_SESSION['wahrheit'] = false;

                        $weight = $weight + 33;
                        
                    }

                    $zahl = (int) $weight;
                    $sql = "UPDATE punkte SET `weight` = $zahl WHERE `username` = '$username' AND `id_frage` = $id_frage";
                    $conn->query($sql);

                }

            ?>
            <div class="container_knochen" onclick="lösung()">
                    <img src="knochen.png" id="knochen_img" alt="knochen" style="width:100%">
                    <button id="knochen_btn" class="btn" onclick="lösung()">Verstanden</button>
            </div>
            
        </div>
    </div>
</body>
<script>
    let wahrheit = <?php if(isset($_SESSION['wahrheit'])){ 
                        if($_SESSION['wahrheit'] == true){
                            echo 'true';
                        }
                        else{
                            echo 'false';
                        }
                        }?>;
</script>
</html>