<?php

namespace App\Repositories;

use App\Item;
use Illuminate\Database\Eloquent\Collection;

class ItemRepo implements ItemRepoInterface
{
    private Item $item;

    /**
     * @param Item $item
     */
    public function __construct(Item $item)
    {
        $this->item = $item;
    }

    /**
     * @param array $data
     * @return bool
     */
    public function bulkInsert(array $data): bool
    {
        return $this->item->insert($data);
    }

    public function truncate()
    {
        return $this->item->truncate();
    }
}
