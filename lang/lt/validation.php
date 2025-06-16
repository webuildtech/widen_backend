<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validacijos kalbos eilutės
    |--------------------------------------------------------------------------
    |
    | Šios kalbos eilutės yra naudojamos kaip numatytieji validacijos klaidų
    | pranešimai. Kai kurios taisyklės turi kelias versijas, pavyzdžiui,
    | dydžio taisyklės. Čia galite koreguoti pranešimus pagal poreikį.
    |
    */

    'accepted' => 'Laukas :attribute turi būti priimtas.',
    'accepted_if' => 'Laukas :attribute turi būti priimtas, kai :other yra :value.',
    'active_url' => 'Laukas :attribute turi būti tinkamas URL.',
    'after' => 'Laukas :attribute turi būti data po :date.',
    'after_or_equal' => 'Laukas :attribute turi būti data po arba lygi :date.',
    'alpha' => 'Laukas :attribute gali turėti tik raides.',
    'alpha_dash' => 'Laukas :attribute gali turėti tik raides, skaičius, brūkšnelius ir pabraukimus.',
    'alpha_num' => 'Laukas :attribute gali turėti tik raides ir skaičius.',
    'array' => 'Laukas :attribute turi būti masyvas.',
    'ascii' => 'Laukas :attribute gali turėti tik vieno baito alfanumerinius simbolius.',
    'before' => 'Laukas :attribute turi būti data iki :date.',
    'before_or_equal' => 'Laukas :attribute turi būti data iki arba lygi :date.',
    'between' => [
        'array' => 'Laukas :attribute turi turėti nuo :min iki :max elementų.',
        'file' => 'Laukas :attribute turi būti nuo :min iki :max kilobaitų.',
        'numeric' => 'Laukas :attribute turi būti tarp :min ir :max.',
        'string' => 'Laukas :attribute turi būti nuo :min iki :max simbolių.',
    ],
    'boolean' => 'Laukas :attribute turi būti tiesa arba klaida.',
    'can' => 'Laukas :attribute turi netinkamą reikšmę.',
    'confirmed' => 'Lauko :attribute patvirtinimas nesutampa.',
    'contains' => 'Lauke :attribute trūksta reikiamos reikšmės.',
    'current_password' => 'Slaptažodis neteisingas.',
    'date' => 'Laukas :attribute turi būti tinkama data.',
    'date_equals' => 'Laukas :attribute turi būti data lygi :date.',
    'date_format' => 'Laukas :attribute neatitinka formato :format.',
    'decimal' => 'Laukas :attribute turi turėti :decimal dešimtaines vietas.',
    'declined' => 'Laukas :attribute turi būti atmestas.',
    'declined_if' => 'Laukas :attribute turi būti atmestas, kai :other yra :value.',
    'different' => 'Laukai :attribute ir :other turi būti skirtingi.',
    'digits' => 'Laukas :attribute turi būti :digits skaitmenų.',
    'digits_between' => 'Laukas :attribute turi būti tarp :min ir :max skaitmenų.',
    'dimensions' => 'Laukas :attribute turi netinkamus paveikslėlio matmenis.',
    'distinct' => 'Laukas :attribute turi pasikartojančią reikšmę.',
    'doesnt_end_with' => 'Laukas :attribute neturi baigtis viena iš šių reikšmių: :values.',
    'doesnt_start_with' => 'Laukas :attribute neturi prasidėti viena iš šių reikšmių: :values.',
    'email' => 'Laukas :attribute turi būti tinkamas el. pašto adresas.',
    'ends_with' => 'Laukas :attribute turi baigtis viena iš šių reikšmių: :values.',
    'enum' => 'Pasirinktas :attribute yra netinkamas.',
    'exists' => 'Pasirinktas :attribute yra netinkamas.',
    'extensions' => 'Laukas :attribute turi turėti vieną iš šių plėtinių: :values.',
    'file' => 'Laukas :attribute turi būti failas.',
    'filled' => 'Laukas :attribute turi turėti reikšmę.',
    'gt' => [
        'array' => 'Laukas :attribute turi turėti daugiau nei :value elementų.',
        'file' => 'Laukas :attribute turi būti didesnis nei :value kilobaitai.',
        'numeric' => 'Laukas :attribute turi būti didesnis nei :value.',
        'string' => 'Laukas :attribute turi būti didesnis nei :value simboliai.',
    ],
    'gte' => [
        'array' => 'Laukas :attribute turi turėti bent :value elementų.',
        'file' => 'Laukas :attribute turi būti didesnis arba lygus :value kilobaitams.',
        'numeric' => 'Laukas :attribute turi būti didesnis arba lygus :value.',
        'string' => 'Laukas :attribute turi būti didesnis arba lygus :value simboliams.',
    ],
    'hex_color' => 'Laukas :attribute turi būti tinkama šešiakampė spalva.',
    'image' => 'Laukas :attribute turi būti paveikslėlis.',
    'in' => 'Pasirinktas :attribute yra netinkamas.',
    'in_array' => 'Laukas :attribute turi egzistuoti :other.',
    'integer' => 'Laukas :attribute turi būti sveikasis skaičius.',
    'ip' => 'Laukas :attribute turi būti tinkamas IP adresas.',
    'ipv4' => 'Laukas :attribute turi būti tinkamas IPv4 adresas.',
    'ipv6' => 'Laukas :attribute turi būti tinkamas IPv6 adresas.',
    'json' => 'Laukas :attribute turi būti tinkama JSON eilutė.',
    'list' => 'Laukas :attribute turi būti sąrašas.',
    'lowercase' => 'Laukas :attribute turi būti mažosiomis raidėmis.',
    'lt' => [
        'array' => 'Laukas :attribute turi turėti mažiau nei :value elementų.',
        'file' => 'Laukas :attribute turi būti mažesnis nei :value kilobaitai.',
        'numeric' => 'Laukas :attribute turi būti mažesnis nei :value.',
        'string' => 'Laukas :attribute turi būti mažesnis nei :value simboliai.',
    ],
    'lte' => [
        'array' => 'Laukas :attribute neturi turėti daugiau nei :value elementų.',
        'file' => 'Laukas :attribute turi būti mažesnis arba lygus :value kilobaitams.',
        'numeric' => 'Laukas :attribute turi būti mažesnis arba lygus :value.',
        'string' => 'Laukas :attribute turi būti mažesnis arba lygus :value simboliams.',
    ],
    'mac_address' => 'Laukas :attribute turi būti tinkamas MAC adresas.',
    'max' => [
        'array' => 'Laukas :attribute neturi turėti daugiau nei :max elementų.',
        'file' => 'Laukas :attribute neturi būti didesnis nei :max kilobaitai.',
        'numeric' => 'Laukas :attribute neturi būti didesnis nei :max.',
        'string' => 'Laukas :attribute neturi būti ilgesnis nei :max simboliai.',
    ],
    'max_digits' => 'Laukas :attribute neturi turėti daugiau nei :max skaitmenų.',
    'mimes' => 'Laukas :attribute turi būti failas tipo: :values.',
    'mimetypes' => 'Laukas :attribute turi būti failas tipo: :values.',
    'min' => [
        'array' => 'Laukas :attribute turi turėti bent :min elementų.',
        'file' => 'Laukas :attribute turi būti bent :min kilobaitai.',
        'numeric' => 'Laukas :attribute turi būti bent :min.',
        'string' => 'Laukas :attribute turi būti bent :min simboliai.',
    ],
    'min_digits' => 'Laukas :attribute turi turėti bent :min skaitmenų.',
    'missing' => 'Laukas :attribute turi nebūti.',
    'missing_if' => 'Laukas :attribute turi nebūti, kai :other yra :value.',
    'missing_unless' => 'Laukas :attribute turi nebūti, nebent :other yra :value.',
    'missing_with' => 'Laukas :attribute turi nebūti, kai yra :values.',
    'missing_with_all' => 'Laukas :attribute turi nebūti, kai yra visi :values.',
    'multiple_of' => 'Laukas :attribute turi būti :value kartotinis.',
    'not_in' => 'Pasirinktas :attribute yra netinkamas.',
    'not_regex' => 'Lauko :attribute formatas yra netinkamas.',
    'numeric' => 'Laukas :attribute turi būti skaičius.',
    'password' => [
        'letters' => 'Laukas :attribute turi turėti bent vieną raidę.',
        'mixed' => 'Laukas :attribute turi turėti bent vieną didžiąją ir mažąją raidę.',
        'numbers' => 'Laukas :attribute turi turėti bent vieną skaičių.',
        'symbols' => 'Laukas :attribute turi turėti bent vieną simbolį.',
        'uncompromised' => 'Pateiktas :attribute buvo pažeistas duomenų nutekėjime. Pasirinkite kitą :attribute.',
    ],
    'present' => 'Laukas :attribute turi būti.',
    'prohibited' => 'Laukas :attribute yra draudžiamas.',
    'regex' => 'Lauko :attribute formatas yra netinkamas.',
    'required' => 'Laukas :attribute yra privalomas.',
    'required_array_keys' => ':attribute lauke turi būti šie įrašai: :values.',
    'required_if' => ':attribute laukas privalomas, kai :other yra :value.',
    'required_if_accepted' => ':attribute laukas privalomas, kai :other yra priimtas.',
    'required_if_declined' => ':attribute laukas privalomas, kai :other yra atmestas.',
    'required_unless' => ':attribute laukas privalomas, nebent :other yra tarp :values.',
    'required_with' => ':attribute laukas privalomas, kai yra :values.',
    'required_with_all' => ':attribute laukas privalomas, kai visi iš šių yra: :values.',
    'required_without' => ':attribute laukas privalomas, kai nėra :values.',
    'required_without_all' => ':attribute laukas privalomas, kai nėra nė vieno iš šių: :values.',
    'same' => ':attribute laukas turi sutapti su :other.',
    'size' => [
        'array' => ':attribute lauke turi būti :size elementai.',
        'file' => ':attribute failas turi būti :size kilobaitų.',
        'numeric' => ':attribute reikšmė turi būti :size.',
        'string' => ':attribute laukas turi būti sudarytas iš :size simbolių.',
    ],
    'starts_with' => ':attribute laukas turi prasidėti vienu iš šių: :values.',
    'string' => ':attribute laukas turi būti tekstinis.',
    'timezone' => ':attribute laukas turi būti galiojanti laiko juosta.',
    'unique' => ':attribute jau yra naudojamas.',
    'uploaded' => 'Nepavyko įkelti :attribute.',
    'uppercase' => ':attribute laukas turi būti didžiosiomis raidėmis.',
    'url' => ':attribute laukas turi būti galiojantis URL.',
    'ulid' => ':attribute laukas turi būti galiojantis ULID.',
    'uuid' => ':attribute laukas turi būti galiojantis UUID.',
    /*
    |--------------------------------------------------------------------------
    | Pasirinktinis validacijos tekstas
    |--------------------------------------------------------------------------
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Pasirinktiniai validacijos atributai
    |--------------------------------------------------------------------------
    */

    'attributes' => [],

];
