<?php 
include 'config.php';
session_start();
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['reservation_id'])) {
    $reservationIdToDelete = $_GET['reservation_id'];
  
    $deleteQuery = "DELETE FROM reservations WHERE id = '$reservationIdToDelete'";
    $deleteResult = mysqli_query($conn, $deleteQuery);
  
    if ($deleteResult) {
      
    echo "Reservation deleted successfully!";
    
    }
  }

?>