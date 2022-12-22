<?php

//Clean data inputs
function cleanData($data){
    $dataOut = htmlspecialchars($data);
    $dataOut = stripslashes($dataOut);
    $dataOut = strip_tags($dataOut);
    $dataOut = trim($dataOut);

    return $dataOut;
}