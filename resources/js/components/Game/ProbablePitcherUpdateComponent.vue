<template>
    <div class="container">
        <h2>{{ data.date }} {{ data.home_team.name }} VS {{ data.visitor_team.name }} DH: {{ data.dh_flag ? '有' : '無' }}</h2>
        <form v-on:submit.prevent="submit(submitPath, {name: 'game.view', params: {gameId: gameId.toString() }})">
            <div class="row justify-content-center">
                <div class="col-sm-6">
                    <h3>{{ data.visitor_team.name }}</h3>
                    <select-component label="先行 予告先発" :errors="errors.visitor_probable_pitcher_id" :options="visitorPlayerOptions" :empty=true :value="data.visitor_probable_pitcher_id" v-model="data.visitor_probable_pitcher_id"/>
                    
                    <table class="table table-hover">
                        <tr>
                            <th>月</th>
                            <th>火</th>
                            <th>水</th>
                            <th>木</th>
                            <th>金</th>
                            <th>土</th>
                            <th>日</th>
                        </tr>
                        <tr>
                            <td>{{ visitorGameSchedule[0].date }}<br />{{ visitorGameSchedule[0].team }}</td>
                            <td>{{ visitorGameSchedule[1].date }}<br />{{ visitorGameSchedule[1].team }}</td>
                            <td>{{ visitorGameSchedule[2].date }}<br />{{ visitorGameSchedule[2].team }}</td>
                            <td>{{ visitorGameSchedule[3].date }}<br />{{ visitorGameSchedule[3].team }}</td>
                            <td>{{ visitorGameSchedule[4].date }}<br />{{ visitorGameSchedule[4].team }}</td>
                            <td>{{ visitorGameSchedule[5].date }}<br />{{ visitorGameSchedule[5].team }}</td>
                            <td>{{ visitorGameSchedule[6].date }}<br />{{ visitorGameSchedule[6].team }}</td>
                        </tr>
                        <tr>
                            <td>{{ visitorGameSchedule[7].date }}<br />{{ visitorGameSchedule[7].team }}</td>
                            <td>{{ visitorGameSchedule[8].date }}<br />{{ visitorGameSchedule[8].team }}</td>
                            <td>{{ visitorGameSchedule[9].date }}<br />{{ visitorGameSchedule[9].team }}</td>
                            <td>{{ visitorGameSchedule[10].date }}<br />{{ visitorGameSchedule[10].team }}</td>
                            <td>{{ visitorGameSchedule[11].date }}<br />{{ visitorGameSchedule[11].team }}</td>
                            <td>{{ visitorGameSchedule[12].date }}<br />{{ visitorGameSchedule[12].team }}</td>
                            <td>{{ visitorGameSchedule[13].date }}<br />{{ visitorGameSchedule[13].team }}</td>
                        </tr>
                        <tr>
                            <td>{{ visitorGameSchedule[14].date }}<br />{{ visitorGameSchedule[7].team }}</td>
                            <td>{{ visitorGameSchedule[15].date }}<br />{{ visitorGameSchedule[8].team }}</td>
                            <td>{{ visitorGameSchedule[16].date }}<br />{{ visitorGameSchedule[9].team }}</td>
                            <td>{{ visitorGameSchedule[17].date }}<br />{{ visitorGameSchedule[10].team }}</td>
                            <td>{{ visitorGameSchedule[18].date }}<br />{{ visitorGameSchedule[11].team }}</td>
                            <td>{{ visitorGameSchedule[19].date }}<br />{{ visitorGameSchedule[12].team }}</td>
                            <td>{{ visitorGameSchedule[20].date }}<br />{{ visitorGameSchedule[13].team }}</td>
                        </tr>
                    </table>
                    <table class="table table-hover">
                        <tr v-for="visitorHistory in visitorHistories">
                            <td>{{ visitorHistory.date }}</td>
                            <td v-for="visitorHistoryPlayer in visitorHistory.info">
                                {{ visitorHistoryPlayer.player }} ({{ visitorHistoryPlayer.inning }})
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="col-sm-6">
                    <h3>{{ data.home_team.name }}</h3>
                    <select-component label="後攻 予告先発" :errors="errors.home_probable_pitcher_id" :options="homePlayerOptions" :empty=true :value="data.home_probable_pitcher_id" v-model="data.home_probable_pitcher_id"/>
                    <table class="table table-hover">
                        <tr>
                            <th>月</th>
                            <th>火</th>
                            <th>水</th>
                            <th>木</th>
                            <th>金</th>
                            <th>土</th>
                            <th>日</th>
                        </tr>
                        <tr>
                            <td>{{ homeGameSchedule[0].date }}<br />{{ homeGameSchedule[0].team }}</td>
                            <td>{{ homeGameSchedule[1].date }}<br />{{ homeGameSchedule[1].team }}</td>
                            <td>{{ homeGameSchedule[2].date }}<br />{{ homeGameSchedule[2].team }}</td>
                            <td>{{ homeGameSchedule[3].date }}<br />{{ homeGameSchedule[3].team }}</td>
                            <td>{{ homeGameSchedule[4].date }}<br />{{ homeGameSchedule[4].team }}</td>
                            <td>{{ homeGameSchedule[5].date }}<br />{{ homeGameSchedule[5].team }}</td>
                            <td>{{ homeGameSchedule[6].date }}<br />{{ homeGameSchedule[6].team }}</td>
                        </tr>
                        <tr>
                            <td>{{ homeGameSchedule[7].date }}<br />{{ homeGameSchedule[7].team }}</td>
                            <td>{{ homeGameSchedule[8].date }}<br />{{ homeGameSchedule[8].team }}</td>
                            <td>{{ homeGameSchedule[9].date }}<br />{{ homeGameSchedule[9].team }}</td>
                            <td>{{ homeGameSchedule[10].date }}<br />{{ homeGameSchedule[10].team }}</td>
                            <td>{{ homeGameSchedule[11].date }}<br />{{ homeGameSchedule[11].team }}</td>
                            <td>{{ homeGameSchedule[12].date }}<br />{{ homeGameSchedule[12].team }}</td>
                            <td>{{ homeGameSchedule[13].date }}<br />{{ homeGameSchedule[13].team }}</td>
                        </tr>
                        <tr>
                            <td>{{ homeGameSchedule[14].date }}<br />{{ homeGameSchedule[7].team }}</td>
                            <td>{{ homeGameSchedule[15].date }}<br />{{ homeGameSchedule[8].team }}</td>
                            <td>{{ homeGameSchedule[16].date }}<br />{{ homeGameSchedule[9].team }}</td>
                            <td>{{ homeGameSchedule[17].date }}<br />{{ homeGameSchedule[10].team }}</td>
                            <td>{{ homeGameSchedule[18].date }}<br />{{ homeGameSchedule[11].team }}</td>
                            <td>{{ homeGameSchedule[19].date }}<br />{{ homeGameSchedule[12].team }}</td>
                            <td>{{ homeGameSchedule[20].date }}<br />{{ homeGameSchedule[13].team }}</td>
                        </tr>
                    </table>
                    <table class="table table-hover">
                        <tr v-for="homeHistory in homeHistories">
                            <td>{{ homeHistory.date }}</td>
                            <td v-for="homeHistoryPlayer in homeHistory.info">
                                {{ homeHistoryPlayer.player }} ({{ homeHistoryPlayer.inning }})
                            </td>
                        </tr>
                    </table>


                    <button type="submit" class="btn btn-primary">Submit</button>

                    <router-link v-bind:to="{name: 'game.view', params: {gameId: gameId.toString() }}">
                        <button class="btn btn-success float-right">ゲーム詳細に戻る</button>
                    </router-link>

                </div>
            </div>
        </form>
    </div>
</template>

<script>
    import SelectComponent from '../common/form/SelectComponent';
    import editMixin from '../../mixins/form/edit.js';
    export default {
        components: {SelectComponent},
        mixins : [editMixin],
        props: {
            gameId: String
        },
        computed: {
          submitPath() {
            return '/api/games/probable-pitcher-edit/' + this.gameId;
          }
        },
        data: function () {
            return {
                data: {},
                errors: {},
                homePlayerOptions: {},
                visitorPlayerOptions: {},
                homeHistories: {},
                visitorHistories: {},
                homeGameSchedule: {},
                visitorGameSchedule: {}
            }
        },
        methods: {
            getPlayerOptions(getPath) {
                axios.get(getPath)
                    .then((res) => {
                        this.homePlayerOptions = res.data.home;
                        this.visitorPlayerOptions = res.data.visitor;
                        this.homeHistories = res.data.home_hisory;
                        this.visitorHistories = res.data.visitor_hisory;
                        this.homeGameSchedule = res.data.home_game_schedule;
                        this.visitorGameSchedule = res.data.visitor_game_schedule;
                    });
            },
        },
        mounted() {
            this.getPlayerOptions('/api/games/get-probable-pitcher-options/' + this.gameId)
            this.getData('/api/games/view/' + this.gameId);
        }
    }
</script>