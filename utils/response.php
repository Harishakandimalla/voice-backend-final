<?php
function success($data = []) {
    echo json_encode(array_merge(["ok" => true], $data));
    exit;
}

function error($msg) {
    echo json_encode(["ok" => false, "error" => $msg]);
    exit;
}
