<?php
/*require_once ('core/utils.php');
//utils::recursiveDelDir('templates/vacancies');
unlink('core/modules/vacancies.php');*/

require_once ('core/utils.php');
require_once ('core/system/config.php');
config::load();
var_dump(utils::sendSimpleEmail('test', 'test', 'oleg778@mail.ru'));


