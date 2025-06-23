<?php

namespace App\Controllers;

use PHPUnit\TextUI\XmlConfiguration\CodeCoverage\Report\Php;

class Home extends BaseController
{
    public function index(): string
    {
        return view('landing_page');
    }
}

