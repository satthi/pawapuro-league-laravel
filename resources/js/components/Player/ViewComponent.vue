<template>
    <div class="container" v-if="Object.keys(enums).length && Object.keys(dataset).length">
        <h2>{{ dataset.player.team.season.name }} {{ dataset.player.team.name }}</h2>
        <h3>{{ dataset.player.name }}</h3>
        <div class="clearfix">
            <router-link v-bind:to="{name: 'team.view', params: {teamId: dataset.player.team_id.toString() }}">
                <button class="btn btn-success">チーム詳細</button>
            </router-link>
        </div>

        <h4>野手成績</h4>
        <table class="table table-hover seiseki_table">
            <tr>
                <th>試<br />合</th>
                <th>打<br />率</th>
                <th>本<br />塁<br />打</th>
                <th>打<br />点</th>
                <th>打<br />席</th>
                <th>打<br />数</th>
                <th>安<br />打</th>
                <th>二<br />塁<br />打</th>
                <th>三<br />塁<br />打</th>
                <th>三<br />振</th>
                <th>併<br />殺</th>
                <th>四<br />球</th>
                <th>死<br />球</th>
                <th>犠<br />打</th>
                <th>犠<br />飛</th>
                <th>盗<br />塁</th>
                <th>盗<br />塁<br />失</th>
                <th>出<br />塁<br />率</th>
                <th>長<br />打<br />率</th>
                <th>O<br />P<br />S</th>
            </tr>
            <tr>
                <td>{{ dataset.player.game }}</td>
                <td>{{ dataset.player.display_avg }}</td>
                <td>{{ dataset.player.hr }}</td>
                <td>{{ dataset.player.daten }}</td>
                <td>{{ dataset.player.daseki }}</td>
                <td>{{ dataset.player.dasu }}</td>
                <td>{{ dataset.player.hit }}</td>
                <td>{{ dataset.player.hit_2 }}</td>
                <td>{{ dataset.player.hit_3 }}</td>
                <td>{{ dataset.player.sansin }}</td>
                <td>{{ dataset.player.heisatsu }}</td>
                <td>{{ dataset.player.walk }}</td>
                <td>{{ dataset.player.dead }}</td>
                <td>{{ dataset.player.bant }}</td>
                <td>{{ dataset.player.sac_fly }}</td>
                <td>{{ dataset.player.steal_success }}</td>
                <td>{{ dataset.player.steal_miss }}</td>
                <td>{{ dataset.player.display_obp }}</td>
                <td>{{ dataset.player.display_slg }}</td>
                <td>{{ dataset.player.display_ops }}</td>
            </tr>
        </table>
        <div v-if="dataset.player.position_main == enums.Position.POSITION_P.value || dataset.player.p_inning > 0">
            <h4>投手成績成績</h4>
            <table class="table table-hover seiseki_table">
                <tr>
                    <th>試<br />合</th>
                    <th>防<br />御<br />率</th>
                    <th>勝<br />利</th>
                    <th>敗<br />北</th>
                    <th>ホ<br />ー<br />ル<br />ド</th>
                    <th>セ<br />ー<br />ブ</th>
                    <th>勝<br />率</th>
                    <th>奪<br />三<br />振</th>
                    <th>奪<br />三<br />振<br />率</th>
                    <th>被<br />安<br />打</th>
                    <th>被<br />打<br />率</th>
                    <th>被<br />本<br />塁<br />打</th>
                    <th>自<br />責<br />点</th>
                    <th>回<br />数</th>
                    <th>完<br />投</th>
                    <th>完<br />封</th>
                    <th>四<br />球</th>
                    <th>死<br />球</th>
                </tr>
                <tr>
                    <td>{{ dataset.player.p_game }}</td>
                    <td>{{ dataset.player.display_p_era }}</td>
                    <td>{{ dataset.player.p_win }}</td>
                    <td>{{ dataset.player.p_lose }}</td>
                    <td>{{ dataset.player.p_hold }}</td>
                    <td>{{ dataset.player.p_save }}</td>
                    <td>{{ dataset.player.display_p_win_ratio }}</td>
                    <td>{{ dataset.player.p_sansin }}</td>
                    <td>{{ dataset.player.display_p_sansin_ratio }}</td>
                    <td>{{ dataset.player.p_hit }}</td>
                    <td>{{ dataset.player.display_p_avg }}</td>
                    <td>{{ dataset.player.p_hr }}</td>
                    <td>{{ dataset.player.p_jiseki }}</td>
                    <td>{{ dataset.player.display_p_inning }}</td>
                    <td>{{ dataset.player.p_kanto }}</td>
                    <td>{{ dataset.player.p_kanpu }}</td>
                    <td>{{ dataset.player.p_walk }}</td>
                    <td>{{ dataset.player.p_dead }}</td>
                </tr>
            </table>
        </div>

        <div v-if="Object.keys(dataset.fielder_histories).length">
            <h4>月間野手成績</h4>
            <table class="table table-hover seiseki_table">
                <tr>
                    <th></th>
                    <th>試<br />合</th>
                    <th>打<br />率</th>
                    <th>本<br />塁<br />打</th>
                    <th>打<br />点</th>
                    <th>打<br />席</th>
                    <th>打<br />数</th>
                    <th>安<br />打</th>
                    <th>二<br />塁<br />打</th>
                    <th>三<br />塁<br />打</th>
                    <th>三<br />振</th>
                    <th>併<br />殺</th>
                    <th>四<br />球</th>
                    <th>死<br />球</th>
                    <th>犠<br />打</th>
                    <th>犠<br />飛</th>
                    <th>盗<br />塁</th>
                    <th>盗<br />塁<br />失</th>
                    <th>出<br />塁<br />率</th>
                    <th>長<br />打<br />率</th>
                    <th>O<br />P<br />S</th>
                </tr>
                <tr v-for="monthly_fielder_info in dataset.monthly_fielder_infos">
                    <td>{{ monthly_fielder_info.month }}</td>
                    <td>{{ monthly_fielder_info.game }}</td>
                    <td>{{ monthly_fielder_info.target_avg }}</td>
                    <td>{{ monthly_fielder_info.hr }}</td>
                    <td>{{ monthly_fielder_info.daten }}</td>
                    <td>{{ monthly_fielder_info.daseki }}</td>
                    <td>{{ monthly_fielder_info.dasu }}</td>
                    <td>{{ monthly_fielder_info.hit }}</td>
                    <td>{{ monthly_fielder_info.hit_2 }}</td>
                    <td>{{ monthly_fielder_info.hit_3 }}</td>
                    <td>{{ monthly_fielder_info.sansin }}</td>
                    <td>{{ monthly_fielder_info.heisatsu }}</td>
                    <td>{{ monthly_fielder_info.walk }}</td>
                    <td>{{ monthly_fielder_info.dead }}</td>
                    <td>{{ monthly_fielder_info.bant }}</td>
                    <td>{{ monthly_fielder_info.sac_fly }}</td>
                    <td>{{ monthly_fielder_info.steal_success }}</td>
                    <td>{{ monthly_fielder_info.steal_miss }}</td>
                    <td>{{ monthly_fielder_info.target_opb }}</td>
                    <td>{{ monthly_fielder_info.target_slg }}</td>
                    <td>{{ monthly_fielder_info.target_ops }}</td>
                </tr>
            </table>
        </div>

        <div v-if="dataset.player.position_main == enums.Position.POSITION_P.value || dataset.player.p_inning > 0">
            <h4>月間投手成績</h4>
            <table class="table table-hover seiseki_table">
                <tr>
                    <th></th>
                    <th>試<br />合</th>
                    <th>防<br />御<br />率</th>
                    <th>勝<br />利</th>
                    <th>敗<br />北</th>
                    <th>ホ<br />ー<br />ル<br />ド</th>
                    <th>セ<br />ー<br />ブ</th>
                    <th>勝<br />率</th>
                    <th>奪<br />三<br />振</th>
                    <th>奪<br />三<br />振<br />率</th>
                    <th>被<br />安<br />打</th>
                    <th>被<br />打<br />率</th>
                    <th>被<br />本<br />塁<br />打</th>
                    <th>自<br />責<br />点</th>
                    <th>回<br />数</th>
                    <th>四<br />球</th>
                    <th>死<br />球</th>
                </tr>
                <tr v-for="monthly_pitcher_info in dataset.monthly_pitcher_infos">
                    <td>{{ monthly_pitcher_info.month }}</td>
                    <td>{{ monthly_pitcher_info.p_game }}</td>
                    <td>{{ monthly_pitcher_info.display_p_era }}</td>
                    <td>{{ monthly_pitcher_info.p_win }}</td>
                    <td>{{ monthly_pitcher_info.p_lose }}</td>
                    <td>{{ monthly_pitcher_info.p_hold }}</td>
                    <td>{{ monthly_pitcher_info.p_save }}</td>
                    <td>{{ monthly_pitcher_info.display_p_win_ratio }}</td>
                    <td>{{ monthly_pitcher_info.p_sansin }}</td>
                    <td>{{ monthly_pitcher_info.display_p_sansin_ratio }}</td>
                    <td>{{ monthly_pitcher_info.p_hit }}</td>
                    <td>{{ monthly_pitcher_info.display_p_avg }}</td>
                    <td>{{ monthly_pitcher_info.p_hr }}</td>
                    <td>{{ monthly_pitcher_info.p_jiseki }}</td>
                    <td>{{ monthly_pitcher_info.display_p_inning }}</td>
                    <td>{{ monthly_pitcher_info.p_walk }}</td>
                    <td>{{ monthly_pitcher_info.p_dead }}</td>
                </tr>
            </table>
        </div>

        <div v-if="Object.keys(dataset.fielder_histories).length">
            <h4>野手成績履歴</h4>
            <table class="table table-hover seiseki_table">
                <tr>
                    <th>日付</th>
                    <th>対戦</th>
                    <th>出場</th>
                    <th>打席</th>
                    <th>打数</th>
                    <th>安打</th>
                    <th>本塁打</th>
                    <th>打点</th>
                    <th>四球</th>
                    <th>死球</th>
                    <th>盗塁</th>
                    <th>成績</th>
                    <th></th>
                </tr>
                <tr v-for="fielder_history in dataset.fielder_histories">
                    <td>
                        <router-link v-bind:to="{name: 'game.summary', params: {gameId: fielder_history.game_id.toString() }}">
                            {{ fielder_history.date }}
                        </router-link>
                    </td>
                    <td>{{ fielder_history.vs }}</td>
                    <td>{{ fielder_history.position }}</td>
                    <td>{{ fielder_history.daseki }}</td>
                    <td>{{ fielder_history.dasu }}</td>
                    <td>{{ fielder_history.hit }}</td>
                    <td>{{ fielder_history.hr }}</td>
                    <td>{{ fielder_history.daten }}</td>
                    <td>{{ fielder_history.walk }}</td>
                    <td>{{ fielder_history.dead }}</td>
                    <td>{{ fielder_history.steal }}</td>
                    <td>{{ fielder_history.now_seiseki }}</td>
                    <td style="text-align:left;">{{ fielder_history.seiseki }}</td>
                </tr>
            </table>
        </div>


        <div v-if="Object.keys(dataset.pitcher_histories).length">
            <h4>投手成績履歴</h4>
            <table class="table table-hover seiseki_table">
                <tr>
                    <th>日付</th>
                    <th>対戦</th>
                    <th></th>
                    <th>イニング</th>
                    <th>被安打</th>
                    <th>被本塁打</th>
                    <th>四球</th>
                    <th>死球</th>
                    <th>奪三振</th>
                    <th>自責点</th>
                    <th>防御率</th>
                </tr>
                <tr v-for="pitcher_history in dataset.pitcher_histories">
                    <td>
                        <router-link v-bind:to="{name: 'game.summary', params: {gameId: pitcher_history.game_id.toString() }}">
                            {{ pitcher_history.date }}
                        </router-link>
                    </td>
                    <td>{{ pitcher_history.vs }}</td>
                    <td>{{ pitcher_history.type }}</td>
                    <td>{{ pitcher_history.inning }}</td>
                    <td>{{ pitcher_history.hit }}</td>
                    <td>{{ pitcher_history.hr }}</td>
                    <td>{{ pitcher_history.walk }}</td>
                    <td>{{ pitcher_history.dead }}</td>
                    <td>{{ pitcher_history.sansin }}</td>
                    <td>{{ pitcher_history.jiseki }}</td>
                    <td>{{ pitcher_history.era }}</td>
                </tr>
            </table>
        </div>


        <div v-if="Object.keys(dataset.season_fielder_histories).length > 0">
            <h4>野手シーズン成績</h4>
            <table class="table table-hover seiseki_table">
                <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th>試<br />合</th>
                    <th>打<br />率</th>
                    <th>本<br />塁<br />打</th>
                    <th>打<br />点</th>
                    <th>打<br />席</th>
                    <th>打<br />数</th>
                    <th>安<br />打</th>
                    <th>二<br />塁<br />打</th>
                    <th>三<br />塁<br />打</th>
                    <th>三<br />振</th>
                    <th>併<br />殺</th>
                    <th>四<br />球</th>
                    <th>死<br />球</th>
                    <th>犠<br />打</th>
                    <th>犠<br />飛</th>
                    <th>盗<br />塁</th>
                    <th>盗<br />塁<br />失</th>
                    <th>出<br />塁<br />率</th>
                    <th>長<br />打<br />率</th>
                    <th>O<br />P<br />S</th>
                    <th></th>
                </tr>
                <tr v-for="season_history in dataset.season_fielder_histories">
                    <td>
                        <router-link v-bind:to="{name: 'player.view', params: {playerId: season_history.id.toString() }}">
                            {{ season_history.season_name }}
                        </router-link>
                    </td>
                    <td>{{ season_history.team_ryaku_name }}</td>
                    <td>{{ season_history.number }}</td>
                    <td>{{ season_history.game }}</td>
                    <td>{{ season_history.display_avg }}<span v-html="season_history.avg_rank"></span></td>
                    <td>{{ season_history.hr }}<span v-html="season_history.hr_rank"></span></td>
                    <td>{{ season_history.daten }}<span v-html="season_history.daten_rank"></span></td>
                    <td>{{ season_history.daseki }}<span v-html="season_history.daseki_rank"></span></td>
                    <td>{{ season_history.dasu }}<span v-html="season_history.dasu_rank"></span></td>
                    <td>{{ season_history.hit }}<span v-html="season_history.hit_rank"></span></td>
                    <td>{{ season_history.hit_2 }}<span v-html="season_history.hit2_rank"></span></td>
                    <td>{{ season_history.hit_3 }}<span v-html="season_history.hit3_rank"></span></td>
                    <td>{{ season_history.sansin }}<span v-html="season_history.sansin_rank"></span></td>
                    <td>{{ season_history.heisatsu }}<span v-html="season_history.heisatsu_rank"></span></td>
                    <td>{{ season_history.walk }}<span v-html="season_history.walk_rank"></span></td>
                    <td>{{ season_history.dead }}<span v-html="season_history.dead_rank"></span></td>
                    <td>{{ season_history.bant }}<span v-html="season_history.bant_rank"></span></td>
                    <td>{{ season_history.sac_fly }}<span v-html="season_history.sac_fly_rank"></span></td>
                    <td>{{ season_history.steal_success }}<span v-html="season_history.steal_success_rank"></span></td>
                    <td>{{ season_history.steal_miss }}<span v-html="season_history.steal_miss_rank"></span></td>
                    <td>{{ season_history.display_obp }}<span v-html="season_history.obp_rank"></span></td>
                    <td>{{ season_history.display_slg }}<span v-html="season_history.slg_rank"></span></td>
                    <td>{{ season_history.display_ops }}<span v-html="season_history.ops_rank"></span></td>
                    <td>
                        <span v-if="season_history.is_mvp">MVP</span>
                        <span v-if="season_history.is_b9">B9</span>
                    </td>
                </tr>
                <tr>
                    <td>合計</td>
                    <td></td>
                    <td></td>
                    <td>{{ dataset.player.base_player.game }}</td>
                    <td>{{ dataset.player.base_player.display_avg }}</td>
                    <td>{{ dataset.player.base_player.hr }}</td>
                    <td>{{ dataset.player.base_player.daten }}</td>
                    <td>{{ dataset.player.base_player.daseki }}</td>
                    <td>{{ dataset.player.base_player.dasu }}</td>
                    <td>{{ dataset.player.base_player.hit }}</td>
                    <td>{{ dataset.player.base_player.hit_2 }}</td>
                    <td>{{ dataset.player.base_player.hit_3 }}</td>
                    <td>{{ dataset.player.base_player.sansin }}</td>
                    <td>{{ dataset.player.base_player.heisatsu }}</td>
                    <td>{{ dataset.player.base_player.walk }}</td>
                    <td>{{ dataset.player.base_player.dead }}</td>
                    <td>{{ dataset.player.base_player.bant }}</td>
                    <td>{{ dataset.player.base_player.sac_fly }}</td>
                    <td>{{ dataset.player.base_player.steal_success }}</td>
                    <td>{{ dataset.player.base_player.steal_miss }}</td>
                    <td>{{ dataset.player.base_player.display_obp }}</td>
                    <td>{{ dataset.player.base_player.display_slg }}</td>
                    <td>{{ dataset.player.base_player.display_ops }}</td>
                    <td></td>
                </tr>
            </table>
        </div>

        <div v-if="Object.keys(dataset.season_pitcher_histories).length">
            <h4>投手シーズン成績</h4>
            <table class="table table-hover seiseki_table">
                <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th>試<br />合</th>
                    <th>防<br />御<br />率</th>
                    <th>勝<br />利</th>
                    <th>敗<br />北</th>
                    <th>ホ<br />ー<br />ル<br />ド</th>
                    <th>セ<br />ー<br />ブ</th>
                    <th>勝<br />率</th>
                    <th>奪<br />三<br />振</th>
                    <th>奪<br />三<br />振<br />率</th>
                    <th>被<br />安<br />打</th>
                    <th>被<br />打<br />率</th>
                    <th>被<br />本<br />塁<br />打</th>
                    <th>自<br />責<br />点</th>
                    <th>回<br />数</th>
                    <th>完<br />投</th>
                    <th>完<br />封</th>
                    <th>四<br />球</th>
                    <th>死<br />球</th>
                </tr>
                <tr v-for="season_history in dataset.season_pitcher_histories">
                    <td>
                        <router-link v-bind:to="{name: 'player.view', params: {playerId: season_history.id.toString() }}">
                            {{ season_history.season_name }}
                        </router-link>
                    </td>
                    <td>{{ season_history.team_ryaku_name }}</td>
                    <td>{{ season_history.number }}</td>
                    <td>{{ season_history.p_game }}<span v-html="season_history.p_game_rank"></span></td>
                    <td>{{ season_history.display_p_era }}<span v-html="season_history.p_era_rank"></span></td>
                    <td>{{ season_history.p_win }}<span v-html="season_history.p_win_rank"></span></td>
                    <td>{{ season_history.p_lose }}<span v-html="season_history.p_lose_rank"></span></td>
                    <td>{{ season_history.p_hold }}<span v-html="season_history.p_hold_rank"></span></td>
                    <td>{{ season_history.p_save }}<span v-html="season_history.p_save_rank"></span></td>
                    <td>{{ season_history.display_p_win_ratio }}<span v-html="season_history.p_win_ratio_rank"></span></td>
                    <td>{{ season_history.p_sansin }}<span v-html="season_history.p_sansin_rank"></span></td>
                    <td>{{ season_history.display_p_sansin_ratio }}<span v-html="season_history.p_sansin_ratio_rank"></span></td>
                    <td>{{ season_history.p_hit }}<span v-html="season_history.p_hit_rank"></span></td>
                    <td>{{ season_history.display_p_avg }}<span v-html="season_history.p_avg_rank"></span></td>
                    <td>{{ season_history.p_hr }}<span v-html="season_history.p_hr_rank"></span></td>
                    <td>{{ season_history.p_jiseki }}<span v-html="season_history.p_jiseki_rank"></span></td>
                    <td>{{ season_history.display_p_inning }}<span v-html="season_history.p_inning_rank"></span></td>
                    <td>{{ season_history.p_kanto }}<span v-html="season_history.p_kantog_rank"></span></td>
                    <td>{{ season_history.p_kanpu }}<span v-html="season_history.p_kanpu_rank"></span></td>
                    <td>{{ season_history.p_walk }}<span v-html="season_history.p_walk_rank"></span></td>
                    <td>{{ season_history.p_dead }}<span v-html="season_history.p_dead_rank"></span></td>
                </tr>
                <tr>
                    <td>合計</td>
                    <td></td>
                    <td></td>
                    <td>{{ dataset.player.base_player.p_game }}</td>
                    <td>{{ dataset.player.base_player.display_p_era }}</td>
                    <td>{{ dataset.player.base_player.p_win }}</td>
                    <td>{{ dataset.player.base_player.p_lose }}</td>
                    <td>{{ dataset.player.base_player.p_hold }}</td>
                    <td>{{ dataset.player.base_player.p_save }}</td>
                    <td>{{ dataset.player.base_player.display_p_win_ratio }}</td>
                    <td>{{ dataset.player.base_player.p_sansin }}</td>
                    <td>{{ dataset.player.base_player.display_p_sansin_ratio }}</td>
                    <td>{{ dataset.player.base_player.p_hit }}</td>
                    <td>{{ dataset.player.base_player.display_p_avg }}</td>
                    <td>{{ dataset.player.base_player.p_hr }}</td>
                    <td>{{ dataset.player.base_player.p_jiseki }}</td>
                    <td>{{ dataset.player.base_player.display_p_inning }}</td>
                    <td>{{ dataset.player.base_player.p_kanto }}</td>
                    <td>{{ dataset.player.base_player.p_kanpu }}</td>
                    <td>{{ dataset.player.base_player.p_walk }}</td>
                    <td>{{ dataset.player.base_player.p_dead }}</td>
                </tr>
            </table>
        </div>
        <div>
            <h4>通算カウント</h4>
            <table class="table table-hover" style="width: 200px;">
                <tr v-if="dataset.count.mvp">
                    <th>MVP</th>
                    <td>{{ dataset.count.mvp }}</td>
                </tr>
                <tr v-if="dataset.count.b9">
                    <th>ベストナイン</th>
                    <td>{{ dataset.count.b9 }}</td>
                </tr>
                <tr v-if="dataset.count.avg">
                    <th>首位打者</th>
                    <td>{{ dataset.count.avg }}</td>
                </tr>
                <tr v-if="dataset.count.hr">
                    <th>ホームラン王</th>
                    <td>{{ dataset.count.hr }}</td>
                </tr>
                <tr v-if="dataset.count.daten">
                    <th>打点王</th>
                    <td>{{ dataset.count.daten }}</td>
                </tr>
                <tr v-if="dataset.count.hit">
                    <th>最多安打</th>
                    <td>{{ dataset.count.hit }}</td>
                </tr>
                <tr v-if="dataset.count.steal">
                    <th>盗塁王</th>
                    <td>{{ dataset.count.steal }}</td>
                </tr>
                <tr v-if="dataset.count.kitei_daseki">
                    <th>規定打席到達</th>
                    <td>{{ dataset.count.kitei_daseki }}</td>
                </tr>
                <tr v-if="dataset.count.avg_3wari">
                    <th>3割</th>
                    <td>{{ dataset.count.avg_3wari }}</td>
                </tr>
                <tr v-if="dataset.count.hr_30">
                    <th>30本</th>
                    <td>{{ dataset.count.hr_30 }}</td>
                </tr>
                <tr v-if="dataset.count.daten_100">
                    <th>100打点</th>
                    <td>{{ dataset.count.daten_100 }}</td>
                </tr>
                <tr v-if="dataset.count.steal_30">
                    <th>30盗塁</th>
                    <td>{{ dataset.count.steal_30 }}</td>
                </tr>
                <tr v-if="dataset.count.era">
                    <th>最優秀防御率</th>
                    <td>{{ dataset.count.era }}</td>
                </tr>
                <tr v-if="dataset.count.win">
                    <th>最多勝</th>
                    <td>{{ dataset.count.win }}</td>
                </tr>
                <tr v-if="dataset.count.win_ratio">
                    <th>最高勝率</th>
                    <td>{{ dataset.count.win_ratio }}</td>
                </tr>
                <tr v-if="dataset.count.sansin">
                    <th>最多奪三振</th>
                    <td>{{ dataset.count.sansin }}</td>
                </tr>
                <tr v-if="dataset.count.hold">
                    <th>最優秀中継ぎ投手</th>
                    <td>{{ dataset.count.hold }}</td>
                </tr>
                <tr v-if="dataset.count.save">
                    <th>最優秀救援投手</th>
                    <td>{{ dataset.count.save }}</td>
                </tr>
                <tr v-if="dataset.count.kitei_tokyu">
                    <th>規定投球回数</th>
                    <td>{{ dataset.count.kitei_tokyu }}</td>
                </tr>
                <tr v-if="dataset.count.game_50">
                    <th>50試合以上</th>
                    <td>{{ dataset.count.game_50 }}</td>
                </tr>
                <tr v-if="dataset.count.win_10">
                    <th>10勝</th>
                    <td>{{ dataset.count.win_10 }}</td>
                </tr>
                <tr v-if="dataset.count.era_1ten">
                    <th>防御率1点台</th>
                    <td>{{ dataset.count.era_1ten }}</td>
                </tr>
                <tr v-if="dataset.count.hold_30">
                    <th>30ホールド</th>
                    <td>{{ dataset.count.hold_30 }}</td>
                </tr>
                <tr v-if="dataset.count.save_30">
                    <th>30セーブ</th>
                    <td>{{ dataset.count.save_30 }}</td>
                </tr>
            </table>
        </div>
        <div>
            <h4>カード情報</h4>
            <div class="card_omote">
                <card-omote-component :data="dataset" />
            </div>
            <div class="card_ura">
                <card-ura-component :data="dataset" />
            </div>
        </div>
        <div>
            <form v-on:submit.prevent="submit('/api/cards/update/' + dataset.player.id)">
                <div v-if="dataset.player.position_main != 1">
                    <table class="card_input_table">
                        <tr>
                            <th>コスト</th>
                            <td>
                                <input-component label="" :value="dataset.player.card_cost" v-model="dataset.player.card_cost"/>
                            </td>
                        </tr>
                        <tr>
                            <th>打撃</th>
                            <td>
                                <input-component label="" :value="dataset.player.card_info.batter.meat" v-model="dataset.player.card_info.batter.meat"/>
                            </td>
                        </tr>
                        <tr>
                            <th>長打力</th>
                            <td>
                                <input-component label="" :value="dataset.player.card_info.batter.power" v-model="dataset.player.card_info.batter.power"/>
                            </td>
                        </tr>
                        <tr>
                            <th>走力</th>
                            <td>
                                <input-component label="" :value="dataset.player.card_info.batter.run" v-model="dataset.player.card_info.batter.run"/>
                            </td>
                        </tr>
                        <tr>
                            <th>バント</th>
                            <td>
                                <input-component label="" :value="dataset.player.card_info.batter.bant" v-model="dataset.player.card_info.batter.bant"/>
                            </td>
                        </tr>
                        <tr>
                            <th>守備力</th>
                            <td>
                                <input-component label="" :value="dataset.player.card_info.batter.defence" v-model="dataset.player.card_info.batter.defence"/>
                            </td>
                        </tr>
                        <tr>
                            <th>精神力</th>
                            <td>
                                <input-component label="" :value="dataset.player.card_info.batter.mental" v-model="dataset.player.card_info.batter.mental"/>
                            </td>
                        </tr>
                    </table>
                    <table class="card_input_table">
                        <tr>
                            <th>捕手</th>
                            <td>
                                <input-component label="" :value="dataset.player.card_info.batter.position_c" v-model="dataset.player.card_info.batter.position_c"/>
                            </td>
                        </tr>
                        <tr>
                            <th>一塁手</th>
                            <td>
                                <input-component label="" :value="dataset.player.card_info.batter.position_1b" v-model="dataset.player.card_info.batter.position_1b"/>
                            </td>
                        </tr>
                        <tr>
                            <th>二塁手</th>
                            <td>
                                <input-component label="" :value="dataset.player.card_info.batter.position_2b" v-model="dataset.player.card_info.batter.position_2b"/>
                            </td>
                        </tr>
                        <tr>
                            <th>三塁手</th>
                            <td>
                                <input-component label="" :value="dataset.player.card_info.batter.position_3b" v-model="dataset.player.card_info.batter.position_3b"/>
                            </td>
                        </tr>
                        <tr>
                            <th>遊撃手</th>
                            <td>
                                <input-component label="" :value="dataset.player.card_info.batter.position_ss" v-model="dataset.player.card_info.batter.position_ss"/>
                            </td>
                        </tr>
                        <tr>
                            <th>左翼手</th>
                            <td>
                                <input-component label="" :value="dataset.player.card_info.batter.position_lf" v-model="dataset.player.card_info.batter.position_lf"/>
                            </td>
                        </tr>
                        <tr>
                            <th>中堅手</th>
                            <td>
                                <input-component label="" :value="dataset.player.card_info.batter.position_cf" v-model="dataset.player.card_info.batter.position_cf"/>
                            </td>
                        </tr>
                        <tr>
                            <th>右翼手</th>
                            <td>
                                <input-component label="" :value="dataset.player.card_info.batter.position_rf" v-model="dataset.player.card_info.batter.position_rf"/>
                            </td>
                        </tr>
                    </table>
                </div>
                <div v-else>
                    <table class="card_input_table">
                        <tr>
                            <th>コスト</th>
                            <td>
                                <input-component label="" :value="dataset.player.card_cost" v-model="dataset.player.card_cost"/>
                            </td>
                        </tr>
                        <tr>
                            <th>体力</th>
                            <td>
                                <input-component label="" :value="dataset.player.card_info.pitcher.stamina" v-model="dataset.player.card_info.pitcher.stamina"/>
                            </td>
                        </tr>
                        <tr>
                            <th>球速</th>
                            <td>
                                <input-component label="" :value="dataset.player.card_info.pitcher.speed" v-model="dataset.player.card_info.pitcher.speed"/>
                            </td>
                        </tr>
                        <tr>
                            <th>球威</th>
                            <td>
                                <input-component label="" :value="dataset.player.card_info.pitcher.power" v-model="dataset.player.card_info.pitcher.power"/>
                            </td>
                        </tr>
                        <tr>
                            <th>変化球</th>
                            <td>
                                <input-component label="" :value="dataset.player.card_info.pitcher.henka" v-model="dataset.player.card_info.pitcher.henka"/>
                            </td>
                        </tr>
                        <tr>
                            <th>制球力</th>
                            <td>
                                <input-component label="" :value="dataset.player.card_info.pitcher.control" v-model="dataset.player.card_info.pitcher.control"/>
                            </td>
                        </tr>
                        <tr>
                            <th>精神力</th>
                            <td>
                                <input-component label="" :value="dataset.player.card_info.pitcher.mental" v-model="dataset.player.card_info.pitcher.mental"/>
                            </td>
                        </tr>
                    </table>
                    <table class="card_input_table">
                        <tr>
                            <th>左</th>
                            <td>
                                <input-component label="" :value="dataset.player.card_info.pitcher.henka_l" v-model="dataset.player.card_info.pitcher.henka_l"/>
                                <input-component label="" :value="dataset.player.card_info.pitcher.henka_l_name" v-model="dataset.player.card_info.pitcher.henka_l_name"/>
                            </td>
                        </tr>
                        <tr>
                            <th>左下</th>
                            <td>
                                <input-component label="" :value="dataset.player.card_info.pitcher.henka_lb" v-model="dataset.player.card_info.pitcher.henka_lb"/>
                                <input-component label="" :value="dataset.player.card_info.pitcher.henka_lb_name" v-model="dataset.player.card_info.pitcher.henka_lb_name"/>
                            </td>
                        </tr>
                        <tr>
                            <th>下</th>
                            <td>
                                <input-component label="" :value="dataset.player.card_info.pitcher.henka_b" v-model="dataset.player.card_info.pitcher.henka_b"/>
                                <input-component label="" :value="dataset.player.card_info.pitcher.henka_b_name" v-model="dataset.player.card_info.pitcher.henka_b_name"/>
                            </td>
                        </tr>
                        <tr>
                            <th>右下</th>
                            <td>
                                <input-component label="" :value="dataset.player.card_info.pitcher.henka_rb" v-model="dataset.player.card_info.pitcher.henka_rb"/>
                                <input-component label="" :value="dataset.player.card_info.pitcher.henka_rb_name" v-model="dataset.player.card_info.pitcher.henka_rb_name"/>
                            </td>
                        </tr>
                        <tr>
                            <th>右</th>
                            <td>
                                <input-component label="" :value="dataset.player.card_info.pitcher.henka_r" v-model="dataset.player.card_info.pitcher.henka_r"/>
                                <input-component label="" :value="dataset.player.card_info.pitcher.henka_r_name" v-model="dataset.player.card_info.pitcher.henka_r_name"/>
                            </td>
                        </tr>
                    </table>
                </div>


                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div> 
    </div>
</template>
<style scoped>
.card_input_table {
    float:left;
    width: 130px;
}

.card_input_table th {
width: 60px;
}
.card_input_table td {
width: 70px;
}
</style>
<script>
    import InputComponent from '../common/form/InputComponent';
    import CardOmoteComponent from '../common/card/OmoteComponent';
    import CardUraComponent from '../common/card/UraComponent';
    import EnumsMixin from '../../mixins/enums.js';
    export default {
        watch: {
            '$route' (to, from) {
                this.initial();
            }
        },
        mixins : [EnumsMixin],
        components: {InputComponent, CardOmoteComponent, CardUraComponent},
        methods: {
            initial() {
                this.getData('/api/players/view/' + this.playerId);
            },
            getData(getPath) {
                axios.get(getPath)
                    .then((res) => {
                        this.dataset = res.data;
                    });
            },
            submit(postPath) {
                var postData = this.dataset;
                axios.post(postPath, postData)
                    .then((res) => {
                    alert('更新');
                    })
                    .catch((error) => {
                        this.errors = error.response.data.errors;
                    });
            }
        },
        props: {
            playerId: String
        },
        data: function () {
            return {
                dataset: {}
            }
        },
        mounted() {
            this.initial();
        }
    }
</script>
