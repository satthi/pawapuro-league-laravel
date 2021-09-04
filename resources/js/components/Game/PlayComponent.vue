<template>
    <div class="container" id="play_wrap" v-if="Object.keys(enums).length">
        <h2>{{ gameData.date }} {{ gameData.home_team.name }} VS {{ gameData.visitor_team.name }}</h2>

        <div class="row">
            <div class="col-sm-3">
                <table class="table table-hover stamen visitor_stamen">
                    <tr v-for="(member, dajun) in this.playData.member.visitor_team" v-on:mouseover="stamenMouseover(member.player.id)" v-on:mouseleave="stamenLeave(member.player.id)">
                        <td v-bind:class="{'member_selected' : playData.now_player != null && member.player.id == playData.now_player.player.id}" style="width: 15px;">{{ member.position.text }}</td>
                        <td>
                            <div class="member_player_wrap">
                                {{ member.player.name_short }}
                                <div class="player_detail" v-bind:style="{'width': ( 70 * member.play_info.length + 20 ) + 'px' }" v-if="focusPlayer == member.player.id">
                                    <div class="player_detail_name">
                                        [{{ member.player.number }}] {{ member.player.name }}
                                    </div>
                                    <table class="table table-hover play_info_detail" v-bind:style="{'width': ( 70 * member.play_info.length ) + 'px' }">
                                        <tr>
                                            <td v-for="playInfo in member.play_info" :class='`result_button_${playInfo.result.button_type}`'>
                                                {{ playInfo.result.name }}
                                                <span v-if="playInfo.point_count > 0">({{ playInfo.point_count }})</span>
                                            </td>
                                        </tr>
                                    </table>

                                </div>
                            </div>
                        </td>
                        <td class="seiseki">
                            {{ member.seiseki.dageki }}
                        </td>
                    </tr>
                </table>
                <div class="clearfix">
                    <router-link v-bind:to="{name: 'game.ph', params: {gameId: gameId.toString(), teamType: 'visitor' }}" v-if="gameData.is_visitor_team_phpr">
                        <button class="btn btn-success">代打</button>
                    </router-link>

                    <router-link v-bind:to="{name: 'game.pr', params: {gameId: gameId.toString(), teamType: 'visitor' }}" v-if="gameData.is_visitor_team_phpr">
                        <button class="btn btn-success">代走</button>
                    </router-link>

                    <router-link v-bind:to="{name: 'game.steal', params: {gameId: gameId.toString(), teamType: 'visitor' }}" v-if="gameData.is_visitor_team_phpr">
                        <button class="btn btn-success">盗塁</button>
                    </router-link>

                    <router-link v-bind:to="{name: 'game.position', params: {gameId: gameId.toString(), teamType: 'visitor' }}" v-if="gameData.is_visitor_team_position">
                        <button class="btn btn-success">守備</button>
                    </router-link>
                </div>
                <div  v-if="gameData.is_visitor_team_phpr && playData.now_player != null">
                    <div>
                        [{{ playData.now_player.player.number }}] {{ playData.now_player.player.name }}
                    </div>
                    <div>
                        {{ playData.now_player.seiseki.dageki }}
                    </div>
                    <table class="table table-hover now_player_detail">
                        <tr v-for="playInfo in playData.now_player.playInfo" :class='`result_button_${playInfo.result.button_type}`'>
                            <td>
                                {{ playInfo.result.name }}
                                <span v-if="playInfo.point_count > 0">({{ playInfo.point_count }})</span>
                                [{{ playInfo.pitcher.name }}]
                            </td>
                        </tr>
                    </table>
                </div>

                <div  v-if="gameData.is_home_team_phpr && playData.now_pitcher != null">
                    <div>
                        [{{ playData.now_pitcher.player.number }}] {{ playData.now_pitcher.player.name }}
                    </div>
                    <div>
                        {{ playData.now_pitcher.seiseki.pitcher }}
                    </div>
                    <table class="table table-hover now_pitcher_detail">
                        <tr>
                            <td>投球回</td>
                            <td>{{ playData.now_pitcher.playInfo.inning_text }}</td>
                        </tr>
                        <tr>
                            <td>被安打</td>
                            <td>{{ playData.now_pitcher.playInfo.hit }}</td>
                        </tr>
                        <tr>
                            <td>被本塁打</td>
                            <td>{{ playData.now_pitcher.playInfo.hr }}</td>
                        </tr>
                        <tr>
                            <td>与四球</td>
                            <td>{{ playData.now_pitcher.playInfo.walk }}</td>
                        </tr>
                        <tr>
                            <td>与死球</td>
                            <td>{{ playData.now_pitcher.playInfo.dead }}</td>
                        </tr>
                        <tr>
                            <td>失点(参考)</td>
                            <td>{{ playData.now_pitcher.playInfo.point }}</td>
                        </tr>
                    </table>
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

                <div v-for="error in errors">
                    <div v-for="errorMessage in error" class="invalid-feedback" style="display:block;">{{ errorMessage }}</div>
                </div>

                <div v-if="gameData.board_status == enums.GameBoardStatus.STATUS_START.value">
                    <div class="row">
                        <div class="col-sm-3">
                            <form v-on:submit.prevent="submit(gameStartSubmitPath)">
                                <button type="submit" class="btn btn-primary" v-bind:disabled="disabled">試合開始</button>
                            </form>
                        </div>
                        <div class="col-sm-5">
                            <router-link v-bind:to="{name: 'game.view', params: {gameId: gameId.toString() }}">
                                <button class="btn btn-success">試合TOPに戻る</button>
                            </router-link>
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
                            <form v-on:submit.prevent="submit(playSubmitPath)">
                                <button type="submit" class="btn btn-primary" v-bind:disabled="disabled">登録</button>
                            </form>
                        </div>
                        <div class="col-sm-4">
                            <form v-on:submit.prevent="submit(pointOnlySubmitPath)">
                                <button type="submit" class="btn btn-primary" v-bind:disabled="disabled">点数のみ</button>
                            </form>
                        </div>
                        <div class="col-sm-2">
                        </div>
                        <div class="col-sm-2">
                            <form v-on:submit.prevent="submit(backSubmitPath)">
                                <button type="submit" class="btn btn-primary" v-bind:disabled="disabled">戻る</button>
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
                            <form v-on:submit.prevent="submit(nextInningSubmitPath)">
                                <button type="submit" class="btn btn-primary" v-bind:disabled="disabled || !gameData.is_next_inning">次のイニングへ</button>
                            </form>
                        </div>
                        <div class="col-sm-3">
                            <form v-on:submit.prevent="submit(backSubmitPath)">
                                <button type="submit" class="btn btn-primary" v-bind:disabled="disabled">戻る</button>
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
                            <th style="width: 20px;">勝</th>
                            <th style="width: 20px;">負</th>
                            <th style="width: 20px;">HP</th>
                            <th style="width: 20px;">SP</th>
                            <th style="width: 85px;">自責点</th>
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
                            <th style="width: 20px;">勝</th>
                            <th style="width: 20px;">負</th>
                            <th style="width: 20px;">HP</th>
                            <th style="width: 20px;">SP</th>
                            <th style="width: 85px;">自責点</th>
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
                            <form v-on:submit.prevent="submit(gameEndSubmitPath)">
                                <button type="submit" class="btn btn-primary" v-bind:disabled="disabled">試合終了</button>
                            </form>
                        </div>
                        <div class="col-sm-3">
                            <form v-on:submit.prevent="submit(backSubmitPath)">
                                <button type="submit" class="btn btn-primary" v-bind:disabled="disabled">戻る</button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-sm-3 clearfix">
                <table class="table table-hover stamen home_stamen">
                    <tr v-for="(member, dajun) in this.playData.member.home_team" v-on:mouseover="stamenMouseover(member.player.id)" v-on:mouseleave="stamenLeave(member.player.id)">
                        <td v-bind:class="{'member_selected' : playData.now_player != null && member.player.id == playData.now_player.player.id}" style="width: 15px;">{{ member.position.text }}</td>
                        <td>
                            <div class="member_player_wrap">
                                {{ member.player.name_short }}
                                <div class="player_detail" v-bind:style="{'width': ( 70 * member.play_info.length + 20 ) + 'px' }" v-if="focusPlayer == member.player.id">
                                    <div class="player_detail_name">
                                        [{{ member.player.number }}] {{ member.player.name }}
                                    </div>
                                    <table class="table table-hover play_info_detail" v-bind:style="{'width': ( 70 * member.play_info.length ) + 'px' }">
                                        <tr>
                                            <td v-for="playInfo in member.play_info" :class='`result_button_${playInfo.result.button_type}`'>
                                                {{ playInfo.result.name }}
                                                <span v-if="playInfo.point_count > 0">({{ playInfo.point_count }})</span>
                                            </td>
                                        </tr>
                                    </table>

                                </div>
                            </div>
                        </td>
                        <td class="seiseki">
                            {{ member.seiseki.dageki }}
                        </td>
                    </tr>
                </table>

                <div class="clearfix">
                    <router-link v-bind:to="{name: 'game.ph', params: {gameId: gameId.toString(), teamType: 'home' }}" v-if="gameData.is_home_team_phpr">
                        <button class="btn btn-success">代打</button>
                    </router-link>

                    <router-link v-bind:to="{name: 'game.pr', params: {gameId: gameId.toString(), teamType: 'home' }}" v-if="gameData.is_home_team_phpr">
                        <button class="btn btn-success">代走</button>
                    </router-link>

                    <router-link v-bind:to="{name: 'game.steal', params: {gameId: gameId.toString(), teamType: 'home' }}" v-if="gameData.is_home_team_phpr">
                        <button class="btn btn-success">盗塁</button>
                    </router-link>

                    <router-link v-bind:to="{name: 'game.position', params: {gameId: gameId.toString(), teamType: 'home' }}" v-if="gameData.is_home_team_position">
                        <button class="btn btn-success">守備</button>
                    </router-link>
                </div>

                <div  v-if="gameData.is_home_team_phpr && playData.now_player != null">
                    <div>
                        [{{ playData.now_player.player.number }}] {{ playData.now_player.player.name }}
                    </div>
                    <div>
                        {{ playData.now_player.seiseki.dageki }}
                    </div>
                    <table class="table table-hover now_player_detail">
                        <tr v-for="playInfo in playData.now_player.playInfo" :class='`result_button_${playInfo.result.button_type}`'>
                            <td>
                                {{ playInfo.result.name }}
                                <span v-if="playInfo.point_count > 0">({{ playInfo.point_count }})</span>
                                [{{ playInfo.pitcher.name }}]
                            </td>
                        </tr>
                    </table>

                </div>
                <div  v-if="gameData.is_visitor_team_phpr && playData.now_pitcher != null">
                    <div>
                        [{{ playData.now_pitcher.player.number }}] {{ playData.now_pitcher.player.name }}
                    </div>
                    <div>
                        {{ playData.now_pitcher.seiseki.pitcher }}
                    </div>
                    <table class="table table-hover now_pitcher_detail">
                        <tr>
                            <td>投球回</td>
                            <td>{{ playData.now_pitcher.playInfo.inning_text }}</td>
                        </tr>
                        <tr>
                            <td>被安打</td>
                            <td>{{ playData.now_pitcher.playInfo.hit }}</td>
                        </tr>
                        <tr>
                            <td>被本塁打</td>
                            <td>{{ playData.now_pitcher.playInfo.hr }}</td>
                        </tr>
                        <tr>
                            <td>与四球</td>
                            <td>{{ playData.now_pitcher.playInfo.walk }}</td>
                        </tr>
                        <tr>
                            <td>与死球</td>
                            <td>{{ playData.now_pitcher.playInfo.dead }}</td>
                        </tr>
                        <tr>
                            <td>失点(参考)</td>
                            <td>{{ playData.now_pitcher.playInfo.point }}</td>
                        </tr>
                    </table>
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
          gameStartSubmitPath() {
            return '/api/games/save-game-start/' + this.gameId;
          },
          playSubmitPath() {
            return '/api/games/save-play/' + this.gameId;
          },
          pointOnlySubmitPath() {
            return '/api/games/save-point-only/' + this.gameId;
          },
          backSubmitPath() {
            return '/api/games/back-play/' + this.gameId;
          },
          nextInningSubmitPath() {
            return '/api/games/next-inning-play/' + this.gameId;
          },
          gameEndSubmitPath() {
            return '/api/games/game-end-play/' + this.gameId;
          }
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
                    'now_player' : null,
                    'now_pitcher' : null,
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
                disabled: false,
                nowPlayer: {},
                focusPlayer: null,
            }
        },
        methods: {
            initial() {
                axios.get('/api/games/view/' + this.gameId)
                    .then((res) => {
                        if (res.data.board_status == 6) { // GameBoardStatus::STATUS_GAMEENDED enumを読み切れてないパターンがあるので
                            // 試合終了済み
                            this.$router.push({name: 'game.summary', params: {gameId: this.gameId.toString() }});
                        } else {
                            this.gameData = res.data;
                            axios.get('/api/games/get-play/' + this.gameId)
                                .then((res) => {
                                    this.playData = res.data;
                                    this.disabled = false;
                                });
                            axios.get('/api/games/get-result')
                                .then((res) => {
                                    this.resultData = res.data;
                                });
                            this.data.out = 0;
                            this.data.point = 0;
                            this.data.selectedResult = null;
                            this.errors = {};
                        }
                    });
            },
            resultButtonClick(resultId) {
                this.data.selectedResult = resultId

                this.data.out = this.resultData[resultId].out_count;
            },
            stamenMouseover(playerId) {
                this.focusPlayer = playerId
            },
            stamenLeave(playerId) {
                if (this.focusPlayer == playerId) {
                    this.focusPlayer = null
                }
            },
            submit(postPath) {
                this.disabled = true;
                var postData = this.data;
                postData['game_id'] = this.gameId;
                postData['now_player_id'] = this.playData.now_player != null ? this.playData.now_player.player.id : null;
                postData['now_pitcher_id'] = this.playData.now_pitcher != null ? this.playData.now_pitcher.player.id : null;
                axios.post(postPath, this.data)
                    .then((res) => {
                        this.initial();
                    })
                    .catch((error) => {
                        this.errors = error.response.data.errors;
                        this.disabled = false;
                    });
            }
        },
        mounted() {

            this.initial();
        }
    }
</script>
