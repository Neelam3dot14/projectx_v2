<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Internal\Geotarget;

class YahooDomainSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $yahoo_domain_list =[
                ["https://in.search.yahoo.com", "in"], 
                ["https://be.search.yahoo.com", "be"], 
                ["https://fr.search.yahoo.com", "fr"], 
                ["https://br.search.yahoo.com", "br"], 
                ["https://ca.search.yahoo.com", "ca"], 
                ["https://de.search.yahoo.com", "de"], 
                ["https://es.search.yahoo.com", "es"], 
                ["https://fr.search.yahoo.com", "fr"], 
                ["https://in.search.yahoo.com", "in"], 
                ["https://id.search.yahoo.com", "id"], 
                ["https://ie.search.yahoo.com", "ie"], 
                ["https://it.search.yahoo.com", "it"], 
                ["https://nl.search.yahoo.com", "nl"], 
                ["https://no.search.yahoo.com", "no"], 
                ["https://at.search.yahoo.com", "at"], 
                ["https://ph.search.yahoo.com", "ph"], 
                ["https://pl.search.yahoo.com", "pl"], 
                ["https://qc.search.yahoo.com", "qc"], 
                ["https://ro.search.yahoo.com", "ro"], 
                ["https://ch.search.yahoo.com", "ch"], 
                ["https://sg.search.yahoo.com", "sg"], 
                ["https://za.search.yahoo.com", "za"], 
                ["https://fi.search.yahoo.com", "fi"], 
                ["https://se.search.yahoo.com", "se"], 
                ["https://tr.search.yahoo.com", "tr"], 
                ["https://uk.search.yahoo.com", "gb"], 
                ["https://vn.search.yahoo.com", "vn"], 
                ["https://gr.search.yahoo.com", "gr"], 
                ["https://ru.search.yahoo.com", "ru"], 
                ["https://ua.search.yahoo.com", "ua"], 
                ["https://il.search.yahoo.com", "il"], 
                ["https://uk.search.yahoo.com", "gb"], 
                ["https://hk.search.yahoo.com", "hk"], 
                ["https://tw.search.yahoo.com", "tw"], 
                ["https://malaysia.search.yahoo.com", "my"], 
                ["https://espanol.search.yahoo.com", "espanol"], 
                ["https://ar.search.yahoo.com", "ar"], 
                ["https://cl.search.yahoo.com", "cl"], 
                ["https://dk.search.yahoo.com", "dk"], 
                ["https://search.yahoo.co.jp", "jp"], 
                ["https://mx.search.yahoo.com", "mx"], 
                ["https://sa.search.yahoo.com", "sa"], 
                ["https://us.search.yahoo.com", "us"]
        ];
        foreach ($yahoo_domain_list as $list){
            Geotarget::where('country_code', strtoupper($list[1]))->update(['yahoo_domain' => $list[0]]);
        }

    }
}
