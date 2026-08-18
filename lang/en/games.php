<?php

return [
    'default_title' => 'Open game :time',
    'not_available' => 'This game is no longer taking place.',
    'already_started' => 'The game has already started.',
    'already_joined' => 'You have already joined this game.',
    'not_enough_spots' => 'There are not that many spots left. Remaining: :spots.',
    'duplicate_guest' => 'The same email address is listed more than once.',
    'guest_already_joined' => 'One of the listed players has already joined this game.',
    'level_not_for_sport' => 'The selected level does not apply to this sport.',
    'canceled_not_filled' => 'The game was canceled because it did not fill up.',
    'canceled_by_admin' => 'The game was canceled by the administration.',
    'removed_by_admin' => 'The participant was removed by the administration.',
    'edit_from_games_page' => 'Edit the game from the games section.',
    'cannot_edit_past' => 'A game that has already started cannot be edited.',
    'manage_from_games_page' => 'Manage the game from the games section.',

    'mail' => [
        'greeting' => 'Hello :name,',
        'greeting_no_name' => 'Hello,',
        'signature' => 'Thank you for using our services,',

        'new_games_subject' => 'New open games',
        'new_games_intro' => 'We have published new open games. Click a game to join it.',
        'new_games_button' => 'View the game',
        'new_games_unsubscribe' => 'You can turn these notifications off in your profile.',

        'joined_subject' => 'Your spot is confirmed',
        'joined_intro' => 'Thank you for your payment! Your spot in the game is confirmed.',
        'joined_added_intro' => ':name signed you up for an open game.',
        'joined_button' => 'Game page',

        'canceled_subject' => 'The game was canceled',
        'canceled_intro' => 'Unfortunately, the game will not take place.',
        'canceled_refund' => 'The amount paid (:amount €) has been returned to your account balance.',

        'details' => 'Game details',
        'sport' => 'Sport',
        'date' => 'Date',
        'time' => 'Time',
        'court' => 'Court',
        'price' => 'Price per person',
        'reason' => 'Reason',
    ],
];
