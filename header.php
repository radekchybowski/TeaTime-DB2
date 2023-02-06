<!-- Radosław Chybowski -->
<?php
session_start();
require_once 'autoryzacja.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$_SESSION['$conn'] = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname)
or die('Bład połączenia z serwerem: '.mysqli_connect_error());
$conn = $_SESSION['$conn'];
mysqli_query($conn, 'SET NAMES utf8');

@$user_id = $_SESSION['user_id'];
@$login = $_SESSION['login'];
@$imie = $_SESSION['imie'];
@$nazwisko = $_SESSION['nazwisko'];
@$czas_zalozenia = $_SESSION['czas_zalozenia'];
@$zdjecie = $_SESSION['zdjecie'];


?>

<!DOCTYPE html>
<html lang="pl">
    <head>
        <title>TeaTime | HerbAppka</title>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta http-equiv="X-Ua-Compatible" content="IE=edge,chrome=1">
        <meta name="description" content="" />
        <meta name="author" content="Radosław Chybowski">

        <!-- Favicona -->
        <link rel="icon" type="image/x-icon" href="img/icon-coffee.svg" />

        <!-- CSS -->
        <link rel="stylesheet" href="css/bootstrap.css">
        <link rel="stylesheet" href="css/mdb.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.1.0/mdb.min.css"/>
        <link rel="stylesheet" type="text/css" href="datatables/datatables.min.css"/>
        <link rel="stylesheet" href="css/main.css">
    </head>
    <body>
        <div class="d-flex" id="wrapper">
            <!-- Sidebar-->
            <div class="border-end bg-light" id="sidebar-wrapper">
                <!-- Site logo -->
                <div class="sidebar-heading text-center"><a class="nav-link my-1 mb-4" href="dashboard.php"><img class="img-fluid me-2" width=40px src="img/icon-coffee.svg" alt="Site logo">TeaTime</a></div>
                <!-- Profile picture and info -->
                <div class="text-center">
                    <?php
                    if ($zdjecie == true) echo '<img src="img/profile_pic/'.$user_id.'" class="img-fluid rounded-circle mb-3 shadow-5" style="width: 170px; height: 170px; object-fit: cover;" alt="Avatar" />';
                    else echo '<img src="img/profile_pic/profile-pic-placeholder.jpeg" class="img-fluid rounded-circle mb-3 shadow-5" style="width: 170px; height: 170px; object-fit: cover;" alt="Avatar_placeholder" />';
                    ?>
                    <h4 class="mb-1"><strong>
                        <?php
                        echo $imie . ' ' . $nazwisko;
                        ?>
                        </strong></h4>
                    <p class="text-muted">
                    <?php
                        echo '@'.$login;
                    ?>
                    </p>
                </div>
                <!-- Navigation -->
                <div class="list-group list-group-flush px-4 hover-overlay">
                    <a class="sidebar-nav-item list-group-item list-group-item-action bg-light p-3" href="add_tea.php">Dodaj nową herbatę</a>
                    <a class="sidebar-nav-item list-group-item list-group-item-action bg-light p-3" href="all_teas.php">Baza herbat</a>
                    <a class="sidebar-nav-item list-group-item list-group-item-action bg-light p-3" href="my_teas.php">Moje herbaty</a>
                    <?php if($user_id == 1) echo '<a class="sidebar-nav-item list-group-item list-group-item-action bg-light p-3" href="admin_panel.php">Panel administratora</a>';?>
                    <a class="sidebar-nav-item list-group-item list-group-item-action bg-light p-3" href="settings.php">Ustawienia</a>
                    <a class="sidebar-nav-itemlist-group-item list-group-item-action bg-light p-3 logout-link" href="logout.php">Wyloguj</a>
                    
                </div>
            </div>

            <!-- Page content wrapper-->
            <div id="page-content-wrapper" class="mx-auto">
                <div class="container-fluid">
                    
                    <!-- Hamburger menu -->
                    <i class="bi bi-list float-right" id="sidebarToggle"></i>