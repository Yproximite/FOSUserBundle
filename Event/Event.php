<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FOS\UserBundle\Event;

if (class_exists(\Symfony\Contracts\EventDispatcher\Event::class)) {
    class Event extends \Symfony\Contracts\EventDispatcher\Event
    {
    }
} else {
    class Event extends \Symfony\Component\EventDispatcher\Event
    {
    }
}
