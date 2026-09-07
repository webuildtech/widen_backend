<?php

return [
    'frontend_path' => env('GAMES_FRONTEND_PATH', '/games/{uuid}'),

    'group_frontend_path' => env('GAME_GROUPS_FRONTEND_PATH', '/games/group/{uuid}'),

    'list_frontend_path' => env('GAMES_LIST_FRONTEND_PATH', '/games'),

    /*
     * How many extra players one user may bring along and pay for.
     */
    'max_guests_per_join' => 2,

    /*
     * Allowed participant counts of a game.
     */
    'capacities' => [2, 4],

    /*
     * How many games one page of the public game list holds.
     */
    'per_page' => 20,

    /*
     * A game that has not filled up this many minutes before its start is canceled and refunded.
     */
    'cancel_unfilled_minutes_before' => 30,
];
