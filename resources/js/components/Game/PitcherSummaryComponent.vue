<template>
    <div class="container" id="play_wrap" v-if="Object.keys(enums).length">
        <h2>{{ gameData.date }} {{ gameData.home_team.name }} VS {{ gameData.visitor_team.name }}</h2>
        <router-link v-bind:to="{name: 'season.view', params: {seasonId: gameData.season_id.toString() }}">
            <button class="btn btn-success">シーズン詳細</button>
        </router-link>
        <router-link v-bind:to="{name: 'game.index', params: {seasonId: gameData.season_id.toString() }}">
            <button class="btn btn-success">日程一覧</button>
        </router-link>
        <router-link v-bind:to="{name: 'game.view', params: {seasonId: gameData.id.toString() }}">
            <button style="margin-right:50px;" class="btn btn-success">ゲームTOP</button>
        </router-link>

        <router-link v-if="gameData.prev_game_id" v-bind:to="{name: 'game.summary', params: {gameId: gameData.prev_game_id.toString() }}">
            <button class="btn btn-success">前のゲーム</button>
        </router-link>
        <router-link v-if="gameData.next_game_id" v-bind:to="{name: 'game.summary', params: {gameId: gameData.next_game_id.toString() }}">
            <button class="btn btn-success">次のゲーム</button>
        </router-link>

        <div class="row">
            <div class="col-sm-3">
                <table class="table table-hover stamen">
                    <tr v-for="(member, dajun) in this.playData.member.visitor_team">
                        <td v-bind:class="{'member_selected' : member.player.id == playData.now_player_id}">{{ member.position.text }}</td>
                        <td>{{ member.player.name_short }}</td>
                        <td class="seiseki">
                            {{ member.seiseki.dageki }}
                        </td>
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

                <div>
                    <h5>試合結果</h5>

                    <form v-on:submit.prevent="submit(gameBackSubmitPath)">
                        <router-link v-bind:to="{name: 'game.summary', params: {gameId: gameId.toString() }}">
                            <button class="btn btn-success">TOP</button>
                        </router-link>

                        <router-link v-bind:to="{name: 'game.fielder_summary', params: {gameId: gameId.toString(), type: 'visitor' }}">
                            <button class="btn btn-success">野手({{ gameData.visitor_team.ryaku_name }} )</button>
                        </router-link>
                        <router-link v-bind:to="{name: 'game.fielder_summary', params: {gameId: gameId.toString(), type: 'home' }}">
                            <button class="btn btn-success">野手({{ gameData.home_team.ryaku_name }})</button>
                        </router-link>
                        <router-link v-bind:to="{name: 'game.pitcher_summary', params: {gameId: gameId.toString(), type: 'visitor' }}">
                            <button class="btn btn-success" v-bind:disabled="type == 'visitor'" v-on:click="initial">投手({{ gameData.visitor_team.ryaku_name }})</button>
                        </router-link>
                        <router-link v-bind:to="{name: 'game.pitcher_summary', params: {gameId: gameId.toString(), type: 'home' }}">
                            <button class="btn btn-success" v-bind:disabled="type == 'home'" v-on:click="initial">投手({{ gameData.home_team.ryaku_name }})</button>
                        </router-link>
                        <button type="submit" class="btn btn-primary" v-bind:disabled="disabled">戻る</button>
                    </form>

                    <table class="table table-hover" id="pitcher-summary">
                        <tr>
                            <th></th>
                            <th></th>
                            <th>回</th>
                            <th>安</th>
                            <th>本</th>
                            <th>振</th>
                            <th>四</th>
                            <th>死</th>
                            <th>自</th>
                            <th>防</th>
                        </tr>
                        <tr v-for="pitcher in summary">
                            <td>
                                <span v-if="pitcher.win_flag">〇</span>
                                <span v-else-if="pitcher.lose_flag">●</span>
                                <span v-else-if="pitcher.hold_flag">H</span>
                                <span v-else-if="pitcher.save_flag">S</span>
                            </td>
                            <td>{{ pitcher.player.name }}</td>
                            <td>{{ pitcher.string_inning }}</td>
                            <td>{{ pitcher.hit }}</td>
                            <td>{{ pitcher.hr }}</td>
                            <td>{{ pitcher.sansin }}</td>
                            <td>{{ pitcher.walk }}</td>
                            <td>{{ pitcher.dead }}</td>
                            <td>{{ pitcher.jiseki }}</td>
                            <td>{{ pitcher.seiseki.era }}</td>
                        </tr>
                    </table>

                </div>

            </div>
            <div class="col-sm-3 clearfix">
                <table class="table table-hover stamen">
                    <tr v-for="(member, dajun) in this.playData.member.home_team">
                        <td v-bind:class="{'member_selected' : member.player.id == playData.now_player_id}">{{ member.position.text }}</td>
                        <td>{{ member.player.name_short }}</td>
                        <td class="seiseki">
                            {{ member.seiseki.dageki }}
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
    import EnumsMixin from '../../mixins/enums.js';
    export default {
        props: {
            gameId: String,
            type: String
        },
        mixins : [EnumsMixin],
        computed: {
          gameBackSubmitPath() {
            return '/api/games/back-game/' + this.gameId;
          }
        },
        data: function () {
            return {
                gameData: {
                    'home_team' : {},
                    'visitor_team' : {},
                    'season_id' : '',
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
                summary: {},
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
            }
        },
        methods: {
            initial() {
                axios.get('/api/games/view/' + this.gameId)
                    .then((res) => {
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
                        axios.get('/api/games/pitcher-summary/' + this.gameId + '/' + this.type)
                            .then((res) => {
                                this.summary = res.data;
                                console.log(this.summary);
                            });
                    });
            },
            submit(postPath) {
                axios.post(postPath)
                    .then((res) => {
                        // 戻る
                        this.$router.push({name: 'game.play', params: {gameId: this.gameId.toString() }});
                    });
            }
        },
        mounted() {
            this.initial();
        }
    }
</script>
