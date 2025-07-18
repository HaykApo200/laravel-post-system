<?php

namespace App\Constants\User;

enum UserStatus: int
{
    case ACTIVE = 0;
    case INACTIVE = 1;
    case BANNED = 2;
}
