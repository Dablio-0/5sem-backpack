<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GeneticController extends GenericController
{
    public function index()
    {
        $name = 'Willians Silva';
        $age = 25;
        $city = 'São Paulo';

        $this->setData('name');
        $this->setData('age');
        $this->setData('city');

        return view('genetic.index', compact($this->getData()));
    }
}
