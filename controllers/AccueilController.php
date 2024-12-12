<?php

namespace controllers;

class AccueilController
{
    public function get()
    {
        require __DIR__ . '/../views/accueil.php';
    }
}