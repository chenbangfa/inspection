<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'db.php';

$db = new DbHelper();
$db->connect();

echo "<h3>Testing getCount</h3>";

// Test 1: Count hyUser
echo "Counting hyUser...<br>";
$count = $db->getCount("hyUser", "1=1");
echo "Count result: " . var_export($count, true) . "<br>";

// Test 2: Debug query directly for COUNT
echo "Direct Query Debug:<br>";
$sql = "SELECT COUNT(1) as c FROM hyUser WHERE 1=1";
$res = $db->query($sql, 1);
echo "<pre>";
var_export($res);
echo "</pre>";

// Test 3: List
echo "<h3>Testing getList</h3>";
$list = $db->getList("hyUser", "1=1", "id desc", 5);
echo "<pre>";
print_r($list); // getList returns array(cur, count, pg, pe, ne, cu, results)
echo "</pre>";
?>