<?php
// Include the Huffman encoding logic
include 'huffman.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the user's message from the form input
    $message = $_POST["message"];

    // Perform Huffman encoding
    $encodedData = huffmanEncode($message);

    // Display the encoded data
    echo "<h2>Encoded Data:</h2>";
    echo "<pre>" . htmlspecialchars($encodedData) . "</pre>";
    echo "<link rel=stylesheet href='styles.css'>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Encode</title>
    <link rel=stylesheet href="styles.css">
    <style>
        body {
            background-image: url("binarybg.jpg");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            z-index: -1;
            margin: 0;
            padding: 0;
            height: 100vh;
        }
    </style>
</head>
<body>
    
</body>
</html>