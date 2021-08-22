<template>
    <div class="container">
        <h2>{{ season.name }} 日程一覧</h2>
        <div class="clearfix">
            <router-link v-bind:to="{name: 'game.add', params: {seasonId: seasonId.toString() }}">
                <button class="btn btn-success float-right">日程追加</button>
            </router-link>
            <router-link v-bind:to="{name: 'game.auto_add', params: {seasonId: seasonId.toString() }}">
                <button class="btn btn-success float-right">日程自動作成</button>
            </router-link>
        </div>
        <table class="table table-hover">
            <tbody>
                <tr v-for="game in games">
                    <th>{{ game.date }}</th>
                    <td v-for="gameDetail in game.game">
                        <div v-if="gameDetail.id">
                            {{ gameDetail.home_team.ryaku_name }} VS {{ gameDetail.visitor_team.ryaku_name }}<br />
                            DH: {{ gameDetail.dh_flag ? '有' : '無' }}
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
