<?php

require __DIR__ . '/../vendor/autoload.php';

// avoiding of Yii Framework class autoloader warnings
spl_autoload_unregister(array('YiiBase','autoload'));
spl_autoload_register(function ($classname) { @YiiBase::autoload($classname); });
