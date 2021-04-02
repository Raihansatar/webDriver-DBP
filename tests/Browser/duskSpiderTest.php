<?php

namespace Tests\Browser;

use App\Models\Page;
use Exception;
use Facebook\WebDriver\WebDriverBy;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class duskSpiderTest extends DuskTestCase
{
    // /**
    //  * A Dusk test example.
    //  *
    //  * @return void
    //  */
    // public function testExample()
    // {
    //     $this->browse(function (Browser $browser) {
    //         $browser->visit('/')
    //                 ->assertSee('Laravel');
    //     });
    // }

    // Specify the $startUrl and $domain as per the website you are trying to crawl.
    protected static $domain = 'reheen.com';
    protected static $startUrl = 'https://reheen.com/';

    // setUp method is used to refresh the database on each test run.
    public function setUp(): void{
        parent::setUp();
        $this->artisan('migrate:fresh');
    }

    // start the crawling insude urlSpider test method, which then calls the getLinks method.
    /** @test */
    public function urlSpider()
    {

        $startingLink = Page::create([
            'url' => self::$startUrl,
            'isCrawled' => false,
        ]);

        $this->browse(function (Browser $browser) use ($startingLink) {
            $this->getLinks($browser, $startingLink);
        });
    }

    // getLinks recursively process the url, fetches all the links on current page and adds them to database table.
    protected function getLinks(Browser $browser, $currentUrl){

        $this->processCurrentUrl($browser, $currentUrl);

        try{

            foreach(Page::where('isCrawled', false)->get() as $link) {
                $this->getLinks($browser, $link);
            }

        }catch(Exception $e){

        }
    }

    protected function processCurrentUrl(Browser $browser, $currentUrl){

        //Check if already crawled
        if(Page::where('url', $currentUrl->url)->first()->isCrawled == true)
            return;

        //Visit URL
        $browser->visit($currentUrl->url);

        //Get Links and Save to DB if Valid
        $linkElements = $browser->driver->findElements(WebDriverBy::tagName('a'));
        foreach($linkElements as $element){
            $href = $element->getAttribute('href');
            $href = $this->trimUrl($href);
            if($this->isValidUrl($href)){
                //var_dump($href);
                Page::create([
                    'url' => $href,
                    'isCrawled' => false,
                ]);
            }
        }

        //Update current url status to crawled
        $currentUrl->isCrawled = true;
        $currentUrl->status  = $this->getHttpStatus($currentUrl->url);
        $currentUrl->title = $browser->driver->getTitle();
        $currentUrl->save();
    }

    // isValidUrl , trimUrl are helper methods to check if the link is valid.
    protected function isValidUrl($url){
        $parsed_url = parse_url($url);

        if(isset($parsed_url['host'])){
            if(strpos($parsed_url['host'], self::$domain) !== false && !Page::where('url', $url)->exists()){
                return true;
            }
        }
        return false;
    }

    protected function trimUrl($url){
        $url = strtok($url, '#');
        $url = rtrim($url,"/");
        return $url;
    }

    // Since dusk does not return Http status codes, we make use of get_headers php function to fetch those inside getHttpStatus method.
    protected function getHttpStatus($url){
        $headers = get_headers($url, 1);
        return intval(substr($headers[0], 9, 3));
    }

}
