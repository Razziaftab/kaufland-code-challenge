<?php

namespace App\Repositories;


use Illuminate\Database\Eloquent\Collection;

interface ItemRepoInterface
{
    public function bulkInsert(array $data): bool;
}
