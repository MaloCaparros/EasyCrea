<?php

declare(strict_types=1);

namespace App\Model;

class CarteAleatoire extends Model
{
    use TraitInstance;

    protected $tableName = APP_TABLE_PREFIX . 'carte_aleatoire';

    // Ajoute des méthodes spécifiques pour gérer les cartes aléatoires
}
