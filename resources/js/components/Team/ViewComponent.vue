<template>
    <div class="container" v-if="Object.keys(data).length">
        <h2>{{  data.team.season.name }} {{  data.team.name }}</h2>
        <div class="clearfix">
            <router-link v-bind:to="{name: 'season.view', params: {seasonId: data.team.season_id.toString() }}">
                <button class="btn btn-success">シーズン詳細</button>
            </router-link>
        </div>
        <h3>野手</h3>
        <table class="table table-hover seiseki_table">
            <tr>
                <th>No</th>
                <th>選手名</th>
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
            <tr v-for="fielder in data.fielders">
                <td>{{ fielder.number }}</td>
                <td>{{ fielder.name_short }}</td>
                <td>{{ fielder.game }}</td>
                <td>{{ fielder.display_avg }}</td>
                <td>{{ fielder.hr }}</td>
                <td>{{ fielder.daten }}</td>
                <td>{{ fielder.daseki }}</td>
                <td>{{ fielder.dasu }}</td>
                <td>{{ fielder.hit }}</td>
                <td>{{ fielder.hit_2 }}</td>
                <td>{{ fielder.hit_3 }}</td>
                <td>{{ fielder.sansin }}</td>
                <td>{{ fielder.heisatsu }}</td>
                <td>{{ fielder.walk }}</td>
                <td>{{ fielder.dead }}</td>
                <td>{{ fielder.bant }}</td>
                <td>{{ fielder.sac_fly }}</td>
                <td>{{ fielder.steal_success }}</td>
                <td>{{ fielder.steal_miss }}</td>
                <td>{{ fielder.display_obp }}</td>
                <td>{{ fielder.display_slg }}</td>
                <td>{{ fielder.display_ops }}</td>
            </tr>
        </table>

        <h3>投手</h3>
        <table class="table table-hover seiseki_table">
            <tr>
                <th>No</th>
                <th>選手名</th>
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
            <tr v-for="pitcher in data.pitchers">
                <td>{{ pitcher.number }}</td>
                <td>{{ pitcher.name_short }}</td>
                <td>{{ pitcher.p_game }}</td>
                <td>{{ pitcher.display_p_era }}</td>
                <td>{{ pitcher.p_win }}</td>
                <td>{{ pitcher.p_lose }}</td>
                <td>{{ pitcher.p_hold }}</td>
                <td>{{ pitcher.p_save }}</td>
                <td>{{ pitcher.display_p_win_ratio }}</td>
                <td>{{ pitcher.p_sansin }}</td>
                <td>{{ pitcher.display_p_sansin_ratio }}</td>
                <td>{{ pitcher.p_hit }}</td>
                <td>{{ pitcher.display_p_avg }}</td>
                <td>{{ pitcher.p_hr }}</td>
                <td>{{ pitcher.p_jiseki }}</td>
                <td>{{ pitcher.display_p_inning }}</td>
                <td>{{ pitcher.p_walk }}</td>
                <td>{{ pitcher.p_dead }}</td>
            </tr>
        </table>
    </div>
</template>
<script>
    export default {
        methods: {
            initial() {
                // チーム情報など込みで詳細画面表示に必要な情報をまとめて取得（したい）
                this.getData('/api/teams/view/' + this.teamId);
            },
            getData(getPath) {
                axios.get(getPath)
                    .then((res) => {
                        this.data = res.data;
                        console.log(this.data)
                    });
            },
        },
        props: {
            teamId: String
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
