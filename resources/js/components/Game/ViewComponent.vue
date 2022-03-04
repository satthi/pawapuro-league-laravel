<template>
    <div class="container" v-if="Object.keys(enums).length">
        <h2>{{ data.date }} {{ data.home_team.name }} VS {{ data.visitor_team.name }} DH: {{ data.dh_flag ? '有' : '無' }}</h2>
        <router-link v-bind:to="{name: 'season.view', params: {seasonId: data.season_id.toString() }}">
            <button class="btn btn-success">シーズン詳細</button>
        </router-link>
        <router-link v-bind:to="{name: 'game.index', params: {seasonId: data.season_id.toString() }}">
            <button class="btn btn-success">日程一覧</button>
        </router-link>
        <router-link v-bind:to="{name: 'game.play', params: {gameId: gameId.toString() }}" v-if="data.board_status > enums.GameBoardStatus.STATUS_STAMEN_SETTING.value">
            <button class="btn btn-success" style="margin-right:50px;">試合へ</button>
        </router-link>
        <router-link v-if="data.prev_game_id" v-bind:to="{name: 'game.view', params: {gameId: data.prev_game_id.toString() }}">
            <button class="btn btn-success">前のゲーム</button>
        </router-link>
        <router-link v-if="data.next_game_id" v-bind:to="{name: 'game.view', params: {gameId: data.next_game_id.toString() }}">
            <button class="btn btn-success">次のゲーム</button>
        </router-link>
        <h4>
        予告先発
        <router-link v-bind:to="{name: 'game.probable-pitcher-edit', params: {gameId: gameId.toString() }}" v-if="data.board_status <= enums.GameBoardStatus.STATUS_START.value">
            <button class="btn btn-success">設定</button>
        </router-link>
        </h4>
        <div class="row">
            <div class="col-sm-6">
                予告先発： {{ data.home_probable_pitcher ? data.home_probable_pitcher.name : '未設定' }}
            </div>
            <div class="col-sm-6">
                <!-- {{ data }}-->
                予告先発： {{ data.visitor_probable_pitcher ? data.visitor_probable_pitcher.name : '未設定' }}
            </div>
        </div>
        <hr />
        <h4>スタメン</h4>
        <div class="row">
            <div class="col-sm-6">

                {{ data.home_team.name }}<br />
                <router-link v-bind:to="{name: 'game.stamen-edit', params: {gameId: gameId.toString(), stamenType: 'home' }}" v-if="data.board_status <= enums.GameBoardStatus.STATUS_START.value">
                    <button class="btn btn-success">設定</button>
                </router-link>
                <table class="table table-hover stamen_table">
                    <tr v-for="(stamen, dajun) in this.stamen.home_team.stamen">
                        <td class="stamen_dajun">{{ stamen.dajun }}</td>
                        <td class="stamen_position">{{ stamen.position.text }}</td>
                        <td class="stamen_name_td">{{ stamen.player.name }}</td>
                        <td class="stamen_seiseki_td">
                            <div v-if="stamen.position.value == enums.Position.POSITION_P.value">
                                {{ stamen.seiseki.pitcher }}
                            </div>
                            <div v-else>
                                {{ stamen.seiseki.dageki }}
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="col-sm-6">
                {{ data.visitor_team.name }}<br />
                <router-link v-bind:to="{name: 'game.stamen-edit', params: {gameId: gameId.toString(), stamenType: 'visitor' }}" v-if="data.board_status <= enums.GameBoardStatus.STATUS_START.value">
                    <button class="btn btn-success">設定</button>
                </router-link>

                <table class="table table-hover stamen_table">
                    <tr v-for="(stamen, dajun) in this.stamen.visitor_team.stamen">
                        <td class="stamen_dajun">{{ stamen.dajun }}</td>
                        <td class="stamen_position">{{ stamen.position.text }}</td>
                        <td class="stamen_name_td">{{ stamen.player.name }}</td>
                        <td class="stamen_seiseki_td">
                            <div v-if="stamen.position.value == enums.Position.POSITION_P.value">
                                {{ stamen.seiseki.pitcher }}
                            </div>
                            <div v-else>
                                {{ stamen.seiseki.dageki }}
                            </div>
                        </td>
                    </tr>
                </table>

            </div>
        </div>
        <div class="col-sm-6"></div>
    </div>
</template>

<script>
    import EnumsMixin from '../../mixins/enums.js';
    export default {
        props: {
            gameId: String
        },
        watch: {
            '$route' (to, from) {
                this.initial();
            }
        },
        mixins : [EnumsMixin],
        data: function () {
            return {
                data: {
                    'home_team' : {
                        'name' : ''
                    },
                    'visitor_team' : {
                        'name' : ''
                    },
                    'season_id' : '',
                },
                'stamen' : {
                    'home_team' : {},
                    'visitor_team' : {}
                }
            }
        },
        methods: {
            initial() {
                this.getData('/api/games/view/' + this.gameId);
                this.getStamenData('/api/games/get-stamen/' + this.gameId);
            },
            getData(getPath) {
                axios.get(getPath)
                    .then((res) => {
                        this.data = res.data;
                    });
            },
            getStamenData(getPath) {
                axios.get(getPath)
                    .then((res) => {
                        this.stamen = res.data;
                        console.log(this.stamen)
                    });
            },
        },
        mounted() {
            this.initial();
        }
    }
</script>