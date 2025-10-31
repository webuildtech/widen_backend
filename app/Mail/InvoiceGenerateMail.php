<?php

namespace App\Mail;

use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class InvoiceGenerateMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public string $dateName;

    public function __construct(
        public Invoice $invoice
    ) {
        $dt = Carbon::parse($invoice->date);

        $ltMonthsGen = [
            1 => 'sausio', 2 => 'vasario', 3 => 'kovo', 4 => 'balandžio',
            5 => 'gegužės', 6 => 'birželio', 7 => 'liepos', 8 => 'rugpjūčio',
            9 => 'rugsėjo', 10 => 'spalio', 11 => 'lapkričio', 12 => 'gruodžio',
        ];

        $this->dateName = sprintf('%d m. %s mėnesį', $dt->year, $ltMonthsGen[$dt->month]);
    }

    public function envelope(): Envelope
    {

        return new Envelope(
            to: $this->invoice->owner->email,
            subject: "Sąskaita faktūra už $this->dateName",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.maizzle.invoiceGenerate',
        );
    }

    public function attachments(): array
    {
        return [Storage::path($this->invoice->path)];
    }
}
