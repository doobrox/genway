<?php
$config['trimite_mesaj_contact'] =
    array(
       array(
             'field'   => 'subiect',
             'label'   => 'Subiect',
             'rules'   => 'trim|required|xss_clean'
          ),
       array(
             'field'   => 'nume',
             'label'   => 'Nume',
             'rules'   => 'trim|required|xss_clean'
          ),
       array(
             'field'   => 'email',
             'label'   => 'Adresa de email',
             'rules'   => 'trim|required|valid_email'
          ),
       array(
             'field'   => 'telefon',
             'label'   => 'Telefon',
             'rules'   => 'trim|xss_clean'
          ),
       array(
             'field'   => 'mesaj',
             'label'   => 'Mesaj',
             'rules'   => 'trim|required|xss_clean'
          ),
       array(
             'field'   => 'captcha',
             'label'   => 'Cod securitate',
             'rules'   => 'callback_check_captcha'
          )
    );

$config['adauga_comentariu_nota'] =
    array(
       array(
             'field'   => 'nota',
             'label'   => 'Nota',
             'rules'   => 'required|numeric'
          ),
       array(
             'field'   => 'comentarii',
             'label'   => 'Comentariu',
             'rules'   => 'trim|required|xss_clean'
          ),
       array(
             'field'   => 'captcha',
             'label'   => 'Cod securitate',
             'rules'   => 'callback_check_captcha'
          )
    );
