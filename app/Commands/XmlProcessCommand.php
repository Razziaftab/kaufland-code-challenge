<?php

namespace App\Commands;

use App\Repositories\ItemRepoInterface;
use App\Services\ItemService;
use App\Services\ParseServiceInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\MessageBag;
use LaravelZero\Framework\Commands\Command;

class XmlProcessCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'process:xml {chunk=100}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Process an XML file and push the data to the database';

    private $chunk;


    // This function can be small
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(ItemRepoInterface $itemRepo, ParseServiceInterface $parseService)
    {
        $this->chunk = $this->argument('chunk');
        try {
            DB::beginTransaction();
            $data = $parseService->parseData();
            if ($data) {
                $itemService = new ItemService();
                $chunks = array_chunk($data, $this->chunk);
                foreach ($chunks as $key => $chunk) {
                    $validatedData = $itemService->validate($chunk);
                    if ($validatedData instanceof MessageBag) {
                        Log::error($validatedData.' Chunk Key & Size:', ['key' => $key, 'size' => $this->chunk]);

                        // If you want to skip the data which are not validated
//                        $chunk = $itemService->skipData($chunk, 'name');

                        // I did not want to stop the execution that's why not return the exception here
                    }
                    DB::transaction(function() use ($itemRepo, $chunk) {
//                          $itemRepo->truncate();
                        $itemRepo->bulkInsert($chunk);
                    });
                }
                DB::commit();
                $this->info('XML data processed and pushed to the database.');
            } else {
                Log::error('Failed to load XML');
                $this->error('Failed to load XML. Please see the log file!');
            }
        } catch(\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            $this->error('XML data cannot pushed into database. Please see the log file!');
        }
    }
}
