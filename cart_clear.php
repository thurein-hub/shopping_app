<?php
    session_start();
    unset($_SESSION['cart']['id'.$_GET['pro_id']]);
    header('location: cart.php');
?>