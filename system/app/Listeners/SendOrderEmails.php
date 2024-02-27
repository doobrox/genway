<?php
namespace App\Listeners;

use App\Mail\OrderEmail;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;

class SendOrderEmails
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
        if(in_array(2, $event->listener)) {
            $details = $event->details + [
                'localitate' => $event->user->localitate,
                'produse' => $event->details['comanda']->produse,
                'curier' => $event->details['comanda']->curier,
                'info' => $event->details['comanda']->getMetas(),
                'setari' => setari([
                    'FACTURARE_NUME_FIRMA',
                    'FACTURARE_ADRESA',
                    'FACTURARE_LOCALITATE',
                    'FACTURARE_JUDET',
                    'FACTURARE_COD_FISCAL',
                    'FACTURARE_NR_REG_COMERT',
                    'FACTURARE_BANCA',
                    'FACTURARE_CUI'
                ])
            ];
            $this->sendUserMail($event->user, $details);
            $this->sendAdminMail($event->user, $details);
        }
    }

    protected function sendUserMail(User $user, array $details)
    {
        // preluare template email din baza de date
        $info = array(
            "__NUME_SITE__" => $details['TITLU_NUME_SITE_SCURT'],
            "__NR_FACTURA__" => $details['comanda']->nr_factura,
            "__LINK_SITE__" => route('home'),
            "__LINK_COMANDA__" => route('profile.orders.show', $details['comanda']->id),
        );

        Mail::to($user->user_email)
            ->send(new OrderEmail([
                'from' => ['email' => $details['EMAIL_CONTACT'], 'name' => $details['TITLU_NUME_SITE']],
                'subject' => email_template(3, "subiect", $info),
                'body' => email_template(3, "continut", $info),
                'attach_invoice' => true,
                'user' => $user
            ] + $details)
        );
    }

    protected function sendAdminMail(User $user, array $details)
    {
        // preluare template email din baza de date
        $info = array(
            "__NUME_SITE__" => $details['TITLU_NUME_SITE_SCURT'],
            "__NR_FACTURA__" => $details['comanda']->nr_factura,
            "__LINK_SITE__" => route('home'),
            "__LINK_COMANDA__" => env('OLD_SITE_NAME').'admin/comenzi/comanda/'.$details['comanda']->id,
        );

        Mail::to($details['EMAIL_CONTACT'])
            ->send(new OrderEmail([
                'from' => ['email' => $details['EMAIL_CONTACT'], 'name' => $details['TITLU_NUME_SITE']],
                'reply_to' => ['email' => $user->user_email, 'name' => $user->nume.' '.$user->prenume],
                'subject' => email_template(4, "subiect", $info),
                'body' => email_template(4, "continut", $info),
                'user' => $user,
            ] + $details)
        );
    }
}
