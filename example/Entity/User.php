<?php
/* (c) Anton Medvedev <anton@elfet.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Entity;

use Granula\Meta;
use Granula\ActiveRecord;

class User
{
    use ActiveRecord;

    /**
     * @var integer
     */
    public $id;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $password;

    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $avatar;

    /**
     * @var Profile
     */
    public $profile;

    /**
     * @var User
     */
    public $friend;

    /**
     * @var \DateTime
     */
    public $date;

    public static function describe(Meta $meta)
    {
        $meta->table('users');
        $meta->field('id', 'integer')->primary()->options(['autoincrement' => true]);
        $meta->field('name', 'string');
        $meta->field('password', 'string');
        //$meta->field('plain_password', 'string');
        $meta->field('email', 'string')->unique()->options(['notnull' => true]);
        $meta->field('avatar', 'string')->options(['notnull' => false, 'default' => '']);
        $meta->field('profile', 'entity')->entity(Profile::class);
        $meta->field('friend', 'entity')->entity(User::class);
        $meta->field('date', 'datetime');
        $meta->index(['name', 'email'], 'name_email_index');
    }

    function __toString()
    {
        return "User ".$this->name;
    }

    /**
     * @param string $avatar
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;
    }

    /**
     * @return string
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return \Fixture\Profile
     */
    public function getProfile()
    {
        $this->load('profile');
        return $this->profile;
    }
} 