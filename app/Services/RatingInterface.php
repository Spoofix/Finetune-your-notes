<?php
namespace App\Services;

interface RatingInterface{
    public static function rate($spoof_domain): string;
    public static function dbColumnName(): string;
}