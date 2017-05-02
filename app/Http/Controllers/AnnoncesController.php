<?php

namespace App\Http\Controllers;

use Packages\NewsCategoryTranslation;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Packages\NewsCategory;
use Packages\Publication;
use LaravelLocalization;
use App\Http\Requests;
use Packages\Annonce;

class AnnoncesController extends Controller {

    public function getList()
    {
        return view('pages.annonce.list');
    }

    public function getView(Annonce $annonce ){
        return view('pages.annonce.view', [
                'annonce' => $annonce,
            ]);
    }
}
