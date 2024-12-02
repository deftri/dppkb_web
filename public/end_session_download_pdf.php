<?php
session_start();
include '../config/config.php';
require('../fpdf/fpdf.php'); // Make sure this path matches where you've saved FPDF

// Ensure the user is logged in and has a session ID
if (!isset($_SESSION['user_id']) || !isset($_POST['session_id'])) {
    header("Location: ../public/login.php");
    exit();
}

$session_id = filter_var($_POST['session_id'], FILTER_SANITIZE_NUMBER_INT);

// Fetch client information for naming the PDF
$sql_client = "SELECT users.username AS client_name FROM users 
               JOIN chat_sessions ON users.id = chat_sessions.klien_id 
               WHERE chat_sessions.id = ?";
$stmt_client = $conn->prepare($sql_client);
$stmt_client->bind_param("i", $session_id);
$stmt_client->execute();
$client_info = $stmt_client->get_result()->fetch_assoc();

$client_name = $client_info['client_name'] ?? 'Unknown_Client';
$date = date("Y-m-d");
$pdf_name = $client_name . '_' . $date . '.pdf';

// Fetch chat messages for the session
$sql_messages = "SELECT * FROM chat_messages WHERE session_id = ? ORDER BY sent_at";
$stmt_messages = $conn->prepare($sql_messages);
$stmt_messages->bind_param("i", $session_id);
$stmt_messages->execute();
$messages = $stmt_messages->get_result();

// End the session
$sql_end_session = "UPDATE chat_sessions SET status = 'selesai' WHERE id = ?";
$stmt_end_session = $conn->prepare($sql_end_session);
$stmt_end_session->bind_param("i", $session_id);
$stmt_end_session->execute();

// Initialize FPDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);

// Title
$pdf->Cell(0, 10, 'Chat Transcript', 0, 1, 'C');
$pdf->SetFont('Arial', '', 12);
$pdf->Ln(5);

// Add each message to the PDF
while ($msg = $messages->fetch_assoc()) {
    $sender_id = $msg['sender_id'];
    $message = $msg['message'];
    $sent_at = $msg['sent_at'];

    // Fetch sender name
    $sql_sender = "SELECT username FROM users WHERE id = ?";
    $stmt_sender = $conn->prepare($sql_sender);
    $stmt_sender->bind_param("i", $sender_id);
    $stmt_sender->execute();
    $sender_result = $stmt_sender->get_result();
    $sender = $sender_result->fetch_assoc()['username'];

    // Add message to PDF
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, "$sender ($sent_at)", 0, 1);
    $pdf->SetFont('Arial', '', 12);
    $pdf->MultiCell(0, 10, $message);
    $pdf->Ln(5);
}

// Output the PDF
$pdf->Output('D', $pdf_name); // 'D' forces download with the specified filename
?>
