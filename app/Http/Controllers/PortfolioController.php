<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use App\Models\Education;
use App\Models\Experience;
use App\Models\Profile;
use App\Models\Project;
use App\Models\ProjectCategory;
use App\Models\Skill;
use App\Models\Statistic;
use App\Models\WebsiteSetting;
use App\Models\WorkProcess;

class PortfolioController extends Controller
{
    public function index()
    {
        return view('portfolio.index', [
            'profile' => Profile::query()->firstOrFail(),
            'statistics' => Statistic::query()->where('active', true)->orderBy('sort_order')->get(),
            'projects' => Project::query()->with(['category', 'images'])->where('featured', true)->orderBy('sort_order')->latest('project_date')->get(),
            'categories' => ProjectCategory::query()->whereHas('projects', fn ($query) => $query->where('featured', true))->orderBy('sort_order')->get(),
            'skills' => Skill::query()->where('active', true)->orderBy('sort_order')->get(),
            'educations' => Education::query()->orderBy('sort_order')->get(),
            'experiences' => Experience::query()->orderBy('sort_order')->get(),
            'achievements' => Achievement::query()->where('featured', true)->orderBy('sort_order')->orderByDesc('achieved_at')->get(),
            'processes' => WorkProcess::query()->where('active', true)->orderBy('sort_order')->get(),
            'settings' => WebsiteSetting::query()->pluck('value', 'key'),
        ]);
    }
}
