<?php
namespace App\Http\Controllers;

use App\Core\Request;
use App\Models\Servei;

class ServeiController {
    public function getJardins()
    {
        return Servei::getByCategory('jardins');
    }
    
    public function getPiscines()
    {
        return Servei::getByCategory('piscines');
    }
}
