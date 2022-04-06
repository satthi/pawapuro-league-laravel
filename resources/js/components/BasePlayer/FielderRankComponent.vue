<template>
    <div class="container" v-if="Object.keys(rankingData).length">
        <h2>野手成績一覧</h2>
        <table class="table table-hover seiseki_table">
            <tr>
                <th>No</th>
                <th>選手名</th>
                <th>
                    <router-link v-bind:to="{name: 'base-player.fielder-rank', params: {sortType: 'game' }}">
                        試<br />合
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'base-player.fielder-rank', params: {sortType: 'avg' }}">
                        打<br />率
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'base-player.fielder-rank', params: {sortType: 'hr' }}">
                        本<br />塁<br />打
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'base-player.fielder-rank', params: {sortType: 'daten' }}">
                        打<br />点
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'base-player.fielder-rank', params: {sortType: 'daseki' }}">
                        打<br />席
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'base-player.fielder-rank', params: {sortType: 'dasu' }}">
                        打<br />数
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'base-player.fielder-rank', params: {sortType: 'hit' }}">
                        安<br />打
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'base-player.fielder-rank', params: {sortType: 'hit_2' }}">
                        二<br />塁<br />打
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'base-player.fielder-rank', params: {sortType: 'hit_3' }}">
                        三<br />塁<br />打
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'base-player.fielder-rank', params: {sortType: 'sansin' }}">
                        三<br />振
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'base-player.fielder-rank', params: {sortType: 'heisatsu' }}">
                        併<br />殺
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'base-player.fielder-rank', params: {sortType: 'walk' }}">
                        四<br />球
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'base-player.fielder-rank', params: {sortType: 'dead' }}">
                        死<br />球
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'base-player.fielder-rank', params: {sortType: 'bant' }}">
                        犠<br />打
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'base-player.fielder-rank', params: {sortType: 'sac_fly' }}">
                        犠<br />飛
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'base-player.fielder-rank', params: {sortType: 'steal_success' }}">
                        盗<br />塁
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'base-player.fielder-rank', params: {sortType: 'steal_miss' }}">
                        盗<br />塁<br />失
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'base-player.fielder-rank', params: {sortType: 'obp' }}">
                        出<br />塁<br />率
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'base-player.fielder-rank', params: {sortType: 'slg' }}">
                        長<br />打<br />率
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'base-player.fielder-rank', params: {sortType: 'ops' }}">
                        O<br />P<br />S
                    </router-link>
                </th>
            </tr>
            <tr v-for="fielder in rankingData">
                <td>{{ fielder.team_ryaku_name }}</td>
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
                this.getRankingData(this.sortType);
            },
            getRankingData(sortType) {
                axios.get('/api/base-players/fielder-rank/' + sortType)
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
