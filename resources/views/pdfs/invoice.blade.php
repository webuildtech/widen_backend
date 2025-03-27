<html lang="lt">
<head>
    <title>PVM sąskaita faktūra</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <meta charset="UTF-8">
</head>

<body>

@php
    $borderColor = 'border-[#14d315]';
    $bgColor = 'bg-green-100';

    $user = $payment->user;
@endphp

<div class="max-w-4xl mx-auto pt-4 px-8">
    <div class="flex justify-between items-center mb-16">
        <img src="{{base_path('public/logo.png')}}" alt="Logo" class="w-24">

        <div class="w-1/2 border-b-2 {!! $borderColor !!} space-y-1">
            <div class="flex justify-between">
                <div class="text-left">
                    PVM sąskaita faktūra
                </div>
                <div class="text-right font-semibold">
                    SS {{$payment->invoice_no}}
                </div>
            </div>

            <div class="flex justify-between text-xs">
                <div class="text-left">
                    Sąskaita išrašyta
                </div>
                <div class="text-right font-medium">
                    {{$payment->paid_at?->format('Y-m-d')}}
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-4 mb-12">
        <div class="space-y-2 border-2 {!! $borderColor !!} rounded-xl p-4">
            <div class="font-bold text-xl text-center">Pirkėjas</div>

            <div class="font-semibold">
                {{
                    $user ?
                        $user->is_company ? $user->company_name : $user->first_name . ' ' . $user->last_name :
                        $payment->paymentable->guest_first_name . ' ' . $payment->paymentable->guest_last_name
                }}
            </div>

            @if($user?->is_company)
                <div class="text-xs">
                    {{$user->company_code}}
                </div>

                <div class="text-xs">
                    {{$user->company_address}}
                </div>

                @if($user->company_vat_code)
                    <div class="text-xs">
                        PVM kodas: <span class="font-semibold">{{$user->company_vat_code}}</span>
                    </div>
                @endif
            @else
                <div class="text-xs">
                    {{$user ? $user->email : $payment->paymentable->guest_email}}
                </div>

                <div class="text-xs">
                    {{$user ? $user->phone : $payment->paymentable->guest_phone}}
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
        <div class="col-span-3">PREKĖS / PASLAUGOS PAVADINIMAS</div>
        <div class="text-center">Kaina</div>
        <div class="text-center">Nuolaida</div>
        <div class="text-center">Suma be PVM</div>
        <div class="text-center">PVM (21%)</div>
        <div class="text-right">Suma su PVM</div>
    </div>

    <div class="grid grid-cols-8 gap-1 text-xs border-b border-black mb-2">
        @if($payment->paymentable_type === 'reservation')
            @foreach($payment->paymentable->times as $time)
                <div class="col-span-3">
                   {{$time->court->name}} | {{$time->start_time->format('Y-m-d H:i')}} - {{$time->end_time->format('H:i')}}
                </div>
                <div class="text-center">{{formatPrice($time->price + $time->discount)}} x 1</div>
                <div class="text-center">{{$time->discount > 0 ? formatPrice($time->discount) : '-'}}</div>
                <div class="text-center">{{formatPrice($time->price)}}</div>
                <div class="text-center">{{formatPrice($time->vat)}}</div>
                <div class="text-right">{{formatPrice($time->price_with_vat)}}</div>
            @endforeach
        @else
            <div class="col-span-3">
                @if(!$payment->paymentable_type)
                    Balanso papildymas
                @else
                    Planas: {{$payment->paymentable->name}}
                @endif
            </div>
            <div class="text-center">{{formatPrice($payment->price + $payment->discount)}} x 1</div>
            <div class="text-center">{{$payment->discount > 0 ? formatPrice($payment->discount) : '-'}}</div>
            <div class="text-center">{{formatPrice($payment->price)}}</div>
            <div class="text-center">{{formatPrice($payment->vat)}}</div>
            <div class="text-right">{{formatPrice($payment->price_with_vat)}}</div>
        @endif
    </div>

    <div class="flex justify-end text-xs">
        <div class="w-2/3 border-b border-black mb-1 space-y-1">
            <div class="flex justify-between">
                <div class="w-3/4 text-right">
                    <p>Iš viso suma be PVM:</p>
                </div>
                <div class="w-1/4 text-right">
                    <p>{{formatPrice($payment->price + $payment->discount)}}</p>
                </div>
            </div>

            @if($payment->discount > 0)
                <div class="flex justify-between">
                    <div class="w-3/4 text-right">
                        <p>Nuolaida:</p>
                    </div>
                    <div class="w-1/4 text-right">
                        <p>{{formatPrice($payment->discount)}}</p>
                    </div>
                </div>

                <div class="flex justify-between">
                    <div class="w-3/4 text-right">
                        <p>Suma su nuolaida:</p>
                    </div>
                    <div class="w-1/4 text-right">
                        <p>{{formatPrice($payment->price)}}</p>
                    </div>
                </div>
            @endif

            <div class="flex justify-between">
                <div class="w-3/4 text-right">
                    <p>PVM 21%:</p>
                </div>
                <div class="w-1/4 text-right">
                    <p>{{formatPrice($payment->vat)}}</p>
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
                    <p>{{formatPrice($payment->price_with_vat)}}</p>
                </div>
            </div>

            @if ($payment->paid_amount_from_balance > 0)
                <div class="flex justify-between">
                    <div class="w-3/4 text-right">
                        <p>Nurašoma nuo balanso:</p>
                    </div>

                    <div class="w-1/4 text-right">
                        <p>{{formatPrice($payment->paid_amount_from_balance)}}</p>
                    </div>
                </div>
            @endif

            <div class="flex justify-between font-semibold text-base border-b border-black">
                <div class="w-3/4 text-right">
                    <p>Sumokėta suma:</p>
                </div>

                <div class="w-1/4 text-right">
                    <p>{{formatPrice($payment->paid_amount)}}</p>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
