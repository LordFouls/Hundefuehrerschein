<?php
    session_start();

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "hundefuehrerschein";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
    }

    /*
    if(isset($_GET['username']) && isset($_GET['email']) && isset($_GET['password'])){
        $user = $_GET["username"];
        $email = $_GET["email"];
        $pass = $_GET["password"];

        $sql = "INSERT INTO kunden (`username`, `email`, `password`)
            VALUES ('$user', '$email', '$pass')";
        try{
            $conn->query($sql);
            $_SESSION['create_successful'] = true;
            $_SESSION['username'] = $user;
            $_SESSION['password'] = $pass;
            
            $sql = "SELECT id_frage FROM frage";
            $question = $conn->query($sql);

                while($row = $question->fetch_assoc()){

                    $i = $row["id_frage"];
                    $sql = "INSERT INTO punkte (`id_frage`, `username`, `weight`)
                    VALUES ('$i', '$user', '100')";
                    $conn->query($sql);
                }

        }
        catch(Exception $e){
            $_SESSION['create_successful'] = false;
        }
    }
    */

    if(isset($_REQUEST['username']) && isset($_REQUEST['email']) && isset($_REQUEST['password'])){
        $user = $_REQUEST["username"];
        $email = $_REQUEST["email"];
        $pass = $_REQUEST["password"];

        $prep = $conn->prepare("SELECT username from kunden WHERE `username`=?;");
        $prep->bind_param("s",$user);
        $prep->execute();
        $existingUsers = $prep->get_result();


        $sql = "INSERT INTO kunden (`username`, `email`, `password`)
            VALUES (?, ?, ?)";



        if($existingUsers->num_rows == 0){
            $prep = $conn->prepare($sql);
            $passwordHash = password_hash($pass,PASSWORD_DEFAULT);
            $prep->bind_param("sss",$user,$email,$passwordHash);
            $prep->execute();

            $_SESSION['create_successful'] = true;
            //$_SESSION['username'] = $user;
            //$_SESSION['password'] = $pass;
            
            $_SESSION['User'] = $user;

            $sql = "SELECT id_frage FROM frage";
            $question = $conn->query($sql);

                while($row = $question->fetch_assoc()){

                    $i = $row["id_frage"];
                    $sql = "INSERT INTO punkte (`id_frage`, `username`, `weight`)
                    VALUES ('$i', '$user', '100')";
                    $conn->query($sql);
                }

        }
        else{
            $_SESSION['create_successful'] = false;
        }
    }

    $conn->close();
?>
<html>
    <head>
        <script>
            document.location = "index.php";
        </script>
    </head>
    <body>

    </body>
</html>