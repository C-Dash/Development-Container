<?php

/* dev */
$servername = "mariadb";
$username = "root";
$password = "blabla";
$dbname = "omeka";

/*Azure 
$servername = "chcmariadb2.mariadb.database.azure.com";
$username = "omeka@chcmariadb2";
$password = "chc_omekapw*";
$dbname = "omeka";
/* omeka database ini file
user     = "omeka@chcmariadb2"
password = "chc_omekapw*"
dbname   = "omeka"
host     = "chcmariadb2.mariadb.database.azure.com"
*/

$output = array( 'success' => false, 'error' => null, 
                 'resp' => array(), 'sql' => null, 'searchterm' => null);
// Create connection
$conn = new mysqli($servername, $username, 
              $password, $dbname);

// Check connection
if ($conn->connect_error) {
    $output["error"] = "Unable to connect tith server: " . $conn->connect_error;
die("Connection failed: " . $conn->connect_error);
}

$searchterm=$_POST['parameter'];
$output["searchterm"] = $searchterm;

$sql = "SELECT item_id, r.title, lat, lng 
        FROM mapping_marker, resource r
        WHERE item_id = r.id 
        AND r.resource_template_id = 7 ";

if ( strlen($searchterm) > 1 ){
  $searchterm=$_POST['parameter'];
  //$sql .= " AND r.title LIKE '%Otis%';";
  $sql .= " AND r.title LIKE '%" . $searchterm . "%';";
  //if ( is_empty( $searchterm )) {
//  if ( "Foo" === "Foo") {
} else {
     $sql .= ";";
      
    };

    $output["sql"] = $sql;


//$sql = "SELECT username, faculty, role 
//FROM $databaseTableName WHERE username = '$user' ";

$result = $conn->query($sql);

if ( $result->num_rows > 0) 
{
     $output["success"] = true;

     // output data of each row
     while( $row = $result->fetch_assoc()) {
          //$output["resp"] += "\n" + $row;
          //$output[$row["item_id"]] = $row;
          //$output["resp"] = $row;
          array_push($output["resp"],$row);
          //break;
      }
    /* free result set */
    $result->free();
}
else {
      $output["success"] = false;
      $output["error"] = "No rows selected";
}
$conn->close();
echo json_encode($output);
?>
