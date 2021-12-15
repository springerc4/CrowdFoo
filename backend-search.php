<?php
$mysqli = new mysqli("localhost", "root", "", "crowdfoo");


if(isset($_REQUEST["term"])){
    $sql = "SELECT * FROM projects WHERE project_name LIKE ?";
    if($stmt = $mysqli->prepare($sql)){
        $stmt->bind_param("s", $param_term);
        $param_term = $_REQUEST["term"] . '%';
        if($stmt->execute()){
            $result = $stmt->get_result();
            if($result->num_rows > 0){
                while($row = $result->fetch_array(MYSQLI_ASSOC)){
                  echo "<p>" . $row["project_name"] . "</p>";
                }
            } else{
                echo "<p>No matches found</p>";
            }
        }
    }
    $stmt->close();
}
$mysqli->close();
?>
