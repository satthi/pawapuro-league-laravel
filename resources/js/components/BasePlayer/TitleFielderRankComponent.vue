<template>
    <div class="container" v-if="Object.keys(rankingData).length">
        <h2>野手成績一覧</h2>
        <table class="table table-hover seiseki_table">
            <tr>
                <th>No</th>
                <th>選手名</th>
                <th>
                    <router-link v-bind:to="{name: 'base-player.title-fielder-rank', params: {sortType: 'mvp_count_for_fielder' }}">
                        MVP
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'base-player.title-fielder-rank', params: {sortType: 'b9_count_for_fielder' }}">
                        ベストナイン
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'base-player.title-fielder-rank', params: {sortType: 'avg_king_count' }}">
                        首位打者
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'base-player.title-fielder-rank', params: {sortType: 'hr_king_count' }}">
                        ホームラン王
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'base-player.title-fielder-rank', params: {sortType: 'daten_king_count' }}">
                        打点王
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'base-player.title-fielder-rank', params: {sortType: 'hit_king_count' }}">
                        最多安打
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'base-player.title-fielder-rank', params: {sortType: 'steal_king_count' }}">
                        盗塁王
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'base-player.title-fielder-rank', params: {sortType: 'kitei_daseki_count' }}">
                        規定打席到達
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'base-player.title-fielder-rank', params: {sortType: 'avg_2wari_8bu_count' }}">
                        2割8分
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'base-player.title-fielder-rank', params: {sortType: 'avg_3wari_count' }}">
                        3割
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'base-player.title-fielder-rank', params: {sortType: 'avg_3wari_2bu_count' }}">
                        3割2分
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'base-player.title-fielder-rank', params: {sortType: 'avg_3wari_4bu_count' }}">
                        3割4分
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'base-player.title-fielder-rank', params: {sortType: 'hr_10_count' }}">
                        10本
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'base-player.title-fielder-rank', params: {sortType: 'hr_20_count' }}">
                        20本
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'base-player.title-fielder-rank', params: {sortType: 'hr_30_count' }}">
                        30本
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'base-player.title-fielder-rank', params: {sortType: 'hr_40_count' }}">
                        40本
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'base-player.title-fielder-rank', params: {sortType: 'daten_60_count' }}">
                        60打点
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'base-player.title-fielder-rank', params: {sortType: 'daten_80_count' }}">
                        80打点
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'base-player.title-fielder-rank', params: {sortType: 'daten_100_count' }}">
                        100打点
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'base-player.title-fielder-rank', params: {sortType: 'steal_10_count' }}">
                        10盗塁
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'base-player.title-fielder-rank', params: {sortType: 'steal_20_count' }}">
                        20盗塁
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'base-player.title-fielder-rank', params: {sortType: 'steal_30_count' }}">
                        30盗塁
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'base-player.title-fielder-rank', params: {sortType: 'steal_40_count' }}">
                        40盗塁
                    </router-link>
                </th>
            </tr>
            <tr v-for="fielder in rankingData">
                <td>{{ fielder.team_ryaku_name }}</td>
                <td>{{ fielder.name_short }}</td>
                <td>{{ fielder.mvp_count_for_fielder }}</td>
                <td>{{ fielder.b9_count_for_fielder }}</td>
                <td>{{ fielder.avg_king_count }}</td>
                <td>{{ fielder.hr_king_count }}</td>
                <td>{{ fielder.daten_king_count }}</td>
                <td>{{ fielder.hit_king_count }}</td>
                <td>{{ fielder.steal_king_count }}</td>
                <td>{{ fielder.kitei_daseki_count }}</td>
                <td>{{ fielder.avg_2wari_8bu_count }}</td>
                <td>{{ fielder.avg_3wari_count }}</td>
                <td>{{ fielder.avg_3wari_2bu_count }}</td>
                <td>{{ fielder.avg_3wari_4bu_count }}</td>
                <td>{{ fielder.hr_10_count }}</td>
                <td>{{ fielder.hr_20_count }}</td>
                <td>{{ fielder.hr_30_count }}</td>
                <td>{{ fielder.hr_40_count }}</td>
                <td>{{ fielder.daten_60_count }}</td>
                <td>{{ fielder.daten_80_count }}</td>
                <td>{{ fielder.daten_100_count }}</td>
                <td>{{ fielder.steal_10_count }}</td>
                <td>{{ fielder.steal_20_count }}</td>
                <td>{{ fielder.steal_30_count }}</td>
                <td>{{ fielder.steal_40_count }}</td>
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
                axios.get('/api/base-players/title-fielder-rank/' + sortType)
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
