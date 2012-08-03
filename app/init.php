<?php
require(__DIR__ . '/translate.php');
fText::registerComposeCallback('pre', 'translate');
