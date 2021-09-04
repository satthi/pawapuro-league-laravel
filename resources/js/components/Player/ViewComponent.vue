<template>
    <div class="container" v-if="Object.keys(enums).length && Object.keys(data).length">
        <h2>{{ data.player.team.season.name }} {{ data.player.team.name }}</h2>
        <h3>{{ data.player.name }}</h3>
        <div class="clearfix">
            <router-link v-bind:to="{name: 'team.view', params: {teamId: data.player.team_id.toString() }}">
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
                <td>{{ data.player.game }}</td>
                <td>{{ data.player.display_avg }}</td>
                <td>{{ data.player.hr }}</td>
                <td>{{ data.player.daten }}</td>
                <td>{{ data.player.daseki }}</td>
                <td>{{ data.player.dasu }}</td>
                <td>{{ data.player.hit }}</td>
                <td>{{ data.player.hit_2 }}</td>
                <td>{{ data.player.hit_3 }}</td>
                <td>{{ data.player.sansin }}</td>
                <td>{{ data.player.heisatsu }}</td>
                <td>{{ data.player.walk }}</td>
                <td>{{ data.player.dead }}</td>
                <td>{{ data.player.bant }}</td>
                <td>{{ data.player.sac_fly }}</td>
                <td>{{ data.player.steal_success }}</td>
                <td>{{ data.player.steal_miss }}</td>
                <td>{{ data.player.display_obp }}</td>
                <td>{{ data.player.display_slg }}</td>
                <td>{{ data.player.display_ops }}</td>
            </tr>
        </table>
        <div v-if="data.player.position_main == enums.Position.POSITION_P.value || data.player.p_inning > 0">
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
                    <th>四<br />球</th>
                    <th>死<br />球</th>
                </tr>
                <tr>
                    <td>{{ data.player.p_game }}</td>
                    <td>{{ data.player.display_p_era }}</td>
                    <td>{{ data.player.p_win }}</td>
                    <td>{{ data.player.p_lose }}</td>
                    <td>{{ data.player.p_hold }}</td>
                    <td>{{ data.player.p_save }}</td>
                    <td>{{ data.player.display_p_win_ratio }}</td>
                    <td>{{ data.player.p_sansin }}</td>
                    <td>{{ data.player.display_p_sansin_ratio }}</td>
                    <td>{{ data.player.p_hit }}</td>
                    <td>{{ data.player.display_p_avg }}</td>
                    <td>{{ data.player.p_hr }}</td>
                    <td>{{ data.player.p_jiseki }}</td>
                    <td>{{ data.player.display_p_inning }}</td>
                    <td>{{ data.player.p_walk }}</td>
                    <td>{{ data.player.p_dead }}</td>
                </tr>
            </table>
        </div>

        <div v-if="Object.keys(data.fielder_histories).length">
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
                    <th></th>
                </tr>
                <tr v-for="fielder_history in data.fielder_histories">
                    <td>{{ fielder_history.date }}</td>
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
                    <td style="text-align:left;">{{ fielder_history.seiseki }}</td>
                </tr>
            </table>
        </div>


        <div v-if="Object.keys(data.pitcher_histories).length">
            <h4>投手成績履歴</h4>
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
                    <th></th>
                </tr>
                <tr v-for="pitcher_history in data.pitcher_histories">
                    <td>{{ pitcher_history.date }}</td>
                    <td>{{ pitcher_history.vs }}</td>
                    <td>{{ pitcher_history.type }}</td>
                    <td>{{ pitcher_history.inning }}</td>
                    <td>{{ pitcher_history.hit }}</td>
                    <td>{{ pitcher_history.hr }}</td>
                    <td>{{ pitcher_history.walk }}</td>
                    <td>{{ pitcher_history.dead }}</td>
                    <td>{{ pitcher_history.sansin }}</td>
                    <td>{{ pitcher_history.jiseki }}</td>
                </tr>
            </table>
        </div>
    </div>
</template>
<script>
    import EnumsMixin from '../../mixins/enums.js';
    export default {
        mixins : [EnumsMixin],
        methods: {
            initial() {
                this.getData('/api/players/view/' + this.playerId);
            },
            getData(getPath) {
                axios.get(getPath)
                    .then((res) => {
                        this.data = res.data;
                    });
            },
        },
        props: {
            playerId: String
        },
        data: function () {
            return {
                data: {}
            }
        },
        mounted() {
            this.initial();
        }
    }
</script>
