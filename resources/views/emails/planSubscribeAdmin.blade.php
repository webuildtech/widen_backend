<p><strong>Klientas:</strong> {{ $payment->owner->full_name }}</p>

<p><strong>El. Paštas:</strong> {{ $payment->owner->email }}</p>

<p><strong>Telefono numeris:</strong> {{ $payment->owner->phone ?: '-' }}</p>

<p><strong>Planas:</strong> {{ $payment->paymentable->plan->name }} ({{ $periodicity }})</p>

<p><strong>Veiksmas:</strong> {{ $actionLabel }}</p>

<p><strong>Suma su PVM:</strong> {{ $payment->price_with_vat }} €</p>

<p><strong>Apmokėta:</strong> {{ $payment->paid_at?->format('Y-m-d H:i') }}</p>
