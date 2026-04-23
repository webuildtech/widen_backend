<?php

namespace App\Http\Controllers\User;

use App\Data\User\Advertisements\AdvertisementData;
use App\Http\Controllers\Controller;
use App\Models\Advertisement;

class AdvertisementController extends Controller
{
    public function __invoke(): AdvertisementData
    {
        $advertisement = Advertisement::whereIsActive(true)->first();

        return AdvertisementData::from($advertisement);
    }
}
