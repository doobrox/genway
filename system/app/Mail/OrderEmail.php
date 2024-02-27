<?php

namespace App\Mail;

use App\Models\Comanda;
use App\Models\User;
use PDF;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderEmail extends Mailable
{
    use Queueable, SerializesModels;

    private $details;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public function __construct($details)
    {
        $this->details = $details;
    }


    /**
     * Build the message.
     *
     * @return $this
     */

    public function build()
    {
        if(!isset($this->details['from'])) {
            $setari = setari(['EMAIL_CONTACT','TITLU_NUME_SITE']);
            $this->details['from'] = [
                'email' => $setari['EMAIL_CONTACT'],
                'name' => $setari['TITLU_NUME_SITE'],
            ];
        }
        $data = [
            'user' => $this->details['user'],
            'localitate' => $this->details['localitate'],
            'comanda' => $this->details['comanda'],
            'curier' => $this->details['curier'],
            'produse' => $this->details['produse'],
            'info' => $this->details['info'],
            'setari' => $this->details['setari']
        ];
        $mail = $this->from($this->details['from']['email'], $this->details['from']['name'])
            ->markdown('emails.reminder', [
                'details' => $this->details,   
            ] + $data)->subject($this->details['subject']);
        if(isset($this->details['reply_to'])) {
            $mail->replyTo($this->details['reply_to']['email'], $this->details['reply_to']['name']);
        }
        if(isset($this->details['attach_invoice'])) {
            $pdf = PDF::loadView('invoice.factura-proforma', $data)->setPaper('a4');
            $mail->attachData($pdf->output(), 'factura '.$this->details["comanda"]->nr_factura.'.pdf', ['mime' => 'application/pdf']);
        }
        return $mail;
    }
}

