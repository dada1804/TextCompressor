<?php
class MinHeapNode {
    public $data;
    public $freq;
    public $left;
    public $right;

    public function __construct($data, $freq) {
        $this->left = $this->right = null;
        $this->data = $data;
        $this->freq = $freq;
    }
}

class MinHeap {
    private $heap;
    private $size;

    public function __construct() {
        $this->heap = [];
        $this->size = 0;
    }

    public function insert($node) {
        $this->heap[$this->size] = $node;
        $this->size++;
        $this->heapifyUp($this->size - 1);
    }

    public function extractMin() {
        if ($this->size === 0) {
            return null;
        }

        $minNode = $this->heap[0];
        $this->heap[0] = $this->heap[$this->size - 1];
        $this->size--;
        $this->heapifyDown(0);
        return $minNode;
    }

    public function getSize() {
        return $this->size;
    }

    private function heapifyUp($index) {
        $parent = ($index - 1) / 2;
        while ($index > 0 && $this->heap[$index]->freq < $this->heap[$parent]->freq) {
            $this->swap($index, $parent);
            $index = $parent;
            $parent = ($index - 1) / 2;
        }
    }

    private function heapifyDown($index) {
        $left = 2 * $index + 1;
        $right = 2 * $index + 2;
        $smallest = $index;

        if ($left < $this->size && $this->heap[$left]->freq < $this->heap[$smallest]->freq) {
            $smallest = $left;
        }

        if ($right < $this->size && $this->heap[$right]->freq < $this->heap[$smallest]->freq) {
            $smallest = $right;
        }

        if ($smallest !== $index) {
            $this->swap($index, $smallest);
            $this->heapifyDown($smallest);
        }
    }

    private function swap($a, $b) {
        $temp = $this->heap[$a];
        $this->heap[$a] = $this->heap[$b];
        $this->heap[$b] = $temp;
    }
}

function buildHuffmanTree($data, $freq) {
    $minHeap = new MinHeap();
    for ($i = 0; $i < count($data); $i++) {
        $node = new MinHeapNode($data[$i], $freq[$i]);
        $minHeap->insert($node);
    }

    while ($minHeap->getSize() > 1) {
        $left = $minHeap->extractMin();
        $right = $minHeap->extractMin();
        $top = new MinHeapNode('$', $left->freq + $right->freq);
        $top->left = $left;
        $top->right = $right;
        $minHeap->insert($top);
    }

    return $minHeap->extractMin();
}

function generateHuffmanCodes($root, $currentCode, &$huffmanCodes) {
    if ($root === null) {
        return;
    }

    if ($root->data !== '$') {
        $huffmanCodes[$root->data] = $currentCode;
    }

    generateHuffmanCodes($root->left, $currentCode . "0", $huffmanCodes);
    generateHuffmanCodes($root->right, $currentCode . "1", $huffmanCodes);
}

function calculateCompressionRatio($originalSize, $encodedSize) {
    if ($originalSize === 0) {
        return 0;
    }

    $compressionRatio = ($encodedSize / $originalSize) * 100;
    return round($compressionRatio, 2);
}

function calculateSizeDifference($originalSize, $encodedSize) {
    return $originalSize - $encodedSize;
}

function huffmanEncode($message) {
    $freq = array_count_values(str_split($message));
    $data = array_keys($freq);
    $root = buildHuffmanTree($data, array_values($freq)); // Use array_values to get the values from the freq array

    $huffmanCodes = [];
    generateHuffmanCodes($root, "", $huffmanCodes);

    $encodedData = "";
    for ($i = 0; $i < strlen($message); $i++) {
        $encodedData .= $huffmanCodes[$message[$i]];
    }

    return $encodedData;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $message = $_POST["message"];
    $encodedData = huffmanEncode($message);

    $originalSize = strlen($message);
    $encodedSize = ceil(strlen($encodedData) / 8); // Convert bits to bytes


    //$compressionRatio = calculateCompressionRatio($originalSize, $encodedSize);
    $originalBits = $originalSize * 8; // Convert bytes to bits
$encodedBits = strlen($encodedData);

$compressionRatio = ((1 - $encodedBits / $originalBits) * 100);

    $sizeDifference = calculateSizeDifference($originalSize, $encodedSize);

    echo "<p>Original Message: $message<br></p>";
    echo "<p>Original Size: $originalSize bytes<br></p>";
    echo "<p>Encoded Message: $encodedData<br></p>";
    echo "<p>Encoded Size: $encodedSize bytes<br></p>";
    echo "<p>Compression Ratio: $compressionRatio%<br></p>";
    echo "<p>Size Difference: $sizeDifference bytes<br></p>";
    echo '<style>
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
        p {
            margin-bottom: 10px;
            font-family: Verdana, Geneva, Tahoma, sans-serif;
            color: 	#FFFF00;
        }
    </style>';
    echo '<link rel="stylesheet" href="styles.css">';
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Huffman Encoding</title>
    <link rel="stylesheet" href="styles.css">
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
    <h1>Huffman Encoding</h1>
    <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
        <label for="message"></label><br>
        <textarea name="message" rows="4" cols="50" placeholder="Enter the message"></textarea><br><br>
        <input type="submit" value="Encode">
    </form>
</body>
</html>
