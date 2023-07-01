<?php

namespace App\Commands;

use App\Repositories\ItemRepoInterface;
use App\Services\ParseServiceInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use LaravelZero\Framework\Commands\Command;

class XmlProcessCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'process:xml {chunk=1000}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Process an XML file and push the data to the database';

    private $chunk;

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
                DB::transaction(function() use ($itemRepo, $data) {
//                    $itemRepo->truncate();
                    $chunks = array_chunk($data, $this->chunk);
                    foreach ($chunks as $chunk) {
                        $itemRepo->bulkInsert($chunk);
                    }
                });
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
