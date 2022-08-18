<?php

$zip = new ZipArchive;
if ($zip->open('app.zip') === TRUE) {
    $zip->extractTo('app');
    $zip->close();
    echo 'ok';
}
?>