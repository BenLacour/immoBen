<?php

// Appel de la Base de Données

try {
    $bdd = new PDO("mysql:host=localhost;dbname=immobilier;charset=utf8", "root", "root");
} catch (Exception $e) {
    die($e->getMessage());
}
