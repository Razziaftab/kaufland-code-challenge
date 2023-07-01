<?php

namespace App\Services;

interface ParseServiceInterface
{
    const FILE_PATH = 'feed.xml';

    public function parseData();
}
