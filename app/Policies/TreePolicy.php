<?php

namespace App\Policies;

use App\Tournament;
use App\Tree;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class TreePolicy
{
//    use HandlesAuthorization;

//    public function before(User $user, $ability)
//    {
//        if ($user->isSuperAdmin()) {
//            return true;
//        }
//        return null;
//    }

    // You can store a user if you are not a simple user
    public function edit(User $user, Tournament $tournament)
    {
        dd("Hola");
        return ($tournament->user_id == $user->id);
    }

    // You can store a user if you are not a simple user
    public function store(User $user, Tree $tree, Tournament $tournament)
    {
        dd($tournament);
//        return ($tournament->user_id == Auth::user()->id);
    }

    public function updateTree(User $user, Tree $tree)
    {
        $tournament = $tree->championship->tournament;
        dd($tournament);
        return ($tournament->user_id == $user->id);
    }

    public function destroy(User $user, Tree $tree)
    {
        $tournament = $tree->championship->tournament;
        dd($tournament);
        return ($tournament->user_id == $user->id);

    }


}
