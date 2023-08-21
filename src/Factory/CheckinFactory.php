<?php

namespace App\Factory;

use App\Entity\Beer;
use App\Entity\Checkin;
use App\Entity\User;

class CheckinFactory
{
    public function createCheckin(
        float $score,
        Beer $beer,
        User $user
    ): Checkin
    {
        $checkin = new Checkin();
        $checkin->setScore($score);
        $checkin->setBeer($beer);
        $checkin->setUser($user);

        return $checkin;
    }
}
