<?php
require __DIR__.'/../../tools/dbTools.php';


echo json_encode(dbTools::getUsersList());