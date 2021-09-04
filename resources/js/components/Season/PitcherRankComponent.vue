<template>
    <div class="container" v-if="Object.keys(data).length">
        <h2>{{  data.season.name }} 投手成績一覧</h2>
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
                    <router-link v-bind:to="{name: 'season.pitcher-rank', params: {seasonId: seasonId.toString(), sortType: 'p_game' }}">
                        試<br />合
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'season.pitcher-rank', params: {seasonId: seasonId.toString(), sortType: 'p_era' }}">
                        防<br />御<br />率
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'season.pitcher-rank', params: {seasonId: seasonId.toString(), sortType: 'p_win' }}">
                        勝<br />利
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'season.pitcher-rank', params: {seasonId: seasonId.toString(), sortType: 'p_lose' }}">
                        敗<br />北
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'season.pitcher-rank', params: {seasonId: seasonId.toString(), sortType: 'p_hold' }}">
                        ホ<br />ー<br />ル<br />ド
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'season.pitcher-rank', params: {seasonId: seasonId.toString(), sortType: 'p_save' }}">
                        セ<br />ー<br />ブ
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'season.pitcher-rank', params: {seasonId: seasonId.toString(), sortType: 'p_win_ratio' }}">
                        勝<br />率
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'season.pitcher-rank', params: {seasonId: seasonId.toString(), sortType: 'p_sansin' }}">
                        奪<br />三<br />振
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'season.pitcher-rank', params: {seasonId: seasonId.toString(), sortType: 'p_sansin_ratio' }}">
                        奪<br />三<br />振<br />率
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'season.pitcher-rank', params: {seasonId: seasonId.toString(), sortType: 'p_hit' }}">
                        被<br />安<br />打
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'season.pitcher-rank', params: {seasonId: seasonId.toString(), sortType: 'p_avg' }}">
                        被<br />打<br />率
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'season.pitcher-rank', params: {seasonId: seasonId.toString(), sortType: 'p_hr' }}">
                        被<br />本<br />塁<br />打
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'season.pitcher-rank', params: {seasonId: seasonId.toString(), sortType: 'p_jiseki' }}">
                        自<br />責<br />点
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'season.pitcher-rank', params: {seasonId: seasonId.toString(), sortType: 'p_inning' }}">
                        回<br />数
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'season.pitcher-rank', params: {seasonId: seasonId.toString(), sortType: 'p_walk' }}">
                        四<br />球
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'season.pitcher-rank', params: {seasonId: seasonId.toString(), sortType: 'p_dead' }}">
                        死<br />球
                    </router-link>
                </th>
            </tr>
            <tr v-for="pitcher in rankingData">
                <td>{{ pitcher.number }}</td>
                <td>
                    <router-link v-bind:to="{name: 'player.view', params: {playerId: pitcher.id.toString() }}">
                        {{ pitcher.name_short }}
                    </router-link>
                </td>
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
        watch: {
            '$route' (to, from) {
                this.initial();
            }
        },
        methods: {
            initial() {
                // チーム情報など込みで詳細画面表示に必要な情報をまとめて取得（したい）
                this.getData('/api/seasons/detail/' + this.seasonId);
                this.getRankingData('/api/seasons/pitcher-rank/' + this.seasonId + '/' + this.sortType);
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
