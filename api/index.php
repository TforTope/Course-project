Author: TOPE_OLUSEGU
Date: 5/26/2026
File: index.php
Description:

<?php

header("Content-Type: application/json");

echo json_encode([
        "message" => "Welcome to the EventHub API",
        "version" => "1.0",
        "status" => "running"
]);

