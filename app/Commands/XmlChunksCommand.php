<?php

namespace App\Commands;

use App\Item;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use LaravelZero\Framework\Commands\Command;
use Prewk\XmlStringStreamer;
use Prewk\XmlStringStreamer\Stream;
use Prewk\XmlStringStreamer\Parser;

class XmlChunksCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'xml-chunks-command';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws \Exception
     */
    public function handle()
    {
        Item::truncate();

        $CHUNK_SIZE = 1024;

        // Prepare our stream to be read with a 1kb buffer
        $stream = new Stream\File(Storage::path('feed.xml'), $CHUNK_SIZE);

        // Construct the default parser (StringWalker)
        $parser = new Parser\StringWalker();

        // Create the streamer
        $streamer = new XmlStringStreamer($parser, $stream);

        while ($node = $streamer->getNode()) {
            // $node will be a string like this: "<customer><firstName>Jane</firstName><lastName>Doe</lastName></customer>"
            $simpleXmlNode = simplexml_load_string($node);
            $data[] = [
                'entity_id' => (int) $simpleXmlNode->entity_id,
                'category_name' => $simpleXmlNode->CategoryName->__toString(),
                'sku' => $simpleXmlNode->sku->__toString(),
                'name' => $simpleXmlNode->name->__toString(),
                'description' => $simpleXmlNode->description->__toString(),
                'short_description' => $simpleXmlNode->shortdesc->__toString(),
                'price' => (float) $simpleXmlNode->price,
                'link' => $simpleXmlNode->link->__toString(),
                'image' => $simpleXmlNode->image->__toString(),
                'brand' => $simpleXmlNode->Brand->__toString(),
                'rating' => (int) $simpleXmlNode->Rating,
                'caffeine_type' => $simpleXmlNode->CaffeineType->__toString(),
                'count' => (int) $simpleXmlNode->Count,
                'flavored' => $simpleXmlNode->Flavored->__toString(),
                'seasonal' => $simpleXmlNode->Seasonal->__toString(),
                'in_stock' => $simpleXmlNode->Instock == 'Yes' ? 1 : 0,
                'facebook' => $simpleXmlNode->Facebook->__toString(),
                'is_kcup' => (int) $simpleXmlNode->IsKCup,
            ];
        }

        $chunks = array_chunk($data, 1000);
        foreach ($chunks as $chunk) {
            Item::insert($chunk);
        }

    }

    /**
     * Define the command's schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }
}
