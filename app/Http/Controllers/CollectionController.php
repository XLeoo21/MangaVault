<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class CollectionController extends Controller
{
    public function index(): View
    {
        return view('collections.index');
    }
}
