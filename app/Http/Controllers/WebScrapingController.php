<?php

namespace App\Http\Controllers;

use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Laravel\Dusk\Browser;
use Laravel\Dusk\Chrome\ChromeProcess;
use Laravel\Dusk\ElementResolver;
use Tests\CreatesApplication;
use Laravel\Dusk\TestCase;

class WebScrapingController extends Controller
{

    protected static $domain = 'prpm.dbp.gov.my';
    protected static $startUrl = 'https://prpm.dbp.gov.my/';

    public function index()
    {
        $deviceUser = '\RaihanSatar'; //  change this according to your device user to store temporary file
        $process = (new ChromeProcess)->toProcess();
        $process->start(null, [
            'SystemRoot' => 'C:\\WINDOWS',
            'TEMP' => 'C:\Users'.$deviceUser.'\AppData\Local\Temp',
        ]);
        $options = (new ChromeOptions)->addArguments(['--disable-gpu', '--headless']);
        $capabilities = DesiredCapabilities::chrome()->setCapability(ChromeOptions::CAPABILITY, $options);
        $driver = retry(5, function () use($capabilities) {
            return RemoteWebDriver::create('http://localhost:9515', $capabilities);
        }, 50);


        $browser = new Browser($driver);
        $search = 'aba';
        // $browser = new Browser($driver, new ElementResolver($driver, ''));
        $browser->visit(self::$startUrl)
        ->type('ctl00$MainContent$txtCarian', $search)
        ->click('#MainContent_cmd_search');

        // if($browser->text('.cadr') == null){
        //     Storage::append('file.txt', $search.' Tiada dalam DBP');
        // }else{
            Storage::append('file.txt', $search.'  '.$browser->text('.cadr'));
        // }
        $browser->quit();
        $process->stop();

    
    }
}
