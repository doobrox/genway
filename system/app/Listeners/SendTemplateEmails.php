<?php
namespace App\Listeners;

use App\Mail\TemplateEmail;
use App\Models\User;
use App\Traits\FileAccessTrait;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;
use Symfony\Component\Mime\Part\DataPart as DataPartEmbed;
use Symfony\Component\Mime\Part\File as FileEmbed;

class SendTemplateEmails
{
    use FileAccessTrait;

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        // make sure the listener attribute is an array
        $event->listener = \Arr::wrap($event->listener);
        $event->user['email'] = $event->user['user_email'] ?? $event->user['email'] ?? null;
        // check if this listener needs to be fired
        if(in_array(0, $event->listener) && isset($event->details['template']) && isset($event->user['email'])) {
            $this->sendMail($event->user, $event->details + (
                count($event->listener) > 1 ? [] : setari(['TITLU_NUME_SITE','TITLU_NUME_SITE_SCURT','EMAIL_CONTACT'])
            ));
        }
    }

    protected function sendMail($user, array $details)
    {
        // preluare template email din baza de date
        $info = array(
            "__NUME_SITE__" => $details['TITLU_NUME_SITE_SCURT'],
            "__ID__" => $details['id'] ?? '',
            "__NUMAR_CONTRACT__" => $details['id'] ?? '',
            "__DATA_CONTRACT__" => date('d.m.Y'),
            "__NUME__" => $details['nume'] ?? $user['nume'] ?? '',
            "__PRENUME__" => $details['prenume'] ?? $user['prenume'] ?? '',
            "__EMAIL__" => $details['email'] ?? $user['email'],
            "__USER_EMAIL__" => $details['email'] ?? $user['email'],
            "__TELEFON__" => $details['telefon'] ?? '',
            "__LINK_LOGIN__" => route('login'),
            "__ADDITIONAL_INFO__" => $details['additional_info'] ?? '',
            "__NUME_RESPONSABIL__" => $details['nume_responsabil'] ?? '',
            "__EMAIL_RESPONSABIL__" => $details['email_responsabil'] ?? '',
            "__TELEFON_RESPONSABIL__" => $details['telefon_responsabil'] ?? '',
            "__UTILIZATOR_ACTIUNE__" => $details['utilizator_actiune'] ?? '',
            "__NUME_UTILIZATOR__" => $details['nume_utilizator'] ?? '',
            "__EMAIL_UTILIZATOR__" => $details['email_utilizator'] ?? '',
            "__TELEFON_UTILIZATOR__" => $details['telefon_utilizator'] ?? '',
            "__CERINTA__" => nl2br($details['cerinta'] ?? ''),
            "__LOGO__" => '<img width="150" height="41" src="cid:logo.png">',
        ) + format_placeholder_data($details);

        $template = full_email_template($details['template'], $info);

        Mail::to($user['email'])
            ->cc($details['cc'] ?? '')
            ->send(new TemplateEmail([
                'from' => ['email' => $details['EMAIL_CONTACT'], 'name' => $details['TITLU_NUME_SITE']],
                'subject' => $template['subiect'],
                'body' => $template['continut'],
                'logo' => strpos($template['continut'], 'cid:logo.png') === false ? null : resource_path('assets/genway_logo_email.png'),
                'attachments' => $details['attachments'] ?? null,
            ])
        );
    }
}
