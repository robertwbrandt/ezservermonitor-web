<?php
require 'autoload.php';
$Config = new Config();
// $update = $Config->checkUpdate();

error_reporting(-1);
header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
// header('Content-type: text/plain');

$var = "";
if (isset($_GET['var']))  $var = $_GET['var'];
if (isset($_POST['var'])) $var = $_POST['var'];
?>
<script src="js/esm.php" type="text/javascript"></script>
<?php

// var_export($Config->plugins); echo "\n";



$tmp = $Config->get($var);
echo "\n". $var . " = "; 
var_export($tmp); echo "\n";

var_export($Config->getPluginNames("cpu")); echo "\n";

?>

