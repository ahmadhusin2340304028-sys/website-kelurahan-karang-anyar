<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Official;
use App\Models\OrganizationStructure;
use App\Models\Setting;

class ProfileController extends Controller
{
    public function index()
    {
        return view('public.profile.index');
    }

    public function history()
    {
        return view('public.profile.history');
    }

    public function visionMission()
    {
        return view('public.profile.vision-mission');
    }

    public function organization()
    {
        $structure = OrganizationStructure::active()->latest()->first();
        return view('public.profile.organization', compact('structure'));
    }

    public function officials()
    {
        $officials = Official::active()
            ->ordered()
            ->get()
            ->groupBy('position_level');

        $levels = Official::$positionLevels;

        return view('public.profile.officials', compact('officials', 'levels'));
    }

    public function contact()
    {
        return view('public.profile.contact');
    }
}
