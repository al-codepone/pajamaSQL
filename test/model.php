<?php

require 'vendor/autoload.php';

//must pass full namespace to get()
$wordModel = purple\ModelFactory::get('purple\WordModel');

$wordModel->install();
$wordModel->create(array('red', 'swim', 'apple', 'red'));
$wordModel->update('red', 'coal');
$wordModel->delete('swim');

var_dump($wordModel->get());
