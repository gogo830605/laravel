<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request as GuzzleRequest;
use Goutte\Client as GoutteClient;
use App\Models\Constellation;

class Spider extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'spider:constellation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Spider Constellation';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $client = new GoutteClient();
        for ($i = 0; $i < 12; $i++) {
            $url = 'https://astro.click108.com.tw/daily_'.$i.'.php?iAcDay='.date("Y-m-d").'&iAstro='.$i;
            $crawler = $client->request('GET', $url);
            $constellation = $crawler->filter('.HOROSCOPE_BTN ul li h3')->text();
            for ($j = 1; $j < 5; $j++) {
                $n1 = $j * 2;
                $n2 = $n1 + 1;
                switch ($j) {
                    case 1:
                        $fortune = '整體運勢';
                        break;  
                    case 2:
                        $fortune = '愛情運勢';
                        break;
                    case 3:
                        $fortune = '事業運勢';
                        break;
                    case 4:
                        $fortune = '財運運勢';
                        break;
                }
                Constellation::create([
                    'constellation' => $constellation,
                    'fortune' => $fortune,
                    'star' => substr_count($crawler->filter('.TODAY_CONTENT p:nth-child('.$n1.')')->text(), '★'),
                    'description' => $crawler->filter('.TODAY_CONTENT p:nth-child('.$n2.')')->text(),
                ]);
            }
        }
    }
}
