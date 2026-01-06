<?php
require 'db.php';
try {
  $stmt = $pdo->query("SELECT DATABASE() AS db, USER() AS user, VERSION() AS version");
  echo json_encode($stmt->fetch());
} catch (Exception $e) {
  echo json_encode(['error' => $e->getMessage()]);
}
