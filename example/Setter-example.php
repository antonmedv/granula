<?php
namespace Model;

use Granula\ActiveRecord;
use Granula\Setter;

// Описываем класс с приватными полями и включаем трейт Setter.
// Переопределяем сеттер для поля name.
class User
{
    use ActiveRecord;
    use Setter;

    private $id;
    private $name;
    private $email;
    
    public function setName($value) 
    {
        $this->name = strtoupper($value);
    }
} 

// Создаём экземпляр класса.
$user = new User();

// Устанавливаем заначения полей.
$user->name = 'User Name';
$user-email = 'mail@domain.com';
