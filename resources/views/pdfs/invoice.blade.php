<html lang="lt">
<head>
    <title>PVM sąskaita faktūra</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <meta charset="UTF-8">
</head>

<body>

@php
    $borderColor = 'border-[#4887af]';
    $bgColor = 'bg-[#e8eff6]';

    $owner = $invoice->owner;
@endphp

<div class="max-w-4xl mx-auto pt-4 px-8">
    <div class="flex justify-between items-center mb-16">
        <img src="{{base_path('public/logo.png')}}" alt="Logo" class="w-32">

        <div class="w-1/2 border-b-2 {!! $borderColor !!} space-y-1">
            <div class="flex justify-between">
                <div class="text-left">
                    PVM sąskaita faktūra
                </div>
                <div class="text-right font-semibold">
                    SS {{$invoice->number}}
                </div>
            </div>

            <div class="flex justify-between text-xs">
                <div class="text-left">
                    Sąskaita išrašyta
                </div>
                <div class="text-right font-medium">
                    {{$invoice->date->format('Y-m-d')}}
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-4 mb-12">
        <div class="space-y-2 border-2 {!! $borderColor !!} rounded-xl p-4">
            <div class="font-bold text-xl text-center">Pirkėjas</div>

            <div class="font-semibold">
                {{$owner->is_company ? $owner->company_name : $owner->full_name}}
            </div>

            @if($owner->is_company)
                <div class="text-xs">
                    {{$owner->company_code}}
                </div>

                <div class="text-xs">
                    {{$owner->company_address}}
                </div>

                @if($owner->company_vat_code)
                    <div class="text-xs">
                        PVM kodas: <span class="font-semibold">{{$owner->company_vat_code}}</span>
                    </div>
                @endif
            @else
                <div class="text-xs">
                    {{$owner->email}}
                </div>

                <div class="text-xs">
                    {{$owner->phone}}
                </div>
            @endif
        </div>

        <div class="space-y-2 border-2 {!! $borderColor !!} rounded-xl p-4">
            <div class="font-bold text-xl text-center">Pardavėjas</div>

            <div class="font-semibold">Septyni Šeši, UAB</div>

            <div class="text-xs">306160235</div>

            <div class="text-xs">Verkių g. 57, LT-12201 Vilnius</div>

            <div class="text-xs">PVM kodas: <span class="font-semibold">LT100015486216</span></div>
        </div>
    </div>

    <div class="grid grid-cols-8 gap-1 font-semibold text-xs border-b-2 {!! $borderColor !!} mb-2">
        <div class="col-span-5">PREKĖS / PASLAUGOS PAVADINIMAS</div>
        <div class="text-center">Kaina</div>
        <div class="text-center">PVM (21%)</div>
        <div class="text-right">Suma su PVM</div>
    </div>

    <div class="grid grid-cols-8 gap-1 text-xs border-b border-black mb-2">
        <div class="col-span-5">
            Teniso paslaugos
        </div>
        <div class="text-center">{{formatPrice($invoice->price)}}</div>
        <div class="text-center">{{formatPrice($invoice->vat)}}</div>
        <div class="text-right">{{formatPrice($invoice->price_with_vat)}}</div>
    </div>

    <div class="flex justify-end text-xs">
        <div class="w-2/3 border-b border-black mb-1 space-y-1">
            <div class="flex justify-between">
                <div class="w-3/4 text-right">
                    <p>Iš viso suma be PVM:</p>
                </div>
                <div class="w-1/4 text-right">
                    <p>{{formatPrice($invoice->price)}}</p>
                </div>
            </div>

            <div class="flex justify-between">
                <div class="w-3/4 text-right">
                    <p>PVM 21%:</p>
                </div>
                <div class="w-1/4 text-right">
                    <p>{{formatPrice($invoice->vat)}}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="flex justify-end text-xs">
        <div class="w-2/3 space-y-1">
            <div class="flex justify-between">
                <div class="w-3/4 text-right">
                    <p>Iš viso:</p>
                </div>

                <div class="w-1/4 text-right">
                    <p>{{formatPrice($invoice->price_with_vat)}}</p>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
