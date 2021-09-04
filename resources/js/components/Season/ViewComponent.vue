<template>
    <div class="container">
        <h2>{{  data.season.name }}</h2>
        <div class="clearfix">
            <form v-on:submit.prevent="reShukei('/api/seasons/re-shukei/' + seasonId.toString())">
                <button type="submit" class="btn btn-success">再集計</button>
                <router-link v-bind:to="{name: 'game.index', params: {seasonId: seasonId.toString() }}">
                    <button class="btn btn-success">日程一覧</button>
                </router-link>
            </form>
        </div>
        <table class="table table-hover">
            <tr>
                <th></th>
                <th>チーム名</th>
                <th>試合</th>
                <th>勝</th>
                <th>負</th>
                <th>分</th>
                <th>勝率</th>
                <th>残</th>
            </tr>
            <tr v-for="team in data.teams">
                <td></td>
                <td>{{ team.name }}</td>
                <td>{{ team.game }}</td>
                <td>{{ team.win }}</td>
                <td>{{ team.lose }}</td>
                <td>{{ team.draw }}</td>
                <td>{{ team.win_ratio }}</td>
                <td>{{ team.remain }}</td>
            </tr>
        </table>

    </div>
</template>
<script>
    export default {
        methods: {
            initial() {
                // チーム情報など込みで詳細画面表示に必要な情報をまとめて取得（したい）
                this.getData('/api/seasons/detail/' + this.seasonId);
            },
            getData(getPath) {
                axios.get(getPath)
                    .then((res) => {
                        this.data = res.data;
                    });
            },
            reShukei(postPath) {
                axios.post(postPath, this.data)
                    .then((res) => {
                        initial();
                    });
            }
        },
        props: {
            seasonId: String
        },
        data: function () {
            return {
                data: {
                    season: {}
                }
            }
        },
        mounted() {
            this.initial();
        }
    }
</script>
