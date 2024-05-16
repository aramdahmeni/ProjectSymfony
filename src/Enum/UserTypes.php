<?php

namespace App\Enum;

/**
 * @method static UserTypes ETUDIANT()
 * @method static UserTypes ENSEIGNANT()
 * @method static UserTypes ADMIN()
 */
enum UserTypes
{
    public const ETUDIANT = 'etudiant';
    public const ENSEIGNANT = 'enseignant';
    public const ADMIN = 'admin';
}
