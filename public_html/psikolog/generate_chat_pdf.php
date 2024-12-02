<?php
session_start();
require('../config/fpdf/fpdf.php'); // Update the path as necessary
include '../config/config.php';

if (isset($_GET['session_id'])) {
    $session_id = filter_var($_GET['session_id'], FILTER_SANITIZE_NUMBER_INT);

    // Fetch chat messages
    $sql_messages = "SELECT * FROM chat_messages WHERE session_id = ? ORDER BY sent_at";
    $stmt_messages = $conn->prepare($sql_messages);
    $stmt_messages->bind_param("i", $session_id);
    $stmt_messages->execute();
    $messages = $stmt_messages->get_result();

    // Create a new PDF document
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, "Riwayat Chat Sesi #$session_id", 0, 1, 'C');
    $pdf->Ln();

    while ($msg = $messages->fetch_assoc()) {
        $sender = $msg['sender_id'];
        $message = $msg['message'];
        $timestamp = $msg['sent_at'];
        
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(0, 10, "[$timestamp] $sender:", 0, 1);
        $pdf->SetFont('Arial', '', 10);
        $pdf->MultiCell(0, 10, $message);
        $pdf->Ln();
    }

    // Output PDF with a dynamic filename
    $pdf->Output('D', "Riwayat_Chat_Sesi_$session_id.pdf");
    exit();
}
?>
