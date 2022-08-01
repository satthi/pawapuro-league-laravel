<template>
    <div class="container" v-if="Object.keys(data).length">
        <h2>{{  data.season.name }} タイトル</h2>
        <div class="clearfix">
            <router-link ref="hoge" v-bind:to="{name: 'season.view', params: {seasonId: seasonId.toString() }}">
                <button class="btn btn-success">詳細</button>
            </router-link>
        </div>
        <div class="clearfix">
            <span v-for="season in seasons">
                <router-link ref="hoge" v-bind:to="{name: 'season.title', params: {seasonId: season.id.toString() }}">
                    <button class="btn btn-success">{{ season.name }}</button>&nbsp;
                </router-link>
            </span>
        </div>

        <table class="table table-hover">
            <tr>
                <th style="width: 200px;">MVP</th>
                <td style="width: 100px;"></td>
                <td></td>
            </tr>
            <tr>
                <th>首位打者</th>
                <td>{{ title.avg.value }}</td>
                <td>
                    <div v-for="player in title.avg.players">
                        <router-link v-bind:to="{name: 'player.view', params: {playerId: player.player_id.toString() }}">
                            {{ player.team_name }} {{ player.player_no }} : {{ player.player_name }}
                        </router-link>
                    </div>
                </td>
            </tr>
            <tr>
                <th>HR王</th>
                <td>{{ title.hr.value }}本</td>
                <td>
                    <div v-for="player in title.hr.players">
                        <router-link v-bind:to="{name: 'player.view', params: {playerId: player.player_id.toString() }}">
                            {{ player.team_name }} {{ player.player_no }} : {{ player.player_name }}
                        </router-link>
                    </div>
                </td>
            </tr>
            <tr>
                <th>打点王</th>
                <td>{{ title.daten.value }}点</td>
                <td>
                    <div v-for="player in title.daten.players">
                        <router-link v-bind:to="{name: 'player.view', params: {playerId: player.player_id.toString() }}">
                            {{ player.team_name }} {{ player.player_no }} : {{ player.player_name }}
                        </router-link>
                    </div>
                </td>
            </tr>
            <tr>
                <th>最多安打</th>
                <td>{{ title.hit.value }}本</td>
                <td>
                    <div v-for="player in title.hit.players">
                        <router-link v-bind:to="{name: 'player.view', params: {playerId: player.player_id.toString() }}">
                            {{ player.team_name }} {{ player.player_no }} : {{ player.player_name }}
                        </router-link>
                    </div>
                </td>
            </tr>
            <tr>
                <th>盗塁王</th>
                <td>{{ title.steal.value }}個</td>
                <td>
                    <div v-for="player in title.steal.players">
                        <router-link v-bind:to="{name: 'player.view', params: {playerId: player.player_id.toString() }}">
                            {{ player.team_name }} {{ player.player_no }} : {{ player.player_name }}
                        </router-link>
                    </div>
                </td>
            </tr>
            <tr>
                <th>最優秀防御率</th>
                <td>{{ title.p_era.value }}</td>
                <td>
                    <div v-for="player in title.p_era.players">
                        <router-link v-bind:to="{name: 'player.view', params: {playerId: player.player_id.toString() }}">
                            {{ player.team_name }} {{ player.player_no }} : {{ player.player_name }}
                        </router-link>
                    </div>
                </td>
            </tr>
            <tr>
                <th>最多勝</th>
                <td>{{ title.p_win.value }}勝</td>
                <td>
                    <div v-for="player in title.p_win.players">
                        <router-link v-bind:to="{name: 'player.view', params: {playerId: player.player_id.toString() }}">
                            {{ player.team_name }} {{ player.player_no }} : {{ player.player_name }}
                        </router-link>
                    </div>
                </td>
            </tr>
            <tr>
                <th>最高勝率</th>
                <td>{{ title.p_win_ratio.value }}</td>
                <td>
                    <div v-for="player in title.p_win_ratio.players">
                        <router-link v-bind:to="{name: 'player.view', params: {playerId: player.player_id.toString() }}">
                            {{ player.team_name }} {{ player.player_no }} : {{ player.player_name }}
                        </router-link>
                    </div>
                </td>
            </tr>
            <tr>
                <th>最多奪三振</th>
                <td>{{ title.p_sansin.value }}個</td>
                <td>
                    <div v-for="player in title.p_sansin.players">
                        <router-link v-bind:to="{name: 'player.view', params: {playerId: player.player_id.toString() }}">
                            {{ player.team_name }} {{ player.player_no }} : {{ player.player_name }}
                        </router-link>
                    </div>
                </td>
            </tr>
            <tr>
                <th>最優秀中継ぎ投手</th>
                <td>{{ title.p_hold.value }}ホールド</td>
                <td>
                    <div v-for="player in title.p_hold.players">
                        <router-link v-bind:to="{name: 'player.view', params: {playerId: player.player_id.toString() }}">
                            {{ player.team_name }} {{ player.player_no }} : {{ player.player_name }}
                        </router-link>
                    </div>
                </td>
            </tr>
            <tr>
                <th>最優秀救援投手</th>
                <td>{{ title.p_save.value }}セーブ</td>
                <td>
                    <div v-for="player in title.p_save.players">
                        <router-link v-bind:to="{name: 'player.view', params: {playerId: player.player_id.toString() }}">
                            {{ player.team_name }} {{ player.player_no }} : {{ player.player_name }}
                        </router-link>
                    </div>
                </td>
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
                this.getTitleData('/api/seasons/title/' + this.seasonId);
                this.getSeasons('/api/seasons/regularList');
            },
            getData(getPath) {
                axios.get(getPath)
                    .then((res) => {
                        this.data = res.data;
                    });
            },
            getTitleData(getPath) {
                axios.get(getPath)
                    .then((res) => {
                        this.title = res.data;
                    });
            },
            getSeasons(getPath) {
                axios.get(getPath)
                    .then((res) => {
                        this.seasons = res.data;
                    });
            },
        },
        props: {
            seasonId: String
        },
        data: function () {
            return {
                data: {},
                title: {},
                seasons: {}
            }
        },
        mounted() {
            this.initial();
        }
    }
</script>
