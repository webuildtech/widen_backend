<?php

namespace Database\Seeders;

use App\Models\CourtType;
use App\Models\GameLevel;

class GameLevelsSeeder extends BasicSeeder
{
    /**
     * NTRP scale: 1.0-5.5 in half steps plus a combined top tier. The label is a number, so it reads
     * the same in both languages. Descriptions are starting points - they are editable from the
     * admin panel.
     */
    private const LEVELS = [
        [
            'name' => '1.0',
            'lt' => 'Tik pradedate žaisti tenisą – mokotės laikyti raketę ir atlikti pirmuosius smūgius.',
            'en' => 'You are just starting out and learning to hold the racket and hit your first shots.',
        ],
        [
            'name' => '1.5',
            'lt' => 'Žaidimo patirties turite nedaug, pagrindinis tikslas – įmušti kamuoliuką į aikštelę.',
            'en' => 'You have limited playing experience and your main goal is keeping the ball in play.',
        ],
        [
            'name' => '2.0',
            'lt' => 'Smūgių technikai dar trūksta, bet jau žinote pagrindines pozicijas aikštelėje.',
            'en' => 'Your strokes still have clear weaknesses, but you know the basic court positions.',
        ],
        [
            'name' => '2.5',
            'lt' => 'Mokotės numatyti kamuoliuko kryptį ir su panašaus lygio partneriu išlaikote trumpą mainų seriją.',
            'en' => 'You are learning to judge where the ball is going and can sustain a short rally with a similar player.',
        ],
        [
            'name' => '3.0',
            'lt' => 'Vidutinio greičio smūgius atliekate gana stabiliai, bet trūksta krypties, gylio ir jėgos kontrolės.',
            'en' => 'You are fairly consistent on medium-paced shots, but still lack control over direction, depth and power.',
        ],
        [
            'name' => '3.5',
            'lt' => 'Vidutinio greičio smūgius valdote patikimai, geriau kontroliuojate kryptį, pradedate sėkmingiau žaisti prie tinklo.',
            'en' => 'You handle medium-paced shots reliably, control direction better and your net play is starting to work.',
        ],
        [
            'name' => '4.0',
            'lt' => 'Dešinės ir kairės smūgiai patikimi, kontroliuojate kryptį bei gylį, naudojate svaidinius ir tinklo žaidimą.',
            'en' => 'Your forehand and backhand are dependable with control over direction and depth; you use lobs and volleys.',
        ],
        [
            'name' => '4.5',
            'lt' => 'Varijuojate smūgio jėgą ir sukimą, gerai judate aikštelėje, kontroliuojate gylį ir susitvarkote su greitu žaidimu.',
            'en' => 'You vary pace and spin, move well, control the depth of your shots and can handle a fast game.',
        ],
        [
            'name' => '5.0',
            'lt' => 'Gerai nuspėjate varžovo veiksmus ir turite ryškų stiprųjį smūgį, aplink kurį sukate žaidimą.',
            'en' => 'You anticipate well and have a standout shot that your game is built around.',
        ],
        [
            'name' => '5.5',
            'lt' => 'Jėga arba stabilumas yra jūsų pagrindinis ginklas, o įtemptose varžybų situacijose žaidžiate patikimai.',
            'en' => 'Power or consistency is your main weapon and you play dependably under pressure.',
        ],
        [
            'name' => '6.0–7.0',
            'lt' => 'Intensyviai treniruojatės nacionalinio ar tarptautinio lygio varžyboms; 7.0 – pasaulinio lygio profesionalas.',
            'en' => 'You train intensively for national or international competition; 7.0 is a world-class professional.',
        ],
    ];

    /**
     * Both tennis court types share the same rows, so the scale is filled in once and cannot drift.
     * Badminton and table tennis have no levels yet.
     */
    private const COURT_TYPES = [
        'Tenisas | Vidus',
        'Tenisas | Laukas',
    ];

    public function run(): void
    {
        if ($this->isNotSeeded()) {
            $levels = collect(self::LEVELS)->map(fn(array $level, int $index) => GameLevel::create([
                'name' => ['lt' => $level['name'], 'en' => $level['name']],
                'description' => ['lt' => $level['lt'], 'en' => $level['en']],
                'sort_order' => ($index + 1) * 10,
            ]));

            CourtType::whereIn('name', self::COURT_TYPES)
                ->get()
                ->each(fn(CourtType $courtType) => $courtType->gameLevels()->sync($levels->pluck('id')));

            $this->saveSeed();
        }
    }
}
