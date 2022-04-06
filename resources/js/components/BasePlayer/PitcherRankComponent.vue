<template>
    <div class="container" v-if="Object.keys(rankingData).length">
        <h2>投手成績一覧</h2>
        <table class="table table-hover seiseki_table">
            <tr>
                <th>No</th>
                <th>選手名</th>
                <th>
                    <router-link v-bind:to="{name: 'base-player.pitcher-rank', params: {sortType: 'p_game' }}">
                        試<br />合
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'base-player.pitcher-rank', params: {sortType: 'p_era' }}">
                        防<br />御<br />率
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'base-player.pitcher-rank', params: {sortType: 'p_win' }}">
                        勝<br />利
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'base-player.pitcher-rank', params: {sortType: 'p_lose' }}">
                        敗<br />北
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'base-player.pitcher-rank', params: {sortType: 'p_hold' }}">
                        ホ<br />ー<br />ル<br />ド
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'base-player.pitcher-rank', params: {sortType: 'p_save' }}">
                        セ<br />ー<br />ブ
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'base-player.pitcher-rank', params: {sortType: 'p_win_ratio' }}">
                        勝<br />率
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'base-player.pitcher-rank', params: {sortType: 'p_sansin' }}">
                        奪<br />三<br />振
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'base-player.pitcher-rank', params: {sortType: 'p_sansin_ratio' }}">
                        奪<br />三<br />振<br />率
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'base-player.pitcher-rank', params: {sortType: 'p_hit' }}">
                        被<br />安<br />打
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'base-player.pitcher-rank', params: {sortType: 'p_avg' }}">
                        被<br />打<br />率
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'base-player.pitcher-rank', params: {sortType: 'p_hr' }}">
                        被<br />本<br />塁<br />打
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'base-player.pitcher-rank', params: {sortType: 'p_jiseki' }}">
                        自<br />責<br />点
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'base-player.pitcher-rank', params: {sortType: 'p_inning' }}">
                        回<br />数
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'base-player.pitcher-rank', params: {sortType: 'p_kanto' }}">
                        完<br />投
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'base-player.pitcher-rank', params: {sortType: 'p_kanpu' }}">
                        完<br />封
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'base-player.pitcher-rank', params: {sortType: 'p_walk' }}">
                        四<br />球
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'base-player.pitcher-rank', params: {sortType: 'p_dead' }}">
                        死<br />球
                    </router-link>
                </th>
            </tr>
            <tr v-for="pitcher in rankingData">
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
                <td>{{ pitcher.p_kanto }}</td>
                <td>{{ pitcher.p_kanpu }}</td>
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
                this.getRankingData('/api/base-players/pitcher-rank/' + this.sortType);
            },
            getRankingData(getPath) {
                axios.get(getPath)
                    .then((res) => {
                        this.rankingData = res.data;
                    });
            },
        },
        props: {
            sortType: String,
        },
        data: function () {
            return {
                rankingData : {},
            }
        },
        mounted() {
            this.initial();
        }
    }
</script>
