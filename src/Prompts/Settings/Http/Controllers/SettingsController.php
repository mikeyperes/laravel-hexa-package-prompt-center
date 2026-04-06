<?php

namespace hexa_package_prompt_center\Prompts\Settings\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\View\View;

class SettingsController extends Controller
{
    /**
     * @return View
     */
    public function index(): View
    {
        return view('prompt-center::settings.index');
    }
}
