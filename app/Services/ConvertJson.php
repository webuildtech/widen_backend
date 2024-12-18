<?php

namespace App\Services;

class ConvertJson
{
    public function __construct($json = false)
    {
        if ($json) $this->set(json_decode($json, true));
    }

    public function set($data)
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $converter = new ConvertJson;
                $converter->set($value);
                $value = $converter;
            }
            $this->{$key} = $value;
        }
    }
}
