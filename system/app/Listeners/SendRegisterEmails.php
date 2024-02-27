<?php
namespace App\Listeners;

use App\Mail\TemplateEmail;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;

class SendRegisterEmails
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
        if(in_array(1, $event->listener)) {
            $this->sendUserMail($event->user, $event->details);
            $this->sendAdminMail($event->user, $event->details);
        }
    }

    protected function sendUserMail(User $user, array $details)
    {
        // preluare template email din baza de date
        $info = array(
            "__NUME_SITE__" => $details['TITLU_NUME_SITE_SCURT'],
            "__LINK_LOGIN__" => route('login'),
            "__USER_EMAIL__" => $user->user_email,
            "__USER_PAROLA__" => $details['pass'] ?? null,
        );

        Mail::to($user->user_email)
            ->send(new TemplateEmail([
                'from' => ['email' => $details['EMAIL_CONTACT'], 'name' => $details['TITLU_NUME_SITE']],
                'subject' => email_template(1, "subiect", $info),
                'body' => email_template(1, "continut", $info),
            ])
        );
    }

    protected function sendAdminMail(User $user, array $details)
    {
        // preluare template email din baza de date
        $info = array(
            "__NUME_SITE__" => $details['TITLU_NUME_SITE_SCURT'],
            "__NUME__" => $user->nume,
            "__PRENUME__" => $user->prenume,
            "__USER_EMAIL__" => $user->user_email,
            "__LINK_USERI_ADMIN__" => env('OLD_SITE_NAME').'admin/useri',
        );

        $id_template = $user->reseller_cerere == '1' ? 14 : 2;
        Mail::to($details['EMAIL_CONTACT'])
            ->send(new TemplateEmail([
                'from' => ['email' => $details['EMAIL_CONTACT'], 'name' => $details['TITLU_NUME_SITE']],
                'subject' => email_template($id_template, "subiect", $info),
                'body' => email_template($id_template, "continut", $info),
            ])
        );
    }
}
