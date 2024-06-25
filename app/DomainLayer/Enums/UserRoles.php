<?php

namespace App\DomainLayer\Enums;

enum UserRoles: string {
    case BaseUser = 'baseUser';
    case ArtistUser = 'artistUser';
}
