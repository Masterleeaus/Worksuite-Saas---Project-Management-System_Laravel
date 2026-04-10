<?php

return [
    'app' => [
        'title'          => 'Title',
        'type'           => 'Type',
        'priority'       => 'Priority',
        'client'         => 'Client',
        'addTicket'      => 'Add Ticket',
        'createNpsSurvey' => 'Create NPS Survey',
    ],
    'modules' => [
        'feedback'   => 'Customer Feedback',
        'nps'        => 'NPS Surveys',
        'analytics'  => 'Feedback Analytics',
    ],
    'notifications' => [
        'newTicketSubject' => 'New Feedback Ticket',
        'newTicketBody'    => 'A new ticket has been submitted: :title',
        'viewTicket'       => 'View Ticket',
    ],
    'messages' => [
        'thankYou'           => 'Thank you for your feedback!',
        'surveyExpired'      => 'This survey link has expired.',
        'alreadyCompleted'   => 'You have already completed this survey.',
        'surveyNotFound'     => 'Survey not found.',
    ],
];
