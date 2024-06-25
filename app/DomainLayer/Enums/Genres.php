<?php

namespace App\DomainLayer\Enums;

enum Genres: string {
    case rock = 'rock';
    case rap = 'rap';
    case jazz = 'jazz';
    case classical = 'classical';
    case electronic = 'electronic';
}