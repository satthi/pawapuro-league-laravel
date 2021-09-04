<template>
    <div class="container">
        <h2>{{  data.season.name }} 投手成績一覧</h2>
        <div class="clearfix">
            <router-link v-bind:to="{name: 'season.view', params: {seasonId: data.season.id.toString() }}">
                <button class="btn btn-success">シーズン詳細</button>
            </router-link>
        </div>
        <table class="table table-hover seiseki_table">
            <tr>
                <th></th>
                <th>No</th>
                <th>選手名</th>
                <th v-on:click="updateRanking('game')">試<br />合</th>
                <th v-on:click="updateRanking('avg')">打<br />率</th>
                <th v-on:click="updateRanking('hr')">本<br />塁<br />打</th>
                <th v-on:click="updateRanking('daten')">打<br />点</th>
                <th v-on:click="updateRanking('daseki')">打<br />席</th>
                <th v-on:click="updateRanking('dasu')">打<br />数</th>
                <th v-on:click="updateRanking('hit')">安<br />打</th>
                <th v-on:click="updateRanking('hit_2')">二<br />塁<br />打</th>
                <th v-on:click="updateRanking('hit_3')">三<br />塁<br />打</th>
                <th v-on:click="updateRanking('sansin')">三<br />振</th>
                <th v-on:click="updateRanking('heisatsu')">併<br />殺</th>
                <th v-on:click="updateRanking('walk')">四<br />球</th>
                <th v-on:click="updateRanking('dead')">死<br />球</th>
                <th v-on:click="updateRanking('bant')">犠<br />打</th>
                <th v-on:click="updateRanking('sac_fly')">犠<br />飛</th>
                <th v-on:click="updateRanking('steal_success')">盗<br />塁</th>
                <th v-on:click="updateRanking('steal_miss')">盗<br />塁<br />失</th>
                <th v-on:click="updateRanking('obp')">出<br />塁<br />率</th>
                <th v-on:click="updateRanking('slg')">長<br />打<br />率<br /></th>
                <th v-on:click="updateRanking('ops')">O<br />P<br />S</th>
            </tr>
            <tr v-for="fielder in rankingData">
                <td>{{ fielder.team_ryaku_name }}</td>
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
        </table>    </div>
</template>
<script>
    export default {
        methods: {
            initial() {
                // チーム情報など込みで詳細画面表示に必要な情報をまとめて取得（したい）
                this.getData('/api/seasons/detail/' + this.seasonId);
                this.getRankingData('/api/seasons/fielder-rank/' + this.seasonId + '/' + this.sortType);
            },
            getData(getPath) {
                axios.get(getPath)
                    .then((res) => {
                        this.data = res.data;
                    });
            },
            getRankingData(getPath) {
                axios.get(getPath)
                    .then((res) => {
                        this.rankingData = res.data;
                    });
            },
            updateRanking(sortType) {
                this.getRankingData('/api/seasons/fielder-rank/' + this.seasonId + '/' + sortType);
            }
        },
        props: {
            seasonId: String
        },
        data: function () {
            return {
                data: {
                    season: {}
                },
                rankingData : {},
                'sortType' : 'avg' // 初期は打率
            }
        },
        mounted() {
            this.initial();
        }
    }
</script>
