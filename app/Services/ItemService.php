<?php

namespace App\Services;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;

class ItemService
{
    /**
     * @param array $data
     * @return bool|MessageBag
     */
    public function validate(array $data): bool|MessageBag
    {
        $validator = Validator::make($data, [
            '*.entity_id'           => 'required|integer',
            '*.category_name'       => 'required|string',
            '*.sku'                 => 'required|string',
            '*.name'                => 'required|string',
            '*.description'         => 'nullable|string',
            '*.short_description'   => 'nullable|string',
            '*.price'               => 'required|numeric|between:0,9999999999.99',
            '*.link'                => 'required|string',
            '*.image'               => 'required|string',
            '*.brand'               => 'nullable|string',
            '*.rating'              => 'required|integer|min:0|max:5',
            '*.caffeine_type'       => 'nullable|string',
            '*.count'               => 'required|integer|min:0',
            '*.flavored'            => 'nullable|string',
            '*.seasonal'            => 'nullable|string',
            '*.in_stock'            => 'required|integer|min:0',
            '*.facebook'            => 'nullable|string',
            '*.is_kcup'             => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return $validator->errors();
        }

        return true;
    }

    /**
     * @param array $data
     * @param string $column
     * @return array
     */
    public function skipData(array $data, string $column): array
    {
        return array_filter($data, function($value) use ($column) {
            return $value[$column] !== '';
        });
    }
}
