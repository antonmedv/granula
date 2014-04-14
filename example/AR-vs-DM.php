<?php

// Пример использования Data Mapper с Doctrine ORM.

// Для работы с Data Mapper нужно всегда получать/создавать EntityManager класс.
$entityManager = new EntityManager();

// Создаём объект.
$message = new Message();
$message->user = $user;
$message->datetime = new \DateTime();
$message->text = $text;

// Размещаем объект в EntityManager.
$entityManager->persist($message);

// Производим выполнение всех необходимых запросов для синхронизации с БД.
$entityManager->flush();



// Пример использования Active Record с Granula ORM.

// Создаём объект.
$message = new Message();
$message->user = $user;
$message->datetime = new \DateTime();
$message->text = $text;

// Созраняем объект в БД.
$message->save();
