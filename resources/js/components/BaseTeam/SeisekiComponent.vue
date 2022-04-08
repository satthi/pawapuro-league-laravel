<template>
    <div class="container">
        <h2>チーム成績</h2>
        <table class="table table-hover">
            <tr>
                <th>チーム名</th>
                <th>試合</th>
                <th>勝</th>
                <th>負</th>
                <th>分</th>
                <th>勝率</th>
                <th>打率</th>
                <th>HR</th>
                <th>防御率</th>
            </tr>
            <tr v-for="team in teams">
                <td>{{ team.name }}</td>
                <td>{{ team.game }}</td>
                <td>{{ team.win }}</td>
                <td>{{ team.lose }}</td>
                <td>{{ team.draw }}</td>
                <td>{{ (Math.round(team.win / (team.win + team.lose) * 1000) / 1000).toFixed(3) }}</td>
                <td>{{ (Math.round(team.avg * 1000) / 1000).toFixed(3) }}</td>
                <td>{{ team.hr }}</td>
                <td>{{ (Math.round(team.era * 100) / 100).toFixed(2) }}</td>
            </tr>
        </table>

        <h2>順位変遷</h2>
        <table class="table table-hover">
            <tr>
                <th v-for="team in teams">
                    {{ team.ryaku_name }}
                </th>
            </tr>
            <tr v-for="hensenSeason in hensen">
                <td v-for="team in teams">{{ hensenSeason[team.id] }}</td>
            </tr>
        </table>
    </div>
</template>
<script>
    export default {
        methods: {
            initial() {
                // チーム情報など込みで詳細画面表示に必要な情報をまとめて取得（したい）
                this.getData('/api/base-teams/seiseki');
                this.getHensen('/api/base-teams/rankHensen');

            },
            getData(getPath) {
                axios.get(getPath)
                    .then((res) => {
                        this.teams = res.data;
                    });
            },
            getHensen(getPath) {
                axios.get(getPath)
                    .then((res) => {
                        this.hensen = res.data;
                        console.log(this.hensen)
                    });
            }
        },
        data: function () {
            return {
                teams: {},
                hensen: {},
            }
        },
        mounted() {
            this.initial();
        }
    }
</script>
