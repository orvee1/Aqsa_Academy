<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Achievement;
use Illuminate\Http\Request;

class AchievementController extends BaseClientController
{
    public function index()
    {
        $achievements = Achievement::where('status', true)
            ->orderBy('position')
            ->latest('id')
            ->paginate(12);

        return $this->view('client.achievements.index', compact('achievements'));
    }
}
