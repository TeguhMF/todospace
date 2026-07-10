<?php
// config/database.php

$host     = "localhost";
$username = "root";     // Default XAMPP
$password = "";         // Default XAMPP kosong (jangan diisi spasi)
$database = "todospace";

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}