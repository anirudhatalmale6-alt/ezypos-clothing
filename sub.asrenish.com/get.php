 <?php
session_start();
?>
<!DOCTYPE html>
<html>
<body>

<?php
// Echo session variables that were set on previous page
echo "color is " . $_SESSION["favcolor"] . ".<br>";
echo "animal is " . $_SESSION["favanimal"] . ".";
?>

</body>
</html> 