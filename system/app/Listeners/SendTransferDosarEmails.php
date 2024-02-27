<?php
namespace App\Listeners;

use App\Mail\TemplateEmail;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;

class SendTransferDosarEmails
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
        if(in_array(5, $event->listener)) {
            $this->sendMail($event->user, $event->details);
        }
    }

    protected function sendMail($user, array $details)
    {
        // preluare template email din baza de date
        $info = array(
            "__NUME_SITE__" => $details['TITLU_NUME_SITE_SCURT'],
        );

        Mail::to($user)
            ->send(new TemplateEmail([
                'from' => ['email' => $details['EMAIL_CONTACT'], 'name' => $details['TITLU_NUME_SITE']],
                'subject' => email_template(16, "subiect", $info),
                'body' => email_template(16, "continut", $info),
            ])
        );
    }
}
