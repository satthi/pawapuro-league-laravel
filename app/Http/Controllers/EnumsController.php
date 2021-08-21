<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Filesystem\Filesystem;

class EnumsController extends Controller
{
    public function index()
    {
        $enums = [];

        $enumDirectoryPath = app_path('Enums');
        $files = (new Filesystem())->files($enumDirectoryPath);
        foreach ($files as $file) {
            $className = preg_replace('/\.php$/', '', $file->getBasename());
            $enumClass = '\\App\\Enums\\' . $className;

            $enums[$className] = [];
            $values = $enumClass::getValues();
            foreach ($values as $value) {
                $enums[$className][] = [
                    'value' => $value,
                    'text' => $enumClass::getDescription($value)
                ];
            }
        }
        return $enums;
    }
}
