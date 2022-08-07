<template>
    <div class="container" v-if="Object.keys(data).length">
        <h2>{{  data.season.name }} タイトル</h2>
        <div class="clearfix">
            <router-link ref="hoge" v-bind:to="{name: 'season.view', params: {seasonId: seasonId.toString() }}">
                <button class="btn btn-success">詳細</button>
            </router-link>
            <router-link v-bind:to="{name: 'season.title-add', params: {seasonId: seasonId.toString() }}">
                <button class="btn btn-success">登録</button>
            </router-link>
        </div>
        <div class="clearfix">
            <span v-for="season in seasons">
                <router-link ref="hoge" v-bind:to="{name: 'season.title', params: {seasonId: season.id.toString() }}">
                    <button class="btn btn-success">{{ season.name }}</button>&nbsp;
                </router-link>
            </span>
        </div>

        <div class="row justify-content-center">
            <div class="col-sm-7">
                <table class="table table-hover">
                    <tr>
                        <th style="width: 200px;">首位打者</th>
                        <td style="width: 100px;">{{ title.avg.value }}</td>
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
            <div class="col-sm-5">
                <table class="table table-hover">
                    <tr v-if="Object.keys(title).length && title.mvp != null">
                        <th style="width: 100px;">MVP</th>
                        <td>
                            <router-link v-bind:to="{name: 'player.view', params: {playerId: title.mvp.id.toString() }}">
                                {{ title.mvp.team.ryaku_name }} {{ title.mvp.number }} : {{ title.mvp.name }}
                            </router-link>

                        </td>
                    </tr>
                    <tr>
                        <th colspan="2" style="text-align:center;">B9</th>
                    </tr>
                    <tr v-if="Object.keys(title).length && title.bb_1 != null">
                        <th style="width: 100px;">投手</th>
                        <td>
                            <router-link v-bind:to="{name: 'player.view', params: {playerId: title.bb_1.id.toString() }}">
                                {{ title.bb_1.team.ryaku_name }} {{ title.bb_1.number }} : {{ title.bb_1.name }}
                            </router-link>

                        </td>
                    </tr>
                    <tr v-if="Object.keys(title).length && title.bb_2 != null">
                        <th style="width: 100px;">捕手</th>
                        <td>
                            <router-link v-bind:to="{name: 'player.view', params: {playerId: title.bb_2.id.toString() }}">
                                {{ title.bb_2.team.ryaku_name }} {{ title.bb_2.number }} : {{ title.bb_2.name }}
                            </router-link>

                        </td>
                    </tr>
                    <tr v-if="Object.keys(title).length && title.bb_3 != null">
                        <th style="width: 100px;">一塁手</th>
                        <td>
                            <router-link v-bind:to="{name: 'player.view', params: {playerId: title.bb_3.id.toString() }}">
                                {{ title.bb_3.team.ryaku_name }} {{ title.bb_3.number }} : {{ title.bb_3.name }}
                            </router-link>

                        </td>
                    </tr>
                    <tr v-if="Object.keys(title).length && title.bb_4 != null">
                        <th style="width: 100px;">二塁手</th>
                        <td>
                            <router-link v-bind:to="{name: 'player.view', params: {playerId: title.bb_4.id.toString() }}">
                                {{ title.bb_4.team.ryaku_name }} {{ title.bb_4.number }} : {{ title.bb_4.name }}
                            </router-link>

                        </td>
                    </tr>
                    <tr v-if="Object.keys(title).length && title.bb_5 != null">
                        <th style="width: 100px;">三塁手</th>
                        <td>
                            <router-link v-bind:to="{name: 'player.view', params: {playerId: title.bb_5.id.toString() }}">
                                {{ title.bb_5.team.ryaku_name }} {{ title.bb_5.number }} : {{ title.bb_5.name }}
                            </router-link>

                        </td>
                    </tr>
                    <tr v-if="Object.keys(title).length && title.bb_6 != null">
                        <th style="width: 100px;">遊撃手</th>
                        <td>
                            <router-link v-bind:to="{name: 'player.view', params: {playerId: title.bb_6.id.toString() }}">
                                {{ title.bb_6.team.ryaku_name }} {{ title.bb_6.number }} : {{ title.bb_6.name }}
                            </router-link>

                        </td>
                    </tr>
                    <tr v-if="Object.keys(title).length && title.bb_7 != null">
                        <th style="width: 100px;" rowspan="3">外野手</th>
                        <td>
                            <router-link v-bind:to="{name: 'player.view', params: {playerId: title.bb_7.id.toString() }}">
                                {{ title.bb_7.team.ryaku_name }} {{ title.bb_7.number }} : {{ title.bb_7.name }}
                            </router-link>

                        </td>
                    </tr>
                    <tr v-if="Object.keys(title).length && title.bb_8 != null">
                        <td>
                            <router-link v-bind:to="{name: 'player.view', params: {playerId: title.bb_8.id.toString() }}">
                                {{ title.bb_8.team.ryaku_name }} {{ title.bb_8.number }} : {{ title.bb_8.name }}
                            </router-link>

                        </td>
                    </tr>
                    <tr v-if="Object.keys(title).length && title.bb_9 != null">
                        <td>
                            <router-link v-bind:to="{name: 'player.view', params: {playerId: title.bb_9.id.toString() }}">
                                {{ title.bb_9.team.ryaku_name }} {{ title.bb_9.number }} : {{ title.bb_9.name }}
                            </router-link>

                        </td>
                    </tr>
                </table>
            </div>
            -->
        </div>
        
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
