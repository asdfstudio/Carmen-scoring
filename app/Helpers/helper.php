<?php

use Illuminate\Support\Facades\Storage;


function ordinal($number) {
    $ends = array('th','st','nd','rd','th','th','th','th','th','th');
    if ((($number % 100) >= 11) && (($number%100) <= 13))
        return $number. 'th';
    else
        return $number. $ends[$number % 10];
}


function uploadToS3($path, $contents, $options = []) {
    $storageDriver = Storage::disk("s3");

    if($storageDriver->put($path, file_get_contents($contents), $options)){
        $storedFilePath = $storageDriver->path($path);
    }
    return $storedFilePath;
 }

 function removeS3File($pathUrl) {

    if(Storage::disk('s3')->exists($pathUrl)) {
        Storage::disk('s3')->delete($pathUrl);
        return true;
    }
    return false;
 }

