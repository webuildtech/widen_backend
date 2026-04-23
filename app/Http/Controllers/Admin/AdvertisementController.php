<?php

namespace App\Http\Controllers\Admin;

use App\Data\Admin\Advertisements\AdvertisementData;
use App\Data\Admin\Advertisements\AdvertisementUpdateData;
use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use App\Services\Media\LogoManager;

class AdvertisementController extends Controller
{
    public function __construct(
        protected LogoManager $logoManager
    )
    {
    }

    public function show(): AdvertisementData
    {
        return AdvertisementData::from(Advertisement::first());
    }

    public function update(AdvertisementUpdateData $data): AdvertisementData
    {
        $advertisement = Advertisement::first();

        $advertisement->update($data->except('logoFile', 'deleteLogo')->all());

        $this->logoManager->handle($advertisement, $data);

        $advertisement->refresh();

        return AdvertisementData::from($advertisement);
    }
}
