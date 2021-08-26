<template>
    <div class="container" id="play_wrap">
        <h2>{{ gameData.date }} {{ gameData.home_team.name }} VS {{ gameData.visitor_team.name }}</h2>

        <div class="row">
            <div class="col-sm-3">
                <table class="table table-hover stamen">
                    <tr v-for="(member, dajun) in this.playData.member.visitor_team">
                        <td v-bind:class="{'member_selected' : member.player.id == playData.now_player_id}">{{ member.position.text }}</td>
                        <td>{{ member.player.name_short }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-sm-6">
                <!-- スコアボード -->
                <table class="table table-hover" id="score_board">
                    <tr>
                        <td></td>
                        <td v-for="inning in 12">{{ inning }}</td>
                        <td>R</td>
                        <td>H</td>
                    </tr>
                    <tr>
                        <td>{{ gameData.home_team.ryaku_name }}</td>
                        <td v-for="inning in 12" v-bind:class="{'inning_selected' : gameData.inning == inning * 10 + 1}">0</td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>{{ gameData.visitor_team.ryaku_name }}</td>
                        <td v-for="inning in 12" v-bind:class="{'inning_selected' : gameData.inning == inning * 10 + 2}">0</td>
                        <td></td>
                        <td></td>
                    </tr>
                </table>

                <div v-if="gameData.inning == null">
                    <form v-on:submit.prevent="submit(playSubmitPath, {name: 'game.play', params: {gameId: gameId.toString() }})">
                        <button type="submit" class="btn btn-primary">試合開始</button>
                    </form>
                </div>
                <div v-else-if="gameData.out != 3">
                    <!-- 操作ボード -->
                    <div class="row">
                        <div class="col-sm-3">
                            <select-component label="" :options="enums.ResultOut" :empty=false v-model="data.out"/>
                        </div>
                        <div class="col-sm-3">
                            <select-component label="" :options="enums.ResultPoint" :empty=false v-model="data.point"/>
                        </div>
                        <div class="col-sm-3">
                            {{ gameData.out }}アウト
                        </div>
                    </div>

                    <div class="clearfix">
                        <table v-for="button_position in 10" class="board_table">
                            <tr v-for="result in resultData" v-if="result.button_position == (button_position - 1)" :class='`result_button_${result.button_type}`'>
                                <td v-on:click="resultButtonClick(result.id)"  v-bind:class="{'result_selected' : result.id == data.selectedResult}">{{ result.name }}</td>
                            </tr>
                        </table>
                    </div>

                    <div class="row" style="margin-top:20px;">
                        <div class="col-sm-1">
                        </div>
                        <div class="col-sm-2">
                            <form v-on:submit.prevent="submit(playSubmitPath, {name: 'game.play', params: {gameId: gameId.toString() }})">
                                <button type="submit" class="btn btn-primary">登録</button>
                            </form>
                        </div>
                        <div class="col-sm-6">
                        </div>
                        <div class="col-sm-2">
                            <form v-on:submit.prevent="submit(backSubmitPath, {name: 'game.play', params: {gameId: gameId.toString() }})">
                                <button type="submit" class="btn btn-primary">戻る</button>
                            </form>
                        </div>
                        <div class="col-sm-1">
                        </div>
                    </div>
                </div>


            </div>
            <div class="col-sm-3 clearfix">
                <table class="table table-hover stamen">
                    <tr v-for="(member, dajun) in this.playData.member.home_team">
                        <td v-bind:class="{'member_selected' : member.player.id == playData.now_player_id}">{{ member.position.text }}</td>
                        <td>{{ member.player.name_short }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
    import SelectComponent from '../common/form/SelectComponent';
    import EnumsMixin from '../../mixins/enums.js';
    export default {
        components: {SelectComponent},
        props: {
            gameId: String
        },
        mixins : [EnumsMixin],
        computed: {
          playSubmitPath() {
            return '/api/games/save-play/' + this.gameId;
          },
          backSubmitPath() {
            return '/api/games/back-play/' + this.gameId;
          },
        },
        data: function () {
            return {
                gameData: {
                    'home_team' : {},
                    'visitor_team' : {},
                },
                playData: {
                    'member' : {
                        'home_team' : {},
                        'visitor_team' : {},
                    },
                    'now_player_id' : null,
                    'now_pitcher_id' : null,
                },
                resultData: {},
                data: {
                    'out' : 0,
                    'point' : 0,
                    selectedResult : null,
                },
            }
        },
        methods: {
            initial() {
                this.getGameData('/api/games/view/' + this.gameId);
                this.getPlayData('/api/games/get-play/' + this.gameId);
                this.getResultData('/api/games/get-result');
            },
            getPlayData(getPath) {
                axios.get(getPath)
                    .then((res) => {
                        this.playData = res.data;
                    });
            },
            getGameData(getPath) {
                axios.get(getPath)
                    .then((res) => {
                        this.gameData = res.data;
                    });
            },
            getResultData(getPath) {
                axios.get(getPath)
                    .then((res) => {
                        this.resultData = res.data;
                    });
            },
            resultButtonClick(resultId) {
                this.data.selectedResult = resultId
            },
            submit(postPath, redirectRoute) {
                var postData = this.data;
                postData['now_player_id'] = this.playData.now_player_id;
                postData['now_pitcher_id'] = this.playData.now_pitcher_id;
                axios.post(postPath, this.data)
                    .then((res) => {
                        this.initial();
                    });
            }
        },
        mounted() {
            this.initial();
        }
    }
</script>
