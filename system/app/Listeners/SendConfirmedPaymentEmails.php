<?php
namespace App\Listeners;

use App\Mail\TemplateEmail;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;

class SendConfirmedPaymentEmails
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        // check if this listener needs to be fired
        if(in_array(3, $event->listener)) {
            $this->sendMail($event->user, $event->details);
        }
    }

    protected function sendMail(User $user, array $details)
    {
        // preluare template email din baza de date
        $info = array(
            "__NUME_SITE__" => $details['TITLU_NUME_SITE_SCURT'],
            "__NR_FACTURA__" => $details['comanda']->nr_factura,
            "__DATA_COMANDA__" => $details['comanda']->transformDate('data_adaugare', 'd.m.Y'),
            "__LINK_COMANDA__" => route('profile.orders.show', $details['comanda']->id),
        );

        Mail::to($user->user_email)
            ->send(new TemplateEmail([
                'from' => ['email' => $details['EMAIL_CONTACT'], 'name' => $details['TITLU_NUME_SITE']],
                'subject' => email_template(5, "subiect", $info),
                'body' => email_template(5, "continut", $info),
            ])
        );
    }
}
