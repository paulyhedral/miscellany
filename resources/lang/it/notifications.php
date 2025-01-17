<?php

return [
    'campaign'          => [
        'export'        => 'Un\'esportazione di una campagna è disponibile. Puoi scaricarlo premendo <a href=":link">qui</a>. Il link sarà disponibile per 30 minuti.',
        'export_error'  => 'Abbiamo riscontrato un errore nell\'esportazione della tua campagna. Per favore contattaci se questo problema dovesse persistere.',
        'join'          => ':user si è unito alla campagna :campaign',
        'leave'         => ':user ha abbandonato la campagna :campaign',
        'role'          => [
            'add'       => 'Sei stato assegnato al ruolo :role nella campagna :campaign.',
            'remove'    => 'Sei stato rimosso dal ruolo :role nella campagna :campaign.',
        ],
    ],
    'header'            => 'Hai :count notifiche',
    'index'             => [
        'description'   => 'Le tue ultime notifiche',
        'title'         => 'Notifiche',
    ],
    'no_notifications'  => 'Attualmente non ci sono notifiche',
    'permissions'       => [
        'body'  => 'Ciao, vogliamo farti sapere che abbiamo completamente cambiato il sistema di gestione dei permessi di ciascuna campagna!</p><p>Le campagne ora possono avere dei ruoli, ed ogni ruolo può avere differenti permessi per visualizzare, modificare o eliminare entità. Ogni entità può essere messa a punto con permessi specifici per singolo utente, significa che Becky ed Alfred possono modificare i loro personaggi!</p><p>L\'unico aspetto negativo è che le campagne con molti utenti dovranno impostare questi nuovi permessi. Se sei l\'Admin della campagna potrai farlo dalla pagina di gestione della campagna. Se fai solamente parte della campagna non potrai visualizzare nulla fino a quando il proprietario non provvederà a sistemarli.',
        'title' => 'Modifica dei permessi',
    ],
];
