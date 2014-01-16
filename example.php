<?php
/* (c) Anton Medvedev <anton@elfet.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

// One:
User::find(1);

// Multi:
User::findAll(['name' => 'Anton']);

// Create:
$user = new User();
$user->setName('Anton');
$user->save();

// Update
$user->setPassword('new one');
$user->save();

// Map