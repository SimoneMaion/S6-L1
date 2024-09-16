<?php
function executeCommand($command) {
    $output = '';
    switch ($command) {
        case 'date':
            $output = date('Y-m-d H:i:s');
            break;
        case 'phpinfo':
            ob_start();
            phpinfo();
            $output = ob_get_clean();
            break;
        case 'server':
            $output = print_r($_SERVER, true);
            break;
        case 'listfiles':
            $output = implode("\n", scandir('.'));
            break;
        case 'diskspace':
            $output = "Spazio libero: " . disk_free_space("/") . " bytes\n";
            $output .= "Spazio totale: " . disk_total_space("/") . " bytes";
            break;
        default:
            if (strpos($command, 'echo ') === 0) {
                $output = substr($command, 5);
            } elseif (strpos($command, 'calc ') === 0) {
                $expr = substr($command, 5);
                $output = "Risultato: " . eval("return $expr;");
            } else {
                $output = "Comando non riconosciuto. Usa 'help' per la lista dei comandi.";
            }
    }
    return $output;
}

$commandList = array(
    'date' => 'Mostra data e ora correnti',
    'phpinfo' => 'Mostra informazioni su PHP',
    'server' => 'Mostra variabili del server',
    'listfiles' => 'Elenca i file nella directory corrente',
    'diskspace' => 'Mostra lo spazio su disco',
    'echo [testo]' => 'Stampa il testo specificato',
    'calc [espressione]' => 'Calcola l\'espressione matematica',
    'help' => 'Mostra questa lista di comandi'
);

if (isset($_POST['command'])) {
    $command = $_POST['command'];
    if ($command == 'help') {
        $output = "Comandi disponibili:\n";
        foreach ($commandList as $cmd => $description) {
            $output .= "$cmd: $description\n";
        }
    } else {
        $output = executeCommand($command);
    }
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shell PHP Grafica</title>
    <style>
        body { font-family: 'Courier New', monospace; background: #1e1e1e; color: #d4d4d4; padding: 20px; }
        #shell { background: #2d2d2d; border: 1px solid #3c3c3c; padding: 10px; border-radius: 5px; }
        #output { height: 300px; overflow-y: auto; white-space: pre-wrap; margin-bottom: 10px; }
        #input { width: 100%; background: #3c3c3c; color: #d4d4d4; border: none; padding: 5px; }
        #submit { background: #0e639c; color: white; border: none; padding: 5px 10px; cursor: pointer; }
        #submit:hover { background: #1177bb; }
    </style>
</head>
<body>
    <div id="shell">
        <div id="output"><?php echo isset($output) ? htmlspecialchars($output) : ''; ?></div>
        <form method="post">
            <input type="text" id="input" name="command" autofocus placeholder="Inserisci un comando (usa 'help' per la lista)">
            <input type="submit" id="submit" value="Esegui">
        </form>
    </div>
    <script>
        document.getElementById('output').scrollTop = document.getElementById('output').scrollHeight;
    </script>
</body>
</html>
