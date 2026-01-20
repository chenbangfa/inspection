<?php header("Content-type: text/html; charset=utf-8");
require("db.php");

// Add new columns for Rectification Assignment feature
$updates = [
    "ALTER TABLE inspect ADD zgUserId int(11) DEFAULT NULL COMMENT '整改人ID';",
    "ALTER TABLE inspect ADD zgName varchar(50) DEFAULT NULL COMMENT '整改人姓名';",
    "ALTER TABLE inspect ADD zgPhoto varchar(1000) DEFAULT NULL COMMENT '整改照片';",
    "ALTER TABLE inspect ADD zgInfo varchar(500) DEFAULT NULL COMMENT '整改备注';"
];

foreach ($updates as $sql) {
    echo "Executing: $sql <br>";
    $db->execut($sql);
}

echo "Database Update Completed Successfully!";
?>