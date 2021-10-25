<?php

    require_once 'Fill.php';

    $fill   = new Fill();
    $result = $fill->loadFile('testcases.txt');

    if (!$result['status']) {
        echo $result['msg'];
    } else {
        echo($fill->floodAll());
    }
