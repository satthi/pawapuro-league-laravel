<template>
    <div class="container" id="play_wrap" v-if="Object.keys(enums).length">
        <h2>{{ gameData.date }} {{ gameData.home_team.name }} VS {{ gameData.visitor_team.name }}</h2>
        <h3>{{ teamType == 'home' ? gameData.home_team.name : gameData.visitor_team.name }} 守備変更設定</h3>
        <div class="row">
            <div class="col-sm-3">
                <table class="table table-hover stamen">
                    <tr v-for="(member, dajun) in memberData">
                        <td v-on:click="positionClick" :data-key="dajun" v-bind:class="{'member_selected' : positionClicked==dajun}">{{ member.base_position.text }}</td>
                        <td v-on:click="playerClick" :data-key="dajun" v-bind:class="{'member_selected' : playerClicked==dajun}">{{ member.player.name }}</td>
                    </tr>
                </table>

                <form v-on:submit.prevent="submit(submitPath, {name: 'game.play', params: {gameId: gameId.toString() }})">
                    <button type="submit" class="btn btn-primary">守備変更登録</button>

                    <router-link v-bind:to="{name: 'game.play', params: {gameId: gameId.toString() }}">
                        <button class="btn btn-success float-right">一覧に戻る</button>
                    </router-link>
                </form>

            </div>
            <div class="col-sm-1">
            </div>
            <div class="col-sm-3 hikae_waku">
                <table class="table table-hover">
                    <tr v-for="(hikae, playerKey) in hikaeMemberData">
                        <td v-on:click="playerClick" :data-key="playerKey" v-bind:class="{'selected' : playerClicked==playerKey}">{{ hikae.name }}</td>
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
            teamType: String,
        },
        mixins : [EnumsMixin],
        computed: {
          submitPath() {
            return '/api/games/save-position-change/' + this.gameId + '/' + this.teamType;
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
                data: {},
                playerClicked: null,
                positionClicked: null,
                memberData: {},
                hikaeMemberData: {},
            }
        },
        methods: {
            initial() {
                axios.get('/api/games/view/' + this.gameId)
                    .then((res) => {
                        this.gameData = res.data;
                    });
                axios.get('/api/games/get-play/' + this.gameId)
                    .then((res) => {
                        this.playData = res.data;

                        if (this.teamType == 'home') {
                            this.memberData = this.playData.member.home_team;
                            this.hikaeMemberData = this.playData.member.home_team_hikae;
                        } else if (this.teamType == 'visitor') {
                            this.memberData = this.playData.member.visitor_team;
                            this.hikaeMemberData = this.playData.member.visitor_team_hikae;
                        } else {
                            // error/
                        }

                    });
            },
            // playerClick
            playerClick(event) {
                // player選択中を外す
                this.positionClicked = null;

                let clickKey = event.target.dataset.key;
                if (this.playerClicked == null) {
                    this.playerClicked = clickKey;
                } else {
                    if (clickKey <= 10 && this.playerClicked == 10 || clickKey == 10 && this.playerClicked <= 10) {
                        // スタメンメンバーとピッチャーは交換できない
                        alert('変更できません')
                        this.playerClicked = null;
                    } else {
                        // 選手を入れ替え
                        // positionの切り替えも同時に行う
                        if (clickKey <= 10 && this.playerClicked <= 10) {
                            // メンバー同士の選手交代はしない
                            this.playerClicked = null;
                        } else {

                            let basePlayer = this.getPlayerInfo(this.playerClicked);
                            let movePlayer = this.getPlayerInfo(clickKey);

                            this.setPlayerInfo(basePlayer, clickKey);
                            this.setPlayerInfo(movePlayer, this.playerClicked);

                            this.playerClicked = null;
                        }
                    }
                }
            },
            getPlayerInfo(key) {
                // playerClickで使用
                if (key <= 10) {
                    //memberから
                    return this.memberData[key].player;
                } else {
                    // hikaeから
                    return this.hikaeMemberData[key];
                }
            },
            setPlayerInfo(playerInfo, key) {
                if (key <= 10) {
                    //memberに
                    this.memberData[key].player = playerInfo;
                } else {
                    // hikaeに
                    this.hikaeMemberData[key] = playerInfo;
                }
            },
            // positionClick
            positionClick(event) {
                // player選択中を外す
                this.playerClicked = null;

                let clickKey = event.target.dataset.key;
                if (this.positionClicked == null) {
                    this.positionClicked = clickKey;
                } else {
                    // 選手を入れ替え
                    let basePosition = this.getPositionInfo(this.positionClicked);
                    let movePosition = this.getPositionInfo(clickKey);

                    this.setPositionInfo(basePosition, clickKey);
                    this.setPositionInfo(movePosition, this.positionClicked);

                    this.positionClicked = null;
                }
            },
            getPositionInfo(key) {
                //stamenから
                return this.memberData[key].base_position;
            },
            setPositionInfo(positionInfo, key) {
                //stamenに
                this.memberData[key].base_position = positionInfo;
            },
            submit(postPath, redirectRoute) {
                axios.post(postPath, this.memberData)
                    .then((res) => {
                        this.$router.push(redirectRoute);
                    });
            }
        },
        mounted() {

            this.initial();
        }
    }
</script>


