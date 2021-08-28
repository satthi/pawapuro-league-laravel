<template>
    <div class="container" id="play_wrap" v-if="Object.keys(enums).length">
        <h2>{{ gameData.date }} {{ gameData.home_team.name }} VS {{ gameData.visitor_team.name }}</h2>

        <div class="row">
            <div class="col-sm-3">
                <table class="table table-hover stamen">
                    <tr v-for="(member, dajun) in this.playData.member.visitor_team">
                        <td v-bind:class="{'member_selected' : member.player.id == playData.now_player_id}">{{ member.position.text }}</td>
                        <td>{{ member.player.name_short }}</td>
                    </tr>
                </table>
                <div class="clearfix">
                    <router-link v-bind:to="{name: 'game.ph', params: {gameId: gameId.toString(), teamType: 'visitor' }}" v-if="gameData.is_visitor_team_phpr">
                        <button class="btn btn-success">代打</button>
                    </router-link>

                    <router-link v-bind:to="{name: 'game.pr', params: {gameId: gameId.toString(), teamType: 'visitor' }}" v-if="gameData.is_visitor_team_phpr">
                        <button class="btn btn-success">代走</button>
                    </router-link>

                    <router-link v-bind:to="{name: 'game.position', params: {gameId: gameId.toString(), teamType: 'visitor' }}" v-if="gameData.is_visitor_team_position">
                        <button class="btn btn-success">守備</button>
                    </router-link>
                </div>

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
                        <td>{{ gameData.visitor_team.ryaku_name }}</td>
                        <td v-for="inning in 12" v-bind:class="{'inning_selected' : gameData.inning == inning * 10 + 1}">{{ playData.inning_info.inning[inning * 10 + 1] }}</td>
                        <td>{{ playData.inning_info.visitor_point }}</td>
                        <td>{{ playData.inning_info.visitor_hit }}</td>
                    </tr>
                    <tr>
                        <td>{{ gameData.home_team.ryaku_name }}</td>
                        <td v-for="inning in 12" v-bind:class="{'inning_selected' : gameData.inning == inning * 10 + 2}">{{ playData.inning_info.inning[inning * 10 + 2] }}</td>
                        <td>{{ playData.inning_info.home_point }}</td>
                        <td>{{ playData.inning_info.home_hit }}</td>
                    </tr>
                </table>

                <div v-if="gameData.board_status == enums.GameBoardStatus.STATUS_START.value">
                    <div class="row">
                        <div class="col-sm-3">
                            <form v-on:submit.prevent="submit(playSubmitPath, {name: 'game.play', params: {gameId: gameId.toString() }})">
                                <button type="submit" class="btn btn-primary">試合開始</button>
                            </form>
                        </div>
                        <div class="col-sm-3">
                            <form v-on:submit.prevent="submit(backSubmitPath, {name: 'game.play', params: {gameId: gameId.toString() }})">
                                <button type="submit" class="btn btn-primary">戻る</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div v-else-if="gameData.board_status == enums.GameBoardStatus.STATUS_GAME.value">
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
                <div v-else-if="gameData.board_status == enums.GameBoardStatus.STATUS_INNING_END.value">
                    <!-- イニング終了 -->
                    <div class="row">
                        <div class="col-sm-4">
                            <form v-on:submit.prevent="submit(nextInningSubmitPath, {name: 'game.play', params: {gameId: gameId.toString() }})">
                                <button type="submit" class="btn btn-primary">次のイニングへ</button>
                            </form>
                        </div>
                        <div class="col-sm-3">
                            <form v-on:submit.prevent="submit(backSubmitPath, {name: 'game.play', params: {gameId: gameId.toString() }})">
                                <button type="submit" class="btn btn-primary">戻る</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div v-else-if="gameData.board_status == enums.GameBoardStatus.STATUS_GAMEEND.value">
                    <!-- 試合終了 -->
                    <table class="table table-hover">
                        <tr>
                            <th colspan="6">{{ gameData.visitor_team.name }}</th>
                        </tr>
                        <tr>
                            <th></th>
                            <th>勝</th>
                            <th>負</th>
                            <th>ホールド</th>
                            <th>セーブ</th>
                            <th>自責点</th>
                        </tr>
                        <tr v-for="pitcher in playData.pithcer_info.visitor_team">
                            <td>{{ pitcher.player.name }}</td>
                            <td>
                                <radio-component label="" :errors="errors.regular_flag" name="win" :radio_value="pitcher.player.id" v-model="data.pitcherResult.win" v-if="gameData.home_point < gameData.visitor_point"/>
                            </td>
                            <td>
                                <radio-component label="" :errors="errors.regular_flag" name="lose" :radio_value="pitcher.player.id" v-model="data.pitcherResult.lose" v-if="gameData.home_point > gameData.visitor_point"/>
                            </td>
                            <td>
                                <checkbox-component label="" :errors="errors.regular_flag" v-model="data.pitcherResult.hold[pitcher.player.id]"/>
                            </td>
                            <td>
                                <radio-component label="" :errors="errors.regular_flag" name="save" :radio_value="pitcher.player.id" v-model="data.pitcherResult.save" v-if="gameData.home_point < gameData.visitor_point"/>
                            </td>
                            <td>
                                <input-component label="" :errors="errors.name" v-model="data.pitcherResult.jiseki[pitcher.player.id]"/>
                            </td>
                        </tr>
                        <tr>
                            <th colspan="6">{{ gameData.home_team.name }}</th>
                        </tr>
                        <tr>
                            <th></th>
                            <th>勝</th>
                            <th>負</th>
                            <th>ホールド</th>
                            <th>セーブ</th>
                            <th>自責点</th>
                        </tr>
                        <tr v-for="pitcher in playData.pithcer_info.home_team">
                            <td>{{ pitcher.player.name }}</td>
                            <td>
                                <radio-component label="" :errors="errors.regular_flag" name="win" :radio_value="pitcher.player.id" v-model="data.pitcherResult.win" v-if="gameData.home_point > gameData.visitor_point"/>
                            </td>
                            <td>
                                <radio-component label="" :errors="errors.regular_flag" name="lose" :radio_value="pitcher.player.id" v-model="data.pitcherResult.lose" v-if="gameData.home_point < gameData.visitor_point"/>
                            </td>
                            <td>
                                <checkbox-component label="" :errors="errors.regular_flag" v-model="data.pitcherResult.hold[pitcher.player.id]"/>
                            </td>
                            <td>
                                <radio-component label="" :errors="errors.regular_flag" name="save" :radio_value="pitcher.player.id" v-model="data.pitcherResult.save" v-if="gameData.home_point > gameData.visitor_point"/>
                            </td>
                            <td>
                                <input-component label="" :errors="errors.name" v-model="data.pitcherResult.jiseki[pitcher.player.id]"/>
                            </td>
                        </tr>
                    </table>
                    <div class="row">
                        <div class="col-sm-4">
                            <form v-on:submit.prevent="submit(gameEndSubmitPath, {name: 'game.play', params: {gameId: gameId.toString() }})">
                                <button type="submit" class="btn btn-primary">試合終了</button>
                            </form>
                        </div>
                        <div class="col-sm-3">
                            <form v-on:submit.prevent="submit(backSubmitPath, {name: 'game.play', params: {gameId: gameId.toString() }})">
                                <button type="submit" class="btn btn-primary">戻る</button>
                            </form>
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

                <div class="clearfix">
                    <router-link v-bind:to="{name: 'game.ph', params: {gameId: gameId.toString(), teamType: 'home' }}" v-if="gameData.is_home_team_phpr">
                        <button class="btn btn-success">代打</button>
                    </router-link>

                    <router-link v-bind:to="{name: 'game.pr', params: {gameId: gameId.toString(), teamType: 'home' }}" v-if="gameData.is_home_team_phpr">
                        <button class="btn btn-success">代走</button>
                    </router-link>

                    <router-link v-bind:to="{name: 'game.position', params: {gameId: gameId.toString(), teamType: 'home' }}" v-if="gameData.is_home_team_position">
                        <button class="btn btn-success">守備</button>
                    </router-link>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import InputComponent from '../common/form/InputComponent';
    import CheckboxComponent from '../common/form/CheckboxComponent';
    import SelectComponent from '../common/form/SelectComponent';
    import RadioComponent from '../common/form/RadioComponent';
    import EnumsMixin from '../../mixins/enums.js';
    export default {
        components: {SelectComponent, InputComponent, CheckboxComponent, RadioComponent},
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
          nextInningSubmitPath() {
            return '/api/games/next-inning-play/' + this.gameId;
          },
          gameEndSubmitPath() {
            return '/api/games/game-end-play/' + this.gameId;
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
                    'inning_info': {
                        inning: {}
                    },
                    'pithcer_info' : {
                        'home_team' : {},
                        'visitor_team' : {},
                    }
                },
                resultData: {},
                errors: {},
                data: {
                    'out' : 0,
                    'point' : 0,
                    selectedResult : null,
                    'pitcherResult' : {
                        'win' : null,
                        'lose' : null,
                        'save' : null,
                        'hold' : {},
                        'jiseki' : {},
                    },
                },
            }
        },
        methods: {
            initial() {
                axios.get('/api/games/view/' + this.gameId)
                    .then((res) => {
                        console.log(res.data)
                        if (res.data.board_status == 5) { // enumを読み切れてないパターンがあるので
                            // 試合終了済み
                            this.$router.push({name: 'game.result', params: {gameId: this.gameId.toString() }});
                        } else {
                            this.gameData = res.data;
                            axios.get('/api/games/get-play/' + this.gameId)
                                .then((res) => {
                                    this.playData = res.data;
                                });
                            axios.get('/api/games/get-result')
                                .then((res) => {
                                    this.resultData = res.data;
                                });
                            this.data.out = 0;
                            this.data.point = 0;
                            this.data.selectedResult = null;
                        }
                    });
            },
            resultButtonClick(resultId) {
                this.data.selectedResult = resultId

                this.data.out = this.resultData[resultId].out_count;
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
