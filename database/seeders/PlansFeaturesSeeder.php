<?php

namespace Database\Seeders;

use App\Models\Plan;
use App\Models\PlanFeature;

class PlansFeaturesSeeder extends BasicSeeder
{
    public function run(): void
    {
        if ($this->isNotSeeded()) {
            $plans = [
                [
                    'id' => 2,
                    'features' => [
                        [
                            'label' => 'Jei norite užsisakyti pavienius (nenuolatinius) teniso ar stalo teniso laikus – jokių apribojimų nėra.',
                            'subFeatures' => [],
                        ],
                        [
                            'label' => 'Tačiau norint rezervuoti nuolatinius laikus, galioja šie laiko intervalai:',
                            'subFeatures' => [
                                'Darbo dienomis: nuo 07:00 iki 14:00 ir nuo 22:00 iki 23:00',
                                'Šeštadieniais: nuo 13:00',
                                'Sekmadieniais: iki 16:00',
                                '*Rezervacijos laikai gali keistis.',
                            ],
                        ],
                        [
                            'label' => 'Nuolatinių rezervacijų galimybė:',
                            'subFeatures' => [
                                'Nuolatinius aikštelių laikus galima rezervuoti po „Prime“ ir „Flow“ narių.',
                                'Rezervuojant nenuolatinius laikus – apribojimų nėra.',
                            ],
                        ],
                        [
                            'label' => 'Atšaukimo sąlygos:',
                            'subFeatures' => [
                                'Rezervaciją galima atšaukti likus ne mažiau kaip 48 val. iki aikštelės rezervacijos laiko.',
                            ],
                        ],
                        [
                            'label' => 'Prieiga prie klubo narių rūbinių.',
                            'subFeatures' => [],
                        ],
                        [
                            'label' => 'Naudojimasis bendra pirtimi.',
                            'subFeatures' => [],
                        ],
                    ],
                ],

                [
                    'id' => 3,
                    'features' => [
                        [
                            'label' => 'Jei norite užsisakyti pavienius (nenuolatinius) teniso ar stalo teniso laikus – jokių apribojimų nėra.',
                            'subFeatures' => [],
                        ],
                        [
                            'label' => 'Tačiau norint rezervuoti nuolatinius laikus, galioja šie laiko intervalai:',
                            'subFeatures' => [
                                'Darbo dienomis: nuo 07:00 iki 19:00 ir nuo 22:00 iki 23:00',
                                'Savaitgaliais: neribotai',
                                '*Rezervacijos laikai gali keistis.',
                            ],
                        ],
                        [
                            'label' => 'Nuolatinių rezervacijų galimybė:',
                            'subFeatures' => [
                                'Nuolatinius aikštelių laikus galima rezervuoti po „Prime“ narių.',
                                'Rezervuojant nenuolatinius laikus – apribojimų nėra.',
                            ],
                        ],
                        [
                            'label' => 'Atšaukimo sąlygos:',
                            'subFeatures' => [
                                'Rezervaciją galima atšaukti ne vėliau kaip 24 val. iki aikštelės rezervacijos laiko.',
                            ],
                        ],
                        [
                            'label' => 'Prieiga prie klubo narių rūbinių.',
                            'subFeatures' => [],
                        ],
                        [
                            'label' => 'Naudojimasis bendra pirtimi.',
                            'subFeatures' => [],
                        ],
                        [
                            'label' => 'Galimybė dalyvauti WIDEN arenos bendruomenės turnyruose.',
                            'subFeatures' => [],
                        ],
                        [
                            'label' => 'Gym erdvė:',
                            'subFeatures' => [
                                'Neribotas naudojimasis treniruoklių sale.',
                                'Specialios nuolaidos grupinėms treniruotėms.',
                            ],
                        ],
                        [
                            'label' => 'Co-working erdvė:',
                            'subFeatures' => [
                                'Prieiga prie ramių darbo ar mokymosi zonų arenos viduje.',
                            ],
                        ],
                    ],
                ],

                [
                    'id' => 4,
                    'features' => [
                        [
                            'label' => 'Tiek pavieniams (nenuolatiniams), tiek nuolatiniams teniso ir stalo teniso aikštelių laikams – jokių apribojimų nėra.',
                            'subFeatures' => [],
                        ],
                        [
                            'label' => 'Rezervuoti galite bet kuriuo metu, visomis savaitės dienomis.',
                            'subFeatures' => [],
                        ],
                        [
                            'label' => 'Pirmumo teisė rezervuoti aikštelių laikus – prieš visus kitus narius.',
                            'subFeatures' => [],
                        ],
                        [
                            'label' => 'Atšaukimo sąlygos:',
                            'subFeatures' => [
                                'Rezervaciją galima atšaukti likus ne mažiau kaip 12 val. iki pasirinkto laiko.',
                            ],
                        ],
                        [
                            'label' => 'VIP rūbinės.',
                            'subFeatures' => [],
                        ],
                        [
                            'label' => 'Atskira VIP pirtis.',
                            'subFeatures' => [],
                        ],
                        [
                            'label' => 'VIP lounge zona.',
                            'subFeatures' => [],
                        ],
                        [
                            'label' => 'Gym erdvė.',
                            'subFeatures' => [],
                        ],
                        [
                            'label' => 'Co-working erdvė.',
                            'subFeatures' => [],
                        ],
                        [
                            'label' => 'Rankšluosčiai.',
                            'subFeatures' => [],
                        ],
                        [
                            'label' => 'Galimybė dalyvauti WIDEN arenos bendruomenės turnyruose.',
                            'subFeatures' => [],
                        ],
                        [
                            'label' => 'VIP klubo renginiai.',
                            'subFeatures' => [],
                        ],
                        [
                            'label' => 'Specialios VIP treniruotės.',
                            'subFeatures' => [],
                        ],
                    ],
                ],

                [
                    'id' => 5,
                    'features' => [
                        [
                            'label' => 'Skirta vaikams ir suaugusiesiems, kurių vaikai sportuoja WIDEN arenoje veikiančiose akademijose.',
                            'subFeatures' => [],
                        ],
                        [
                            'label' => 'Prieiga prie WIDEN arenos co-working erdvės – rami, įkvepianti erdvė namų darbams, kūrybai ar darbui, laukimo laikas čia tampa prasmingu.',
                            'subFeatures' => [],
                        ],
                        [
                            'label' => 'Prieiga prie klubo narių rūbinių.',
                            'subFeatures' => [],
                        ],
                        [
                            'label' => 'Naudojimasis bendra pirtimi.',
                            'subFeatures' => [],
                        ],
                        [
                            'label' => 'Prieiga prie WIDEN arenos GYM erdvės:',
                            'subFeatures' => [
                                'Pastaba vaikams: naudojimasis GYM erdve galimas tik su suaugusiojo ar trenerio priežiūra.',
                            ],
                        ],
                    ],
                ],
            ];

            foreach ($plans as $plan) {
                $planId = $plan['id'];

                if (Plan::where('id', $planId)->exists()) {
                    foreach ($plan['features'] as $feature) {
                        $parent = PlanFeature::create([
                            'plan_id' => $planId,
                            'label' => $feature['label'],
                            'parent_id' => null,
                        ]);

                        foreach ($feature['subFeatures'] as $subLabel) {
                            PlanFeature::create([
                                'plan_id' => $planId,
                                'label' => $subLabel,
                                'parent_id' => $parent->id,
                            ]);
                        }
                    }
                }
            }

            $this->saveSeed();
        }
    }
}
