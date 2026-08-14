<?php

// save_playlist.php

// 1. Zpracování požadavku na seznam MP3 souborů
if (isset($_GET['action']) && $_GET['action'] === 'get_mp3_list') {
    $directory = 'audio/';
    $mp3_files = array();

    // Kontrola, zda složka existuje
    if (is_dir($directory)) {
        // Najde všechny .mp3 soubory ve složce
        $files = glob($directory . "*.mp3");
        
        // Změní cestu na pouze názvy souborů (odstraní 'audio/')
        foreach ($files as $file) {
            $mp3_files[] = basename($file);
        }
    }

    // Nastaví hlavičku na JSON a vypíše data
    header('Content-Type: application/json');
    echo json_encode($mp3_files);
    exit; // Ukončí skript, aby se neprováděl zbytek (ukládání playlistu)
}


$playlistFile = 'playlist.txt';
$playlistFile = isset($_GET['file']) ? basename($_GET['file']) : 'playlist.txt';
$audioFolder = 'audio/';

// 1. NAČÍTÁNÍ SEZNAMU SKUTEČNÝCH MP3 SOUBORŮ NA SERVERU
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'get_mp3_list') {
    $mp3Files = [];
    if (is_dir($audioFolder)) {
        // Vyhledá všechny soubory s koncovkou .mp3 (nerozlišuje velká/malá písmena)
        $files = glob($audioFolder . '*.{[mM][pP]3}', GLOB_BRACE);
        foreach ($files as $file) {
            $mp3Files[] = basename($file);
        }
    }
    header('Content-Type: application/json');
    echo json_encode($mp3Files);
    exit;
}

// 2. NAČÍTÁNÍ AKTUÁLNÍHO PLAYLISTU (Pořadí 60 minut)
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (file_exists($playlistFile)) {
        echo file_get_contents($playlistFile);
    } else {
        echo "";
    }
    exit;
}

// 3. UKLÁDÁNÍ PLAYLISTU
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inputData = file_get_contents('php://input');
    if ($inputData !== false) {
        if (file_put_contents($playlistFile, $inputData) !== false) {
            echo "OK";
        } else {
            http_response_code(500);
            echo "Chyba zápisu do playlist.txt (Ověřte práva složky).";
        }
    }
    exit;
}
?>
