<template>
    <div class="container" id="play_wrap" v-if="Object.keys(enums).length">
        <h2>{{ gameData.date }} {{ gameData.home_team.name }} VS {{ gameData.visitor_team.name }}</h2>
        <h3>{{ teamType == 'home' ? gameData.home_team.name : gameData.visitor_team.name }} 代打設定</h3>
        <div class="row">
            <div class="col-sm-3">
                <table class="table table-hover stamen" v-if="teamType == 'visitor'">
                    <tr v-for="(member, dajun) in this.playData.member.visitor_team">
                        <td v-bind:class="{'member_selected' : member.player.id == playData.now_player_id}">{{ member.position.text }}</td>
                        <td>{{ member.player.name_short }}</td>
                    </tr>
                </table>
                <table class="table table-hover stamen" v-if="teamType == 'home'">
                    <tr v-for="(member, dajun) in this.playData.member.visitor_team">
                        <td v-bind:class="{'member_selected' : member.player.id == playData.now_player_id}">{{ member.position.text }}</td>
                        <td>{{ member.player.name_short }}</td>
                    </tr>
                </table>

                <form v-on:submit.prevent="submit(submitPath, {name: 'game.play', params: {gameId: gameId.toString() }})">
                    <button type="submit" class="btn btn-primary">代打登録</button>

                    <router-link v-bind:to="{name: 'game.play', params: {gameId: gameId.toString() }}">
                        <button class="btn btn-success float-right">一覧に戻る</button>
                    </router-link>
                </form>

            </div>
            <div class="col-sm-1">
            </div>
            <div class="col-sm-3 hikae_waku">
                <table class="table table-hover" v-if="teamType == 'visitor'">
                    <tr v-for="(hikae, playerKey) in this.playData.member.visitor_team_hikae">
                        <td v-on:click="playerClick" :data-key="hikae.id" v-bind:class="{'selected' : playerClicked==hikae.id}">{{ hikae.name }}</td>
                    </tr>
                </table>
                <table class="table table-hover" v-if="teamType == 'home'">
                    <tr v-for="(hikae, playerKey) in this.playData.member.home_team_hikae">
                        <td v-on:click="playerClick" :data-key="hikae.id" v-bind:class="{'selected' : playerClicked==hikae.id}">{{ hikae.name }}</td>
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
            return '/api/games/save-pinch-hitter/' + this.gameId + '/' + this.teamType;
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
            }
        },
        methods: {
            initial() {
                axios.get('/api/games/view/' + this.gameId)
                    .then((res) => {
                        this.gameData = res.data;
                        console.log(this.gameData);
                    });
                axios.get('/api/games/get-play/' + this.gameId)
                    .then((res) => {
                        this.playData = res.data;
                    });
            },
            // playerClick
            playerClick(event) {
                // player選択中を外す
                let clickKey = event.target.dataset.key;
                this.playerClicked = clickKey;
                this.data.pinch_hitter_id = clickKey;
            },

            submit(postPath, redirectRoute) {
                var postData = this.data;
                axios.post(postPath, this.data)
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


