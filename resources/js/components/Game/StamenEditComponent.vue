<template>
    <div class="container">
        <h2>{{ gameData.date }} {{ gameData.home_team.name }} VS {{ gameData.visitor_team.name }}</h2>
        <h3 v-if="stamenType=='visitor'">{{ gameData.visitor_team.name }} スタメン設定</h3>
        <h3 v-if="stamenType=='home'">{{ gameData.home_team.name }} スタメン設定</h3>

        <div class="row">
            <div class="col-sm-4">
                <table class="table table-hover">
                    <tr v-for="(stamen, dajun) in this.stamenData.stamen">
                        <td>{{ stamen.dajun }}</td>
                        <td v-on:click="positionClick" :data-key="dajun" v-bind:class="{'selected' : positionClicked==dajun}">{{ stamen.position.text }}</td>
                        <td v-on:click="playerClick" :data-key="dajun" v-bind:class="{'selected' : playerClicked==dajun}">{{ stamen.player.name }}</td>
                    </tr>
                </table>
                <form v-on:submit.prevent="submit(submitPath, {name: 'game.view', params: {gameId: gameId.toString() }})">
                    <button type="submit" class="btn btn-primary">Submit</button>

                    <router-link v-bind:to="{name: 'game.view', params: {gameId: gameId.toString() }}">
                        <button class="btn btn-success float-right">一覧に戻る</button>
                    </router-link>
                </form>

            </div>
            <div class="col-sm-1">
            </div>
            <div class="col-sm-3 hikae_waku">
                <table class="table table-hover">
                    <tr v-for="(hikae, playerKey) in this.stamenData.hikae">
                        <td v-on:click="playerClick" :data-key="playerKey" v-bind:class="{'selected' : playerClicked==playerKey}">{{ hikae.name }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        props: {
            gameId: String,
            stamenType: String
        },
        computed: {
          submitPath() {
            return '/api/games/stamen-edit/' + this.gameId + '/' + this.stamenType;
          }
        },
        data: function () {
            return {
                gameData: {
                    'home_team' : {
                        'name' : ''
                    },
                    'visitor_team' : {
                        'name' : ''
                    },
                },
                stamenData: {},
                playerClicked: null,
                positionClicked: null
            }
        },
        methods: {
            getData(getPath) {
                axios.get(getPath)
                    .then((res) => {
                        this.gameData = res.data;
                    });
            },
            getStamenData(getPath) {
                axios.get(getPath)
                    .then((res) => {
                        this.stamenData = res.data;
                        console.log(this.stamenData);
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
                        let basePlayer = this.getPlayerInfo(this.playerClicked);
                        let movePlayer = this.getPlayerInfo(clickKey);

                        this.setPlayerInfo(basePlayer, clickKey);
                        this.setPlayerInfo(movePlayer, this.playerClicked);

                        // positionの切り替えも同時に行う
                        if (clickKey <= 10 && this.playerClicked <= 10) {
                            let basePosition = this.stamenData.stamen[this.playerClicked].position;
                            let movePosition = this.stamenData.stamen[clickKey].position;

                            this.stamenData.stamen[clickKey].position = basePosition;
                            this.stamenData.stamen[this.playerClicked].position = movePosition;
                        }

                        this.playerClicked = null;
                    }
                }
            },
            getPlayerInfo(key) {
                // playerClickで使用
                if (key <= 10) {
                    //stamenから
                    return this.stamenData.stamen[key].player;
                } else {
                    // hikaeから
                    return this.stamenData.hikae[key];
                }
            },
            setPlayerInfo(playerInfo, key) {
                if (key <= 10) {
                    //stamenに
                    this.stamenData.stamen[key].player = playerInfo;
                } else {
                    // hikaeに
                    this.stamenData.hikae[key] = playerInfo;
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
                return this.stamenData.stamen[key].position;
            },
            setPositionInfo(positionInfo, key) {
                //stamenに
                this.stamenData.stamen[key].position = positionInfo;
            },
            submit(postPath, redirectRoute) {
                axios.post(postPath, this.stamenData)
                    .then((res) => {
                        this.$router.push(redirectRoute);
                    });
            }
        },
        mounted() {
            this.getData('/api/games/view/' + this.gameId);

            this.getStamenData('/api/games/get-stamen-initial-data/' + this.gameId + '/' + this.stamenType);
        }
    }
</script>
