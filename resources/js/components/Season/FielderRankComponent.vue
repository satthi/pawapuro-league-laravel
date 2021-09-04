<template>
    <div class="container" v-if="Object.keys(data).length">
        <h2>{{  data.season.name }} 野手成績一覧</h2>
        <div class="clearfix">
            <router-link v-bind:to="{name: 'season.view', params: {seasonId: data.season.id.toString() }}">
                <button class="btn btn-success">シーズン詳細</button>
            </router-link>
        </div>
        <table class="table table-hover seiseki_table">
            <tr>
                <th>No</th>
                <th>選手名</th>
                <th>
                    <router-link v-bind:to="{name: 'season.fielder-rank', params: {seasonId: seasonId.toString(), sortType: 'game' }}">
                        試<br />合
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'season.fielder-rank', params: {seasonId: seasonId.toString(), sortType: 'avg' }}">
                        打<br />率
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'season.fielder-rank', params: {seasonId: seasonId.toString(), sortType: 'hr' }}">
                        本<br />塁<br />打
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'season.fielder-rank', params: {seasonId: seasonId.toString(), sortType: 'daten' }}">
                        打<br />点
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'season.fielder-rank', params: {seasonId: seasonId.toString(), sortType: 'daseki' }}">
                        打<br />席
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'season.fielder-rank', params: {seasonId: seasonId.toString(), sortType: 'dasu' }}">
                        打<br />数
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'season.fielder-rank', params: {seasonId: seasonId.toString(), sortType: 'hit' }}">
                        安<br />打
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'season.fielder-rank', params: {seasonId: seasonId.toString(), sortType: 'hit_2' }}">
                        二<br />塁<br />打
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'season.fielder-rank', params: {seasonId: seasonId.toString(), sortType: 'hit_3' }}">
                        三<br />塁<br />打
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'season.fielder-rank', params: {seasonId: seasonId.toString(), sortType: 'sansin' }}">
                        三<br />振
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'season.fielder-rank', params: {seasonId: seasonId.toString(), sortType: 'heisatsu' }}">
                        併<br />殺
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'season.fielder-rank', params: {seasonId: seasonId.toString(), sortType: 'walk' }}">
                        四<br />球
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'season.fielder-rank', params: {seasonId: seasonId.toString(), sortType: 'dead' }}">
                        死<br />球
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'season.fielder-rank', params: {seasonId: seasonId.toString(), sortType: 'bant' }}">
                        犠<br />打
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'season.fielder-rank', params: {seasonId: seasonId.toString(), sortType: 'sac_fly' }}">
                        犠<br />飛
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'season.fielder-rank', params: {seasonId: seasonId.toString(), sortType: 'steal_success' }}">
                        盗<br />塁
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'season.fielder-rank', params: {seasonId: seasonId.toString(), sortType: 'steal_miss' }}">
                        盗<br />塁<br />失
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'season.fielder-rank', params: {seasonId: seasonId.toString(), sortType: 'obp' }}">
                        出<br />塁<br />率
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'season.fielder-rank', params: {seasonId: seasonId.toString(), sortType: 'slg' }}">
                        長<br />打<br />率
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'season.fielder-rank', params: {seasonId: seasonId.toString(), sortType: 'ops' }}">
                        O<br />P<br />S
                    </router-link>
                </th>
            </tr>
            <tr v-for="fielder in rankingData">
                <td>{{ fielder.team_ryaku_name }}</td>
                <td>
                    <router-link v-bind:to="{name: 'player.view', params: {playerId: fielder.id.toString() }}">
                        {{ fielder.name_short }}
                    </router-link>
                </td>
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
    </div>
</template>
<script>
    export default {
        watch: {
            '$route' (to, from) {
                this.initial();
            }
        },
        methods: {
            initial() {
                // チーム情報など込みで詳細画面表示に必要な情報をまとめて取得（したい）
                this.getData('/api/seasons/detail/' + this.seasonId);
                this.getRankingData(this.sortType);
            },
            getData(getPath) {
                axios.get(getPath)
                    .then((res) => {
                        this.data = res.data;
                    });
            },
            getRankingData(sortType) {
                axios.get('/api/seasons/fielder-rank/' + this.seasonId + '/' + sortType)
                    .then((res) => {
                        this.rankingData = res.data;
                    });
            },
        },
        props: {
            seasonId: String,
            sortType: String,
        },
        data: function () {
            return {
                data: {},
                rankingData : {},
            }
        },
        mounted() {
            this.initial();
        }
    }
</script>
