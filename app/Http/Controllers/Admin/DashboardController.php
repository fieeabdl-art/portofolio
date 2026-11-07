<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Education;
use App\Models\Experience;
use App\Models\MediaAsset;
use App\Models\PortfolioMessage;
use App\Models\Project;
use App\Models\Skill;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('admin.dashboard', [
            'counts' => [
                'projects' => Project::count(),
                'experiences' => Experience::count(),
                'skills' => Skill::count(),
                'messages' => PortfolioMessage::count(),
                'unread' => PortfolioMessage::whereNull('read_at')->count(),
                'education' => Education::count(),
                'media' => MediaAsset::count(),
            ],
            'recentMessages' => PortfolioMessage::query()->latest()->limit(5)->get(),
        ]);
    }
}
