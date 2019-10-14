<?php

require 'boot.php';

$dog_model = purple\ModelFactory::get('purple\DogModel');

$dog_model->install();

$dog_model->createDog('spike');
$dog_model->createDog('buster');

$data = $dog_model->getDogs();

print_r($data);
