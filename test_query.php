<?php
require 'db.php';

$stmt = $pdo->query("
    SELECT
      (SELECT COUNT(*) FROM tasks WHERE priority = 'HIGH') AS high_priority,
      (SELECT COUNT(*) FROM tasks WHERE status = 'UPCOMING') AS upcoming,
      (SELECT COUNT(*) FROM tasks WHERE status = 'PENDING') AS pending
");

print_r($stmt->fetch());
