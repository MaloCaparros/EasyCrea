<?php

declare(strict_types=1);

namespace App\Model;

class Administrateur extends Model
{
    use TraitInstance;

    protected $tableName = APP_TABLE_PREFIX . 'administrateur';

    // Ajoute des méthodes spécifiques pour gérer les administrateurs
}