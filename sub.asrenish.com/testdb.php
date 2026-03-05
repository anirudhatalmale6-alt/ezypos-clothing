?> 

 <?php
$servername = "localhost";
$username = "trbsysne_asrenish";
$password = "Renish@123";
$dbname = "trbsysne_asrenish";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM ezy_pos_categories";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  // output data of each row
  while($row = $result->fetch_assoc()) {
    echo "Row1: " . $row["cat_name"]. " - Row2 " . $row["cat_createdat"] .  "<br>";
  }
} else {
  echo "0 results";
}
echo "Done";
$conn->close();
?> 