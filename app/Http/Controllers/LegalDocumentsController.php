<?php

namespace App\Http\Controllers;

class LegalDocumentsController extends Controller
{
    public function privacyPolicy()
    {
        return view('privacy_policy');
    }

    public function termsOfUse()
    {
        return view('terms_of_use');
    }
}
