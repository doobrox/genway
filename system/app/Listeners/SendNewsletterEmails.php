<?php
namespace App\Listeners;

use App\Mail\TemplateEmail;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;

class SendNewsletterEmails
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
        if(in_array(4, $event->listener)) {
            $this->sendMail($event->user, $event->details);
        }
    }

    protected function sendMail($user, array $details)
    {
        $info = array(
            "__NUME__" => $user['nume'],
            "__EMAIL__ " => $user['email'],
            "__NUME_SITE__" => $details['TITLU_NUME_SITE_SCURT'],
            "__LINK_NEWSLETTER__" => env('OLD_SITE_NAME').'admin/newsletter_abonati/',
        );
        Mail::to($details['EMAIL_CONTACT'])
            ->send(new TemplateEmail([
                'from' => ['email' => $details['EMAIL_CONTACT'], 'name' => $details['TITLU_NUME_SITE']],
                'subject' => email_template(11, "subiect", $info),
                'body' => email_template(11, "continut", $info),
            ])
        );
    }
}
