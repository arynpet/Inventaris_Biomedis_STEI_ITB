<?php

namespace App\Policies;

use App\Models\Item;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ItemPolicy
{
    public function terminate(User $user, Item $item): bool
    {
        return $user->role === 'superadmin';
    }
}
