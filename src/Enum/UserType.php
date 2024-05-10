<?php
namespace App\Enum;



/**
 * @method static UserType ETUDIANT()
 * @method static UserType ENSEIGNANT()
 * @method static UserType ADMIN()
 */
Enum UserType
{
    private const ETUDIANT = 'etudiant';
    private const ENSEIGNANT = 'enseignant';
    private const ADMIN = 'admin';
}
