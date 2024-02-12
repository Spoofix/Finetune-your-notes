<?php
namespace App\Services;

interface ScanInterface{
    public static function search($domain, $ref_id=null): array;
}