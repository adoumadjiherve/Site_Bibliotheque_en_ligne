<?php
// Script CRUD centralisé
include 'config.php';

function getAllBooks($conn) {
    $sql = "SELECT * FROM Livres ORDER BY titre";
    $result = $conn->query($sql);
    return $result;
}

function getBookById($conn, $id) {
    $sql = "SELECT * FROM Livres WHERE id =