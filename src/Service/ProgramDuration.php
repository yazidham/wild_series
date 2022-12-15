<?php

namespace App\Service;

use App\Entity\Program;

class ProgramDuration
{
    public function calculate(Program $program): array
    {
        $minutesTotal = 0;
        $seasons = $program->getSeasons();
        foreach ($seasons as $season) {
            $episodes = $season->getEpisodes();
            foreach ($episodes as $episode) {
                $minutesTotal += $episode->getDuration();
            }
        }
        $n = $minutesTotal * 60;
        $day = floor($n / (24 * 3600));

        $n = ($n % (24 * 3600));
        $hour = floor($n / 3600);

        $n %= 3600;
        $minutes = floor($n / 60 );

        $n %= 60;
        $seconds = floor($n);

        return [intval($day), intval($hour), intval($minutes), intval($seconds)];
    }
}