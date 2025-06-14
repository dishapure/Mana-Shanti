<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $index  = $_POST['line_index'] ?? null;
    $action = $_POST['action'] ?? '';

    $file = "appointments.txt";

    if (!is_null($index) && in_array($action, ['accept', 'reject'])) {
        $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if (isset($lines[$index])) {
            // Append status to the line
            $lines[$index] .= "|status:$action";
            file_put_contents($file, implode("\n", $lines));

            header("Location: appointment.php");
            exit();
        } else {
            echo "Invalid appointment index.";
        }
    } else {
        echo "Invalid request.";
    }
}
?>
