<template>
    <div class="container">
        <h2>{{ season.name }} 日程一覧</h2>
        <div class="clearfix">
            <router-link v-bind:to="{name: 'season.view', params: {seasonId: seasonId.toString() }}">
                <button class="btn btn-success">シーズン詳細</button>
            </router-link>
            <router-link v-bind:to="{name: 'game.add', params: {seasonId: seasonId.toString() }}">
                <button class="btn btn-success">日程追加</button>
            </router-link>
            <router-link v-bind:to="{name: 'game.auto_add', params: {seasonId: seasonId.toString() }}">
                <button class="btn btn-success">日程自動作成</button>
            </router-link>
        </div>
        <table class="table table-hover">
            <tbody>
                <tr v-for="game in games">
                    <th>{{ game.date }}</th>
                    <td v-for="gameDetail in game.game">
                        <div v-if="gameDetail.id">
                            <table class="table table-hover season_game_table">
                                <tr>
                                    <td>{{ gameDetail.home_team.ryaku_name }}</td>
                                    <td>VS</td>
                                    <td>{{ gameDetail.visitor_team.ryaku_name }}</td>
                                </tr>
                                <tr>
                                    <td>{{ gameDetail.home_point }}</td>
                                    <td>-</td>
                                    <td>{{ gameDetail.visitor_point }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3">
                                        DH: {{ gameDetail.dh_flag ? '有' : '無' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3">
                                        <router-link v-bind:to="{name: 'game.view', params: {gameId: gameDetail.id.toString() }}">
                                            <button class="btn btn-success">{{ gameDetail.display_inning }}</button>
                                        </router-link>
                                    </td>
                                </tr>
                            </table>

                        </div>
                        <div v-else> - </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        props: {
            seasonId: String
        },
        data: function () {
            return {
                season: {},
                games: {}
            }
        },
        methods: {
            getSeasonData(getPath) {
                axios.get(getPath)
                    .then((res) => {
                        this.season = res.data;
                    });
            },
            getGames(getPath) {
                axios.get(getPath)
                    .then((res) => {
                        this.games = res.data;
                        console.log(this.games)
                    });
            }
        },

        mounted() {
            this.getSeasonData('/api/seasons/view/' + this.seasonId);
            this.getGames('/api/games/' + this.seasonId);
        }

    }
</script>
