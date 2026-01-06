<?php
require_once 'groq_client.php';

$result = groqRequest([
    ["role"=>"user","content"=>"Say hello"]
]);

var_dump($result);
