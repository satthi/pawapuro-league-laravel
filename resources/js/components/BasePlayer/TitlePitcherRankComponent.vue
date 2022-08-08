<template>
    <div class="container" v-if="Object.keys(rankingData).length">
        <h2>野手成績一覧</h2>
        <table class="table table-hover seiseki_table">
            <tr>
                <th>No</th>
                <th>選手名</th>
                <th>
                    <router-link v-bind:to="{name: 'base-player.title-pitcher-rank', params: {sortType: 'mvp_count_for_pitcher' }}">
                        MVP
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'base-player.title-pitcher-rank', params: {sortType: 'b9_count_for_pitcher' }}">
                        ベストナイン
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'base-player.title-pitcher-rank', params: {sortType: 'p_era_king_count' }}">
                        最優秀防御率
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'base-player.title-pitcher-rank', params: {sortType: 'p_win_king_count' }}">
                        最多勝
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'base-player.title-pitcher-rank', params: {sortType: 'p_win_ratio_king_count' }}">
                        最高勝率
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'base-player.title-pitcher-rank', params: {sortType: 'p_sansin_king_count' }}">
                        最多奪三振
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'base-player.title-pitcher-rank', params: {sortType: 'p_hold_king_count' }}">
                        最優秀中継ぎ投手
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'base-player.title-pitcher-rank', params: {sortType: 'p_save_king_count' }}">
                        最優秀救援投手
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'base-player.title-pitcher-rank', params: {sortType: 'kitei_tokyu_count' }}">
                        規定投球回数
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'base-player.title-pitcher-rank', params: {sortType: 'p_game_50_count' }}">
                        50試合
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'base-player.title-pitcher-rank', params: {sortType: 'p_era_1ten_count' }}">
                        防御率1点台
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'base-player.title-pitcher-rank', params: {sortType: 'p_era_2ten_count' }}">
                        防御率2点台
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'base-player.title-pitcher-rank', params: {sortType: 'p_win_10_count' }}">
                        10勝
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'base-player.title-pitcher-rank', params: {sortType: 'p_win_13_count' }}">
                        13勝
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'base-player.title-pitcher-rank', params: {sortType: 'p_win_15_count' }}">
                        15勝
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'base-player.title-pitcher-rank', params: {sortType: 'p_hold_30_count' }}">
                        30ホールド
                    </router-link>
                </th>
                <th>
                    <router-link v-bind:to="{name: 'base-player.title-pitcher-rank', params: {sortType: 'p_save_30_count' }}">
                        30セーブ
                    </router-link>
                </th>
            </tr>
            <tr v-for="fielder in rankingData">
                <td>{{ fielder.team_ryaku_name }}</td>
                <td>{{ fielder.name_short }}</td>
                <td>{{ fielder.mvp_count_for_pitcher }}</td>
                <td>{{ fielder.b9_count_for_pitcher }}</td>
                <td>{{ fielder.p_era_king_count }}</td>
                <td>{{ fielder.p_win_king_count }}</td>
                <td>{{ fielder.p_win_ratio_king_count }}</td>
                <td>{{ fielder.p_sansin_king_count }}</td>
                <td>{{ fielder.p_hold_king_count }}</td>
                <td>{{ fielder.p_save_king_count }}</td>
                <td>{{ fielder.kitei_tokyu_count }}</td>
                <td>{{ fielder.p_game_50_count }}</td>
                <td>{{ fielder.p_era_1ten_count }}</td>
                <td>{{ fielder.p_era_2ten_count }}</td>
                <td>{{ fielder.p_win_10_count }}</td>
                <td>{{ fielder.p_win_13_count }}</td>
                <td>{{ fielder.p_win_15_count }}</td>
                <td>{{ fielder.p_hold_30_count }}</td>
                <td>{{ fielder.p_save_30_count }}</td>
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
                axios.get('/api/base-players/title-pitcher-rank/' + sortType)
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
