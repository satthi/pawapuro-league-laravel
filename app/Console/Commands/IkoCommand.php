<?php

namespace App\Console\Commands;

use App\Models\BasePlayer;
use App\Models\Game;
use App\Models\Play;
use App\Models\Player;
use App\Models\Season;
use App\Models\Stamen;
use App\Models\Team;
use App\Models\GamePitcher;
use Illuminate\Console\Command;

class IkoCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:iko';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    private $ikoConnection;
    private $baseTeamOptions = [
        'LB' => 1,
        'TU' => 2,
        'MF' => 3,
        'TF' => 4,
        'SN' => 5,
        'FF' => 6,
    ];
    private $positionOptions = [
        1 => 1,
        2 => 2,
        3 => 3,
        4 => 4,
        5 => 5,
        6 => 6,
        7 => 7,
        8 => 8,
        9 => 9,
        10 => 21,
        11 => 22,
        99 => 10,
    ];
    private $innigList = [
        0 => null,
        1 => 11,
        2 => 12,
        3 => 21,
        4 => 22,
        5 => 31,
        6 => 32,
        7 => 41,
        8 => 42,
        9 => 51,
        10 => 52,
        11 => 61,
        12 => 62,
        13 => 71,
        14 => 72,
        15 => 81,
        16 => 82,
        17 => 91,
        18 => 92,
        19 => 101,
        20 => 102,
        21 => 111,
        22 => 112,
        23 => 121,
        24 => 122,
        99 => 999
    ];
    private $resultList = [

    ];


    private $basePlayerIds = [];
    private $seasonIds = [];
    private $teamIds = [];
    private $playerIds = [];
    private $gameIds = [];


    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
         ini_set('memory_limit', -1);
        $this->output->writeln('start: ' . date('Y/m/d H:i:s'));

        $this->ikoConnection = \DB::connection('pgsql_iko');

        // game_members??????????????????
        // ???????????????????????????
        // $this->gameMemberFix();
        // $this->gameResultFix();

        // base player?????????
        $this->basePlayerIko();

        // seasons?????????
        $this->seasonIko();

        // teams?????????
        $this->teamIko();

        // players?????????
        $this->playerIko();

        // games?????????
        $this->gameIko();

        // stamen?????????
        $this->stamenIko();

        // plays?????????
        $this->playIko();

        // game_pitchers?????????
        $this->gamePitcherIko();

        // ??????
        $this->shukei();

        $this->output->writeln('end: ' . date('Y/m/d H:i:s'));

        return 0;
    }

    private function gameMemberFix()
    {
        $ikoGameMembers = $this->ikoConnection->select('select * FROM game_members order by game_id,id asc');
        $memberCheck = [];
        // game_id/position/player_id????????????????????????????????????????????????????????????
        foreach ($ikoGameMembers as $ikoGameMember) {
            if (!empty($memberCheck[$ikoGameMember->game_id][$ikoGameMember->player_id][$ikoGameMember->position])) {
                $this->ikoConnection->delete('delete from game_members WHERE id = ' . $ikoGameMember->id);
                continue;
            }

            if (!empty($memberCheck[$ikoGameMember->game_id][$ikoGameMember->player_id])) {
                // position????????????????????????????????????????????????????????????position??????????????????
                unset($memberCheck[$ikoGameMember->game_id][$ikoGameMember->player_id]);
            }
            $memberCheck[$ikoGameMember->game_id][$ikoGameMember->player_id][$ikoGameMember->position] = true;
        }
    }

    private function gameResultFix()
    {
        $ikoGameResults = $this->ikoConnection->select('select * FROM game_results where type = 1 order by game_id,id asc');
        $memberCheck = [];
        // game_id/position/player_id????????????????????????????????????????????????????????????
        foreach ($ikoGameResults as $ikoGameResult) {
            if (!empty($memberCheck[$ikoGameResult->game_id][$ikoGameResult->target_player_id][$ikoGameResult->position])) {
                $this->ikoConnection->delete('delete from game_results WHERE id = ' . $ikoGameResult->id);
                continue;
            }

            if (!empty($memberCheck[$ikoGameResult->game_id][$ikoGameResult->target_player_id])) {
                // position????????????????????????????????????????????????????????????position??????????????????
                unset($memberCheck[$ikoGameResult->game_id][$ikoGameResult->target_player_id]);
            }
            $memberCheck[$ikoGameResult->game_id][$ikoGameResult->target_player_id][$ikoGameResult->position] = true;
        }
    }


    

    private function basePlayerIko()
    {
        $this->output->writeln(__FUNCTION__ . ' start');
        // base_player?????????
        $ikoMotoBasePlayers = $this->ikoConnection->select('select * FROM base_players order by id asc');

        foreach ($ikoMotoBasePlayers as $ikoMotoBasePlayer) {

            $positionMain = null;
            if ($ikoMotoBasePlayer->type_p == 2) {
                $positionMain = 1;
            } elseif ($ikoMotoBasePlayer->type_c == 2) {
                $positionMain = 2;
            } elseif ($ikoMotoBasePlayer->type_i == 2) {
                $positionMain = 3;
            } elseif ($ikoMotoBasePlayer->type_o == 2) {
                $positionMain = 4;
            }

            $positionSub = [];
            if ($ikoMotoBasePlayer->type_p == 1) {
                $positionSub[] = 1;
            }
            if ($ikoMotoBasePlayer->type_c == 1) {
                $positionSub[] = 2;
            }
            if ($ikoMotoBasePlayer->type_i == 1) {
                $positionSub[] = 3;
            }
            if ($ikoMotoBasePlayer->type_o == 1) {
                $positionSub[] = 4;
            }


            $a = BasePlayer::create([
                'base_team_id' => $this->baseTeamOptions[$ikoMotoBasePlayer->team_ryaku_name],
                'number' => $ikoMotoBasePlayer->no,
                'name' => $ikoMotoBasePlayer->name,
                'name_short' => $ikoMotoBasePlayer->name_short,
                'hand_p' => $ikoMotoBasePlayer->throw,
                'hand_b' => $ikoMotoBasePlayer->bat,
                'position_main' => $positionMain,
                'position_sub1' => $positionSub[0] ?? null,
                'position_sub2' => $positionSub[1] ?? null,
                'position_sub3' => $positionSub[2] ?? null,
                'accident_type' => $ikoMotoBasePlayer->accident_type,
                'walk_ritsu' => $ikoMotoBasePlayer->walk_ritsu,
                'p_walk_ritsu' => $ikoMotoBasePlayer->p_walk_ritsu,
            ]);

            // ??????ID????????????ID??????????????????
            $this->basePlayerIds[$ikoMotoBasePlayer->id] = $a->id;
        }
        $this->output->writeln(__FUNCTION__ . ' end');
    }


    private function seasonIko()
    {
        $this->output->writeln(__FUNCTION__ . ' start');
        // base_player?????????
        $ikoMotoSeasons = $this->ikoConnection->select('select * FROM seasons order by id asc');

        foreach ($ikoMotoSeasons as $ikoMotoSeason) {

            $a = Season::create([
                'name' => $ikoMotoSeason->name,
                'regular_flag' => $ikoMotoSeason->regular_flag,
            ]);

            // ??????ID????????????ID??????????????????
            $this->seasonIds[$ikoMotoSeason->id] = $a->id;
        }
        $this->output->writeln(__FUNCTION__ . ' end');
    }



    private function teamIko()
    {
        $this->output->writeln(__FUNCTION__ . ' start');
        // base_player?????????
        $ikoMotoTeams = $this->ikoConnection->select('select * FROM teams order by id asc');

        foreach ($ikoMotoTeams as $ikoMotoTeam) {

            // ?????????????????????????????????????????????
            if (!array_key_exists($ikoMotoTeam->season_id, $this->seasonIds)) {
                continue;
            }

            $a = Team::create([
                'base_team_id' => $this->baseTeamOptions[$ikoMotoTeam->ryaku_name],
                'season_id' => $this->seasonIds[$ikoMotoTeam->season_id],
                'name' => $ikoMotoTeam->name,
                'ryaku_name' => $ikoMotoTeam->ryaku_name,
            ]);

            // ??????ID????????????ID??????????????????
            $this->teamIds[$ikoMotoTeam->id] = $a->id;
        }
        $this->output->writeln(__FUNCTION__ . ' end');
    }


    private function playerIko()
    {
        $this->output->writeln(__FUNCTION__ . ' start');
        // base_player?????????
        $ikoMotoPlayers = $this->ikoConnection->select('select * FROM players order by id asc');

        foreach ($ikoMotoPlayers as $ikoMotoPlayer) {

            // ?????????????????????????????????
            if (!array_key_exists($ikoMotoPlayer->team_id, $this->teamIds)) {
                continue;
            }

            $positionMain = null;
            if ($ikoMotoPlayer->type_p == 2) {
                $positionMain = 1;
            } elseif ($ikoMotoPlayer->type_c == 2) {
                $positionMain = 2;
            } elseif ($ikoMotoPlayer->type_i == 2) {
                $positionMain = 3;
            } elseif ($ikoMotoPlayer->type_o == 2) {
                $positionMain = 4;
            }

            $positionSub = [];
            if ($ikoMotoPlayer->type_p == 1) {
                $positionSub[] = 1;
            }
            if ($ikoMotoPlayer->type_c == 1) {
                $positionSub[] = 2;
            }
            if ($ikoMotoPlayer->type_i == 1) {
                $positionSub[] = 3;
            }
            if ($ikoMotoPlayer->type_o == 1) {
                $positionSub[] = 4;
            }

            $a = Player::create([
                'base_player_id' => $this->basePlayerIds[$ikoMotoPlayer->base_player_id],
                'team_id' => $this->teamIds[$ikoMotoPlayer->team_id],
                'number' => $ikoMotoPlayer->no,
                'name' => $ikoMotoPlayer->name,
                'name_short' => $ikoMotoPlayer->name_short,
                'hand_p' => $ikoMotoPlayer->throw,
                'hand_b' => $ikoMotoPlayer->bat,
                'position_main' => $positionMain,
                'position_sub1' => $positionSub[0] ?? null,
                'position_sub2' => $positionSub[1] ?? null,
                'position_sub3' => $positionSub[2] ?? null,
                'accident_type' => $ikoMotoPlayer->accident_type,
                'walk_ritsu' => $ikoMotoPlayer->walk_ritsu,
                'p_walk_ritsu' => $ikoMotoPlayer->p_walk_ritsu,
            ]);

            // ??????ID????????????ID??????????????????
            $this->playerIds[$ikoMotoPlayer->id] = $a->id;
        }
        $this->output->writeln(__FUNCTION__ . ' end');
    }


    private function gameIko()
    {
        $this->output->writeln(__FUNCTION__ . ' start');
        // base_player?????????
        $ikoMotoGames = $this->ikoConnection->select('select * FROM games order by season_id ASC, date ASC, id asc');


        foreach ($ikoMotoGames as $ikoMotoGame) {

            // ?????????????????????????????????????????????
            if (!array_key_exists($ikoMotoGame->season_id, $this->seasonIds)) {
                continue;
            }

            $a = Game::create([
                'season_id' => $this->seasonIds[$ikoMotoGame->season_id],
                'date' => $ikoMotoGame->date,
                'visitor_team_id' => $this->teamIds[$ikoMotoGame->visitor_team_id],
                'home_team_id' => $this->teamIds[$ikoMotoGame->home_team_id],
                'home_probable_pitcher_id' => null,
                'visitor_probable_pitcher_id' => null,
                'dh_flag' => (bool)$ikoMotoGame->dh_flag,
                'inning' => $this->innigList[$ikoMotoGame->status],
                'out' => $ikoMotoGame->out_num,
                'home_point' => $ikoMotoGame->home_point,
                'visitor_point' => $ikoMotoGame->visitor_point,
            ]);

            // ??????ID????????????ID??????????????????
            $this->gameIds[$ikoMotoGame->id] = $a->id;
        }
        $this->output->writeln(__FUNCTION__ . ' end');
    }

    private function stamenIko()
    {
        $this->output->writeln(__FUNCTION__ . ' start');
        // base_player?????????
        $ikoMotoGameMembers = $this->ikoConnection->select('select * FROM game_members WHERE stamen_flag = true order by id asc');

        $ikoMotoGameMembersCount = count($ikoMotoGameMembers);
        $count = 0;
        foreach ($ikoMotoGameMembers as $ikoMotoGameMember) {
            $count++;
            if ($count % 1000 == 0) {
                $this->output->writeln($count . '/' . $ikoMotoGameMembersCount);
            }


            // ?????????????????????????????????????????????
            if (!array_key_exists($ikoMotoGameMember->team_id, $this->teamIds)) {
                continue;
            }

            $a = Stamen::create([
                'game_id' => $this->gameIds[$ikoMotoGameMember->game_id],
                'team_id' => $this->teamIds[$ikoMotoGameMember->team_id],
                'dajun' => $ikoMotoGameMember->dajun,
                'position' => $this->positionOptions[$ikoMotoGameMember->position],
                'player_id' => $this->playerIds[$ikoMotoGameMember->player_id],
            ]);
        }
        $this->output->writeln(__FUNCTION__ . ' end');
    }

    private function playIko()
    {
        $this->output->writeln(__FUNCTION__ . ' start');
        // base_player?????????
        $ikoMotoGameResults = $this->ikoConnection->select('select * FROM game_results order by id asc');

        $ikoMotoGameResultsCount = count($ikoMotoGameResults);
        $count = 0;
        $memberStamenFlag = true;
        $gamePositionSet = []; // stamen?????????????????????
        $beforeInning = null;
        foreach ($ikoMotoGameResults as $ikoMotoGameResult) {
            $count++;
            if ($count % 1000 == 0) {
                $this->output->writeln($count . '/' . $ikoMotoGameResultsCount);
            }

            // ?????????????????????????????????????????????
            if (!array_key_exists($ikoMotoGameResult->game_id, $this->gameIds)) {
                continue;
            }

            // $type?????????
            if ($ikoMotoGameResult->type == 1) {
                // ???????????????????????????????????????
                if (empty($gamePositionSet[$ikoMotoGameResult->game_id][$ikoMotoGameResult->team_id][$ikoMotoGameResult->dajun])) {
                    $type = 1;
                    $gamePositionSet[$ikoMotoGameResult->game_id][$ikoMotoGameResult->team_id][$ikoMotoGameResult->dajun] = true;
                } else {
                    $type = 2;
                }

            } else if ($ikoMotoGameResult->type == 2) {
                $type = 3;
            } else if ($ikoMotoGameResult->type == 3) {
                $type = 4;
            } else if ($ikoMotoGameResult->type == 5) {
                $type = 5;
            }

            // ?????????????????????inning???????????????????????????null??????????????????????????????????????????????????????????????????
            if ($ikoMotoGameResult->inning && $ikoMotoGameResult->inning != $ikoMotoGameResult->position) {
                $inning = $ikoMotoGameResult->inning;
            } else {
                $inning = $beforeInning;
            }
            $beforeInning = $inning;

            $a = Play::create([
                'game_id' => $this->gameIds[$ikoMotoGameResult->game_id],
                'team_id' => $this->teamIds[$ikoMotoGameResult->team_id],
                'inning' => $this->innigList[$inning],
                'type' => $type,//ato
                'result_id' => $ikoMotoGameResult->result_id,//???????????????????????????????????????
                'out_count' => $ikoMotoGameResult->out_num,
                'point_count' => $ikoMotoGameResult->point,
                'player_id' => $this->playerIds[$ikoMotoGameResult->target_player_id] ?? null,
                'pitcher_id' => $this->playerIds[$ikoMotoGameResult->pitcher_id] ?? null,
                'dajun' => $ikoMotoGameResult->dajun,
                'position' => $this->positionOptions[$ikoMotoGameResult->position] ?? null,
            ]);
        }
        $this->output->writeln(__FUNCTION__ . ' end');
    }

    private function gamePitcherIko()
    {
        $this->output->writeln(__FUNCTION__ . ' start');

        // ????????????????????????????????????????????????????????????
        $pitcherShukeis = Play::where('type', 3)
            ->select([
                'pitcher_id',
                'game_id',
                \DB::raw('count(*) AS daseki'),
                $this->fielderSeisekiSelectParts('dasu_count_flag', 'dasu'),
                $this->fielderSeisekiSelectParts('hit_flag', 'hit'),
                $this->fielderSeisekiSelectParts('hr_flag', 'hr'),
                $this->fielderSeisekiSelectParts('sansin_flag', 'sansin'),
                $this->fielderSeisekiSelectParts('walk_flag', 'walk'),
                $this->fielderSeisekiSelectParts('dead_flag', 'dead'),
            ])
            ->leftjoin('results', 'results.id', '=', 'plays.result_id')
            ->groupBy('pitcher_id')
            ->groupBy('game_id')
            ->get();

        $pitcherShukeiList = [];
        foreach ($pitcherShukeis as $pitcherShukei) {
            $pitcherShukeiList[$pitcherShukei->game_id][$pitcherShukei->pitcher_id] = $pitcherShukei;
        };

        // base_player?????????
        $ikoMotoGamePitcherResults = $this->ikoConnection->select('select
            game_pitcher_results.*
        FROM
            game_pitcher_results 
        LEFT JOIN 
            game_members
        ON
            game_pitcher_results.game_id = game_members.game_id
        AND
            game_members.player_id = game_pitcher_results.pitcher_id
        AND
            game_members.position = 1
            order by game_members.id asc');

        $ikoMotoGamePitcherResultsCount = count($ikoMotoGamePitcherResults);
        $count = 0;
        foreach ($ikoMotoGamePitcherResults as $ikoMotoGamePitcherResult) {
            $count++;
            if ($count % 1000 == 0) {
                $this->output->writeln($count . '/' . $ikoMotoGamePitcherResultsCount);
            }


            // ?????????????????????????????????????????????
            if (!array_key_exists($ikoMotoGamePitcherResult->game_id, $this->gameIds)) {
                continue;
            }

            // ????????????????????????????????????????????????????????????????????????????????????????????????
            if (!array_key_exists($this->playerIds[$ikoMotoGamePitcherResult->pitcher_id], $pitcherShukeiList[$this->gameIds[$ikoMotoGamePitcherResult->game_id]])) {
                continue;
            }
            $shukei = $pitcherShukeiList[$this->gameIds[$ikoMotoGamePitcherResult->game_id]][$this->playerIds[$ikoMotoGamePitcherResult->pitcher_id]];

            $a = GamePitcher::create([
                'game_id' => $this->gameIds[$ikoMotoGamePitcherResult->game_id],
                'team_id' => $this->teamIds[$ikoMotoGamePitcherResult->team_id],
                'player_id' => $this->playerIds[$ikoMotoGamePitcherResult->pitcher_id],
                'win_flag' => $ikoMotoGamePitcherResult->win,
                'lose_flag' => $ikoMotoGamePitcherResult->lose,
                'hold_flag' => $ikoMotoGamePitcherResult->hold,
                'save_flag' => $ikoMotoGamePitcherResult->save,
                'jiseki' => $ikoMotoGamePitcherResult->jiseki,
                'inning' => $ikoMotoGamePitcherResult->inning,

                // ?????????????????????????????????????????????????????????????????????????????????
                'daseki' => $shukei->daseki,
                'dasu' => $shukei->dasu, 
                'hit' => $shukei->hit,
                'hr' => $shukei->hr,
                'sansin' => $shukei->sansin,
                'walk' => $shukei->walk,
                'dead' => $shukei->dead,
            ]);
        }
        $this->output->writeln(__FUNCTION__ . ' end');
    }


    private function fielderSeisekiSelectParts($checkField, $asField)
    {
        return \DB::raw('sum(CASE WHEN results.' . $checkField . ' THEN 1 ELSE 0 END) AS ' . $asField);
    }

    public function shukei()
    {
        $seasons = Season::all();

        foreach ($seasons as $season) {
            $season->shukei();
        }
    }
}
