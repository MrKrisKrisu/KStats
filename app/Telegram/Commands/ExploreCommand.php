<?php

namespace App\Telegram\Commands;

use App\Models\SpotifyTrack;
use App\Models\SpotifyTrackRating;
use Telegram\Bot\Commands\Command;

class ExploreCommand extends Command {

    protected $name        = "explore";
    protected $pattern     = "{action} {trackId}";
    protected $description = "Explore spotify songs";

    public function handle() {

        if(!isset($this->getArguments()['action']) || !isset($this->getArguments()['trackId'])) {
            $this->replyWithMessage([
                                        'text' => 'Bitte gib den Befehl im Format "/explore {action} {trackId}" ein.'
                                    ]);
            return;
        }

        $action  = $this->getArguments()['action'];
        $trackId = $this->getArguments()['trackId'];

        $track = SpotifyTrack::where('track_id', $trackId)->first();

        if($track == null) {
            $this->replyWithMessage([
                                        'text' => 'Fehler: Der Track ist nicht korrekt.'
                                    ]);
            return;
        }

        SpotifyTrackRating::updateOrCreate([
                                               'user_id'  => $this->getUpdate()->getChat()->get('id'),
                                               'track_id' => $track->id,
                                               'rating'   => $action,
                                           ]);


        $this->replyWithMessage([
                                    'text' => "Dein Feedback wurde gespeichert und wird helfen, dass deine Freundschaftsplaylisten besser werden! 🥰"
                                ]);

    }
}