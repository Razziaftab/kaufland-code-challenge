<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class XMLParseService implements ParseServiceInterface
{
    /**
     * @return array|string
     */
    public function parseData()
    {
        $data = [];
        try {
            $results = simplexml_load_file(Storage::path(self::FILE_PATH));
            $dateTime = now()->toDateTimeString();

            foreach ($results as $result) {
                $data[] = [
                    'entity_id'         => (int) $result->entity_id,
                    'category_name'     => $result->CategoryName->__toString(),
                    'sku'               => $result->sku->__toString(),
                    'name'              => $result->name->__toString(),
                    'description'       => $result->description->__toString(),
                    'short_description' => $result->shortdesc->__toString(),
                    'price'             => (float) $result->price,
                    'link'              => $result->link->__toString(),
                    'image'             => $result->image->__toString(),
                    'brand'             => $result->Brand->__toString(),
                    'rating'            => (int) $result->Rating,
                    'caffeine_type'     => $result->CaffeineType->__toString(),
                    'count'             => (int) $result->Count,
                    'flavored'          => $result->Flavored->__toString(),
                    'seasonal'          => $result->Seasonal->__toString(),
                    'in_stock'          => $result->Instock == 'Yes' ? 1 : 0,
                    'facebook'          => $result->Facebook->__toString(),
                    'is_kcup'           => (int) $result->IsKCup,
                    'created_at'        => $dateTime,
                    'updated_at'        => $dateTime,
                ];
            }
        } catch (\Throwable $e) {
            $errorMessage = $e->getMessage();
            Log::error($errorMessage);
        }
        return $data;
    }
}
