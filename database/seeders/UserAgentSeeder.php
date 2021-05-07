<?php

namespace Database\Seeders;

use Flynsarmy\CsvSeeder\CsvSeeder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\UserAgent;
class UserAgentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function __construct()
	{
		$this->table = 'user_agents';
		$this->filename = __DIR__.'/csv/user_agent_08_04_2021.csv';
	}

    public function run()
    {
		// Uncomment the below to wipe the table clean before populating
		DB::table($this->table)->truncate();

        $file = fopen($this->filename, "r") or die("unable to open a file");
        fgetcsv($file);
        while (($column = fgetcsv($file, ",")) !== FALSE) {
            $data = [
                'user_agent' => $column[0],
                'device' => $column[1],
                'browser' => $column[2],
            ];
            UserAgent::create($data);
        }
    }
}
