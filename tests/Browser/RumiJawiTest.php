<?php

namespace Tests\Browser;

use App\Models\Page;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Storage;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class RumiJawiTest extends DuskTestCase
{
    protected static $domain = 'prpm.dbp.gov.my';
    protected static $startUrl = 'https://prpm.dbp.gov.my/';

    public function setUp(): void{
        parent::setUp();
        $this->artisan('migrate:fresh');
    }

    /** @test */
    public function DBPSpider()
    {

        $this->browse(function (Browser $browser) {
            // $arr = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
            $arr = array('A','B','C','D');

            for ($i=0; $i < count($arr); $i++) {
                $search = $arr[$i];
                for ($i=0; $i < count($arr); $i++) { 
                    
                }
            }
            $search = 'Astakona';
            $browser->visit(self::$startUrl)
                ->type('ctl00$MainContent$txtCarian', $search)
                ->click('#MainContent_cmd_search');

                Page::create([
                    'url' => self::$startUrl,
                    'title' =>$browser->driver->getTitle(),
                    'isCrawled' => false,
                ]);
                Storage::append('file.txt', $search.'  '.$browser->text('.cadr'));
                Storage::append('file.txt', $search.'  '.$browser->text('.cadr'));

            $browser->assertTitle('Carian Umum');
        });
        // name="ctl00$MainContent$txtCarian"
        // ctl00$MainContent$cmd_search
    }

}
