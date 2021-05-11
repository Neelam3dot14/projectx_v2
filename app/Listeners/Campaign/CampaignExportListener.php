<?php

namespace App\Listeners\Campaign;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Repositories\CampaignRepository;
use App\Events\CampaignExportEvents;
use App\Models\Internal\Campaign;
use App\Models\User;
use File;
use App\Notifications\ExportNotification;

class CampaignExportListener implements ShouldQueue
{
    public $campaignRepo, $exportNotification;

    public $timeout = 600;
    
    public function exportCampaign(CampaignExportEvents $campaignExportEvents)
    {
        $this->exportNotification = $campaignExportEvents->exportNotification;
        $campaign_id = $this->exportNotification->campaign_id;
        $this->campaignRepo = new CampaignRepository();
        $path = public_path('export');
        //Check if export directory not exists.
        if(!File::isDirectory($path)){
            File::makeDirectory($path, 0777, true, true);
        }
        $columns = $this->campaignRepo->getExportableColumns();
        $fileName = public_path('export').'/'. $this->exportNotification->filename.'.csv';
        $file = fopen($fileName, 'w');
        fputcsv($file, $columns);
        $this->campaignRepo->getKeywordAdsByCampaignId($campaign_id, $file);
        fclose($file);
        $this->exportNotification->status = 'FINISHED';
        $this->exportNotification->save();
        $user = User::find($this->exportNotification->user_id);
        $user->notify(new ExportNotification($this->exportNotification->hash));
    }

    public function exportAll(CampaignExportEvents $campaignExportEvents)
    {
        $this->exportNotification = $campaignExportEvents->exportNotification;
        $user_id = $this->exportNotification->user_id;
        $this->campaignRepo = new CampaignRepository();
        $path = public_path('export');
        //Check if export directory not exists.
        if(!File::isDirectory($path)){
            File::makeDirectory($path, 0777, true, true);
        }
        $columns = $this->campaignRepo->getExportableColumns();
        $fileName = public_path('export').'/'. $this->exportNotification->filename.'.csv';
        $file = fopen($fileName, 'w');
        fputcsv($file, $columns);
        $campaigns = Campaign::where('created_by', $user_id)->pluck('id')->all();
        $this->campaignRepo->getKeywordAdsByCampaignArray($campaigns, $file);
        fclose($file);
        $this->exportNotification->status = 'FINISHED';
        $this->exportNotification->save();
        $user = User::find($this->exportNotification->user_id);
        $user->notify(new ExportNotification($this->exportNotification->hash));
    }
}
