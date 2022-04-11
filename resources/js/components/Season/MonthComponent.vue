<template>
    <div class="container" v-if="Object.keys(data).length">
        <h2>{{  data.season.name }} ({{ month }})</h2>
        <div class="clearfix">
            <router-link v-bind:to="{name: 'season.view', params: {seasonId: seasonId.toString() }}">
                <button class="btn btn-success">詳細</button>
            </router-link>
            <router-link v-for="(monthListParts, index) in data.monthList" :key="index" v-bind:to="{name: 'season.month', params: { seasonId: seasonId.toString(), month: monthListParts.month }}">
                <button class="btn btn-success">{{ monthListParts.month }}</button>&nbsp;
            </router-link>
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
            </tr>
            <tr v-for="team in monthData">
                <td>{{ team.rank }}</td>
                <td>{{ team.name }}</td>
                <td>{{ team.game }}</td>
                <td>{{ team.win }}</td>
                <td>{{ team.lose }}</td>
                <td>{{ team.draw }}</td>
                <td>{{ (Math.round(team.win_ratio * 1000) / 1000).toFixed(3) }}</td>
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
                this.getMonthData('/api/seasons/monthData/' + this.seasonId + '/' + this.month);

            },
            getData(getPath) {
                axios.get(getPath)
                    .then((res) => {
                    console.log(res)
                        this.data = res.data;
                    });
            },
            getMonthData(getPath) {
                axios.get(getPath)
                    .then((res) => {
                    console.log(res)
                        this.monthData = res.data;
                    });
            },
        },
        props: {
            seasonId: String,
            month: String
        },
        data: function () {
            return {
                data: {},
                monthData: {},
            }
        },
        mounted() {
            this.initial();
        }
    }
</script>
