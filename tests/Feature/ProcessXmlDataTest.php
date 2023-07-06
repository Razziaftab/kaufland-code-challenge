<?php

namespace Tests\Feature;

use App\Repositories\ItemRepo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ProcessXmlDataTest extends TestCase
{
    private $itemRepo;

    public function setUp(): void
    {
        parent::setUp();
        $this->itemRepo = App::make(ItemRepo::class);
    }

    public function test_process_xml_data()
    {
        $this->itemRepo->truncate();

        // Run the command to process the XML file
        $this->artisan('process:xml')
            ->expectsOutput('XML data processed and pushed to the database.')
            ->assertExitCode(0);

        // Assert that the data has been inserted into the 'items' table
        $this->assertDatabaseCount('items', 3449);
        $this->assertDatabaseHas('items', ['entity_id' => 340]);
        $this->assertDatabaseHas('items', ['sku' => '12308']);
        $this->assertDatabaseHas('items', ['name' => 'Hevla Irish Creme Decaf Low Acid Ground Coffee 5lb Bag']);
        $this->assertDatabaseHas('items', ['brand' => 'Bear Naked']);
    }

    public function test_process_xml_data_with_chunks()
    {
        $this->itemRepo->truncate();

        $this->artisan('process:xml 50')
            ->expectsOutput('XML data processed and pushed to the database.')
            ->assertExitCode(0);

        $this->assertDatabaseHas('items', ['entity_id' => 468]);
        $this->assertDatabaseHas('items', ['sku' => '06-1634']);
        $this->assertDatabaseHas('items', ['name' => 'Heavy Weight Plastic Forks 1000ct']);
        $this->assertDatabaseHas('items', ['category_name' => 'Bunn Coffee Machines']);
    }
}
