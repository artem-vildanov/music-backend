<?php

namespace App\DomainLayer\Enums;

enum FileStatuses: string
{
    case inuse = 'inuse';
    case unused = 'unused';
    case removed = 'removed';
}
