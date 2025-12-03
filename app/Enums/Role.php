<?php

declare(strict_types=1);

namespace App\Enums;

use App\Models\User;

enum Role: string
{
    case SuperAdmin = 'super-admin';
    case Admin = 'admin';
    case Editor = 'editor';
    case User = 'user';
}
