<?php


namespace App\Repositories;


use App\Models\CampaignKeyword;
use Carbon\Carbon;

class KeywordRepository
{
    public function getIncompleteJobs($status, $duration)
    {
        $pendingJobs = CampaignKeyword::where('status', '=', $status)
            ->where('updated_at', '<', Carbon::now()->subMinutes($duration))
            ->where('tries', '<', 3)
            ->get();
        return $pendingJobs;
    }
}
