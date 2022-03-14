<template>
    <div class="container" v-if="Object.keys(data).length">
        <h2>{{  data.team.season.name }} {{  data.team.name }} ({{ month }})</h2>
        <div class="clearfix">
            <router-link v-bind:to="{name: 'season.view', params: {seasonId: data.team.season_id.toString() }}">
                <button class="btn btn-success">シーズン詳細</button>
            </router-link>
            <router-link v-bind:to="{name: 'team.view', params: {teamId: data.team.id.toString() }}">
                <button class="btn btn-success">個人成績</button>
            </router-link>
            <router-link v-for="(monthListParts, index) in monthList" :key="index" v-bind:to="{name: 'team.month', params: {teamId: data.team.id.toString(), month: monthListParts.month }}">
                <button class="btn btn-success">{{ monthListParts.month }}</button>&nbsp;
            </router-link>
        </div>
        <table class="table table-hover">
            <tr>
                <th style="width:14%;">月</th>
                <th style="width:14%;">火</th>
                <th style="width:14%;">水</th>
                <th style="width:14%;">木</th>
                <th style="width:14%;">金</th>
                <th style="width:14%;">土</th>
                <th style="width:14%;">日</th>
            </tr>
            <tr v-for="monthInfoRaw in monthInfo">
                <td v-if="monthInfoRaw[1] != undefined && monthInfoRaw[1].date">
                    {{ monthInfoRaw[1].date }}
                    <span v-if="monthInfoRaw[1].game">
                        <span v-if="monthInfoRaw[1].game.visitor_team_id == data.team.id">
                            <router-link v-bind:to="{name: 'game.view', params: {gameId: monthInfoRaw[1].game.id.toString() }}">
                                <button class="btn btn-success">vs {{ monthInfoRaw[1].game.visitor_team.ryaku_name }}</button>&nbsp;
                            </router-link>

                            <span v-if="monthInfoRaw[1].game.inning == 999">
                                <span v-if="monthInfoRaw[1].game.visitor_point > monthInfoRaw[1].game.home_point" style="font-size:20px;">○</span>
                                <span v-if="monthInfoRaw[1].game.visitor_point < monthInfoRaw[1].game.home_point" style="font-size:20px;">●</span>
                                <span v-if="monthInfoRaw[1].game.visitor_point == monthInfoRaw[1].game.home_point" style="font-size:20px;">△</span>
                                <br />
                                {{ monthInfoRaw[1].game.visitor_point }} - {{ monthInfoRaw[1].game.home_point }}

                                <span v-if="monthInfoRaw[1].win">
                                <br />勝: {{ monthInfoRaw[1].win.name_short }}
                                </span>
                                <span v-if="monthInfoRaw[1].lose">
                                <br />敗: {{ monthInfoRaw[1].lose.name_short }}
                                </span>
                                <span v-if="monthInfoRaw[1].save">
                                <br />S: {{ monthInfoRaw[1].save.name_short }}
                                </span>
                            </span>
                        </span>
                        <span v-if="monthInfoRaw[1].game.home_team_id == data.team.id">
                            <router-link v-bind:to="{name: 'game.view', params: {gameId: monthInfoRaw[1].game.id.toString() }}">
                                <button class="btn btn-success">vs {{ monthInfoRaw[1].game.home_team.ryaku_name }}</button>&nbsp;
                            </router-link>

                            <span v-if="monthInfoRaw[1].game.inning == 999">
                                <span v-if="monthInfoRaw[1].game.home_point > monthInfoRaw[1].game.visitor_point" style="font-size:20px;">○</span>
                                <span v-if="monthInfoRaw[1].game.home_point < monthInfoRaw[1].game.visitor_point" style="font-size:20px;">●</span>
                                <span v-if="monthInfoRaw[1].game.home_point == monthInfoRaw[1].game.visitor_point" style="font-size:20px;">△</span>
                                <br />
                                {{ monthInfoRaw[1].game.home_point }} - {{ monthInfoRaw[1].game.visitor_point }}

                                <span v-if="monthInfoRaw[1].win">
                                <br />勝: {{ monthInfoRaw[1].win.name_short }}
                                </span>
                                <span v-if="monthInfoRaw[1].lose">
                                <br />敗: {{ monthInfoRaw[1].lose.name_short }}
                                </span>
                                <span v-if="monthInfoRaw[1].save">
                                <br />S: {{ monthInfoRaw[1].save.name_short }}
                                </span>
                            </span>
                        </span>
                    </span>
                </td>
                <td v-else></td>
                <td v-if="monthInfoRaw[2] != undefined && monthInfoRaw[2].date">
                    {{ monthInfoRaw[2].date }}
                    <span v-if="monthInfoRaw[2].game">
                        <span v-if="monthInfoRaw[2].game.visitor_team_id == data.team.id">
                            <router-link v-bind:to="{name: 'game.view', params: {gameId: monthInfoRaw[2].game.id.toString() }}">
                                <button class="btn btn-success">vs {{ monthInfoRaw[2].game.visitor_team.ryaku_name }}</button>&nbsp;
                            </router-link>

                            <span v-if="monthInfoRaw[2].game.inning == 999">
                                <span v-if="monthInfoRaw[2].game.visitor_point > monthInfoRaw[2].game.home_point" style="font-size:20px;">○</span>
                                <span v-if="monthInfoRaw[2].game.visitor_point < monthInfoRaw[2].game.home_point" style="font-size:20px;">●</span>
                                <span v-if="monthInfoRaw[2].game.visitor_point == monthInfoRaw[2].game.home_point" style="font-size:20px;">△</span>
                                <br />
                                {{ monthInfoRaw[2].game.visitor_point }} - {{ monthInfoRaw[2].game.home_point }}

                                <span v-if="monthInfoRaw[2].win">
                                <br />勝: {{ monthInfoRaw[2].win.name_short }}
                                </span>
                                <span v-if="monthInfoRaw[2].lose">
                                <br />敗: {{ monthInfoRaw[2].lose.name_short }}
                                </span>
                                <span v-if="monthInfoRaw[2].save">
                                <br />S: {{ monthInfoRaw[2].save.name_short }}
                                </span>
                            </span>
                        </span>
                        <span v-if="monthInfoRaw[2].game.home_team_id == data.team.id">
                            <router-link v-bind:to="{name: 'game.view', params: {gameId: monthInfoRaw[2].game.id.toString() }}">
                                <button class="btn btn-success">vs {{ monthInfoRaw[2].game.home_team.ryaku_name }}</button>&nbsp;
                            </router-link>

                            <span v-if="monthInfoRaw[2].game.inning == 999">
                                <span v-if="monthInfoRaw[2].game.home_point > monthInfoRaw[2].game.visitor_point" style="font-size:20px;">○</span>
                                <span v-if="monthInfoRaw[2].game.home_point < monthInfoRaw[2].game.visitor_point" style="font-size:20px;">●</span>
                                <span v-if="monthInfoRaw[2].game.home_point == monthInfoRaw[2].game.visitor_point" style="font-size:20px;">△</span>
                                <br />
                                {{ monthInfoRaw[2].game.home_point }} - {{ monthInfoRaw[2].game.visitor_point }}

                                <span v-if="monthInfoRaw[2].win">
                                <br />勝: {{ monthInfoRaw[2].win.name_short }}
                                </span>
                                <span v-if="monthInfoRaw[2].lose">
                                <br />敗: {{ monthInfoRaw[2].lose.name_short }}
                                </span>
                                <span v-if="monthInfoRaw[2].save">
                                <br />S: {{ monthInfoRaw[2].save.name_short }}
                                </span>
                            </span>
                        </span>
                    </span>
                </td>
                <td v-else></td>
                <td v-if="monthInfoRaw[3] != undefined && monthInfoRaw[3].date">
                    {{ monthInfoRaw[3].date }}
                    <span v-if="monthInfoRaw[3].game">
                        <span v-if="monthInfoRaw[3].game.visitor_team_id == data.team.id">
                            <router-link v-bind:to="{name: 'game.view', params: {gameId: monthInfoRaw[3].game.id.toString() }}">
                                <button class="btn btn-success">vs {{ monthInfoRaw[3].game.visitor_team.ryaku_name }}</button>&nbsp;
                            </router-link>

                            <span v-if="monthInfoRaw[3].game.inning == 999">
                                <span v-if="monthInfoRaw[3].game.visitor_point > monthInfoRaw[3].game.home_point" style="font-size:20px;">○</span>
                                <span v-if="monthInfoRaw[3].game.visitor_point < monthInfoRaw[3].game.home_point" style="font-size:20px;">●</span>
                                <span v-if="monthInfoRaw[3].game.visitor_point == monthInfoRaw[3].game.home_point" style="font-size:20px;">△</span>
                                <br />
                                {{ monthInfoRaw[3].game.visitor_point }} - {{ monthInfoRaw[3].game.home_point }}

                                <span v-if="monthInfoRaw[3].win">
                                <br />勝: {{ monthInfoRaw[3].win.name_short }}
                                </span>
                                <span v-if="monthInfoRaw[3].lose">
                                <br />敗: {{ monthInfoRaw[3].lose.name_short }}
                                </span>
                                <span v-if="monthInfoRaw[3].save">
                                <br />S: {{ monthInfoRaw[3].save.name_short }}
                                </span>
                            </span>
                        </span>
                        <span v-if="monthInfoRaw[3].game.home_team_id == data.team.id">
                            <router-link v-bind:to="{name: 'game.view', params: {gameId: monthInfoRaw[3].game.id.toString() }}">
                                <button class="btn btn-success">vs {{ monthInfoRaw[3].game.home_team.ryaku_name }}</button>&nbsp;
                            </router-link>

                            <span v-if="monthInfoRaw[3].game.inning == 999">
                                <span v-if="monthInfoRaw[3].game.home_point > monthInfoRaw[3].game.visitor_point" style="font-size:20px;">○</span>
                                <span v-if="monthInfoRaw[3].game.home_point < monthInfoRaw[3].game.visitor_point" style="font-size:20px;">●</span>
                                <span v-if="monthInfoRaw[3].game.home_point == monthInfoRaw[3].game.visitor_point" style="font-size:20px;">△</span>
                                <br />
                                {{ monthInfoRaw[3].game.home_point }} - {{ monthInfoRaw[3].game.visitor_point }}

                                <span v-if="monthInfoRaw[3].win">
                                <br />勝: {{ monthInfoRaw[3].win.name_short }}
                                </span>
                                <span v-if="monthInfoRaw[3].lose">
                                <br />敗: {{ monthInfoRaw[3].lose.name_short }}
                                </span>
                                <span v-if="monthInfoRaw[3].save">
                                <br />S: {{ monthInfoRaw[3].save.name_short }}
                                </span>
                            </span>
                        </span>
                    </span>
                </td>
                <td v-else></td>
                <td v-if="monthInfoRaw[4] != undefined && monthInfoRaw[4].date">
                    {{ monthInfoRaw[4].date }}
                    <span v-if="monthInfoRaw[4].game">
                        <span v-if="monthInfoRaw[4].game.visitor_team_id == data.team.id">
                            <router-link v-bind:to="{name: 'game.view', params: {gameId: monthInfoRaw[4].game.id.toString() }}">
                                <button class="btn btn-success">vs {{ monthInfoRaw[4].game.visitor_team.ryaku_name }}</button>&nbsp;
                            </router-link>

                            <span v-if="monthInfoRaw[4].game.inning == 999">
                                <span v-if="monthInfoRaw[4].game.visitor_point > monthInfoRaw[4].game.home_point" style="font-size:20px;">○</span>
                                <span v-if="monthInfoRaw[4].game.visitor_point < monthInfoRaw[4].game.home_point" style="font-size:20px;">●</span>
                                <span v-if="monthInfoRaw[4].game.visitor_point == monthInfoRaw[4].game.home_point" style="font-size:20px;">△</span>
                                <br />
                                {{ monthInfoRaw[4].game.visitor_point }} - {{ monthInfoRaw[4].game.home_point }}

                                <span v-if="monthInfoRaw[4].win">
                                <br />勝: {{ monthInfoRaw[4].win.name_short }}
                                </span>
                                <span v-if="monthInfoRaw[4].lose">
                                <br />敗: {{ monthInfoRaw[4].lose.name_short }}
                                </span>
                                <span v-if="monthInfoRaw[4].save">
                                <br />S: {{ monthInfoRaw[4].save.name_short }}
                                </span>
                            </span>
                        </span>
                        <span v-if="monthInfoRaw[4].game.home_team_id == data.team.id">
                            <router-link v-bind:to="{name: 'game.view', params: {gameId: monthInfoRaw[4].game.id.toString() }}">
                                <button class="btn btn-success">vs {{ monthInfoRaw[4].game.home_team.ryaku_name }}</button>&nbsp;
                            </router-link>

                            <span v-if="monthInfoRaw[4].game.inning == 999">
                                <span v-if="monthInfoRaw[4].game.home_point > monthInfoRaw[4].game.visitor_point" style="font-size:20px;">○</span>
                                <span v-if="monthInfoRaw[4].game.home_point < monthInfoRaw[4].game.visitor_point" style="font-size:20px;">●</span>
                                <span v-if="monthInfoRaw[4].game.home_point == monthInfoRaw[4].game.visitor_point" style="font-size:20px;">△</span>
                                <br />
                                {{ monthInfoRaw[4].game.home_point }} - {{ monthInfoRaw[4].game.visitor_point }}

                                <span v-if="monthInfoRaw[4].win">
                                <br />勝: {{ monthInfoRaw[4].win.name_short }}
                                </span>
                                <span v-if="monthInfoRaw[4].lose">
                                <br />敗: {{ monthInfoRaw[4].lose.name_short }}
                                </span>
                                <span v-if="monthInfoRaw[4].save">
                                <br />S: {{ monthInfoRaw[4].save.name_short }}
                                </span>
                            </span>
                        </span>
                    </span>
                </td>
                <td v-else></td>
                <td v-if="monthInfoRaw[5] != undefined && monthInfoRaw[5].date">
                    {{ monthInfoRaw[5].date }}
                    <span v-if="monthInfoRaw[5].game">
                        <span v-if="monthInfoRaw[5].game.visitor_team_id == data.team.id">
                            <router-link v-bind:to="{name: 'game.view', params: {gameId: monthInfoRaw[5].game.id.toString() }}">
                                <button class="btn btn-success">vs {{ monthInfoRaw[5].game.visitor_team.ryaku_name }}</button>&nbsp;
                            </router-link>

                            <span v-if="monthInfoRaw[5].game.inning == 999">
                                <span v-if="monthInfoRaw[5].game.visitor_point > monthInfoRaw[5].game.home_point" style="font-size:20px;">○</span>
                                <span v-if="monthInfoRaw[5].game.visitor_point < monthInfoRaw[5].game.home_point" style="font-size:20px;">●</span>
                                <span v-if="monthInfoRaw[5].game.visitor_point == monthInfoRaw[5].game.home_point" style="font-size:20px;">△</span>
                                <br />
                                {{ monthInfoRaw[5].game.visitor_point }} - {{ monthInfoRaw[5].game.home_point }}

                                <span v-if="monthInfoRaw[5].win">
                                <br />勝: {{ monthInfoRaw[5].win.name_short }}
                                </span>
                                <span v-if="monthInfoRaw[5].lose">
                                <br />敗: {{ monthInfoRaw[5].lose.name_short }}
                                </span>
                                <span v-if="monthInfoRaw[5].save">
                                <br />S: {{ monthInfoRaw[5].save.name_short }}
                                </span>
                            </span>
                        </span>
                        <span v-if="monthInfoRaw[5].game.home_team_id == data.team.id">
                            <router-link v-bind:to="{name: 'game.view', params: {gameId: monthInfoRaw[5].game.id.toString() }}">
                                <button class="btn btn-success">vs {{ monthInfoRaw[5].game.home_team.ryaku_name }}</button>&nbsp;
                            </router-link>

                            <span v-if="monthInfoRaw[5].game.inning == 999">
                                <span v-if="monthInfoRaw[5].game.home_point > monthInfoRaw[5].game.visitor_point" style="font-size:20px;">○</span>
                                <span v-if="monthInfoRaw[5].game.home_point < monthInfoRaw[5].game.visitor_point" style="font-size:20px;">●</span>
                                <span v-if="monthInfoRaw[5].game.home_point == monthInfoRaw[5].game.visitor_point" style="font-size:20px;">△</span>
                                <br />
                                {{ monthInfoRaw[5].game.home_point }} - {{ monthInfoRaw[5].game.visitor_point }}

                                <span v-if="monthInfoRaw[5].win">
                                <br />勝: {{ monthInfoRaw[5].win.name_short }}
                                </span>
                                <span v-if="monthInfoRaw[5].lose">
                                <br />敗: {{ monthInfoRaw[5].lose.name_short }}
                                </span>
                                <span v-if="monthInfoRaw[5].save">
                                <br />S: {{ monthInfoRaw[5].save.name_short }}
                                </span>
                            </span>
                        </span>
                    </span>
                </td>
                <td v-else></td>
                <td v-if="monthInfoRaw[6] != undefined && monthInfoRaw[6].date">
                    {{ monthInfoRaw[6].date }}
                    <span v-if="monthInfoRaw[6].game">
                        <span v-if="monthInfoRaw[6].game.visitor_team_id == data.team.id">
                            <router-link v-bind:to="{name: 'game.view', params: {gameId: monthInfoRaw[6].game.id.toString() }}">
                                <button class="btn btn-success">vs {{ monthInfoRaw[6].game.visitor_team.ryaku_name }}</button>&nbsp;
                            </router-link>

                            <span v-if="monthInfoRaw[6].game.inning == 999">
                                <span v-if="monthInfoRaw[6].game.visitor_point > monthInfoRaw[6].game.home_point" style="font-size:20px;">○</span>
                                <span v-if="monthInfoRaw[6].game.visitor_point < monthInfoRaw[6].game.home_point" style="font-size:20px;">●</span>
                                <span v-if="monthInfoRaw[6].game.visitor_point == monthInfoRaw[6].game.home_point" style="font-size:20px;">△</span>
                                <br />
                                {{ monthInfoRaw[6].game.visitor_point }} - {{ monthInfoRaw[6].game.home_point }}

                                <span v-if="monthInfoRaw[6].win">
                                <br />勝: {{ monthInfoRaw[6].win.name_short }}
                                </span>
                                <span v-if="monthInfoRaw[6].lose">
                                <br />敗: {{ monthInfoRaw[6].lose.name_short }}
                                </span>
                                <span v-if="monthInfoRaw[6].save">
                                <br />S: {{ monthInfoRaw[6].save.name_short }}
                                </span>
                            </span>
                        </span>
                        <span v-if="monthInfoRaw[6].game.home_team_id == data.team.id">
                            <router-link v-bind:to="{name: 'game.view', params: {gameId: monthInfoRaw[6].game.id.toString() }}">
                                <button class="btn btn-success">vs {{ monthInfoRaw[6].game.home_team.ryaku_name }}</button>&nbsp;
                            </router-link>

                            <span v-if="monthInfoRaw[6].game.inning == 999">
                                <span v-if="monthInfoRaw[6].game.home_point > monthInfoRaw[6].game.visitor_point" style="font-size:20px;">○</span>
                                <span v-if="monthInfoRaw[6].game.home_point < monthInfoRaw[6].game.visitor_point" style="font-size:20px;">●</span>
                                <span v-if="monthInfoRaw[6].game.home_point == monthInfoRaw[6].game.visitor_point" style="font-size:20px;">△</span>
                                <br />
                                {{ monthInfoRaw[6].game.home_point }} - {{ monthInfoRaw[6].game.visitor_point }}

                                <span v-if="monthInfoRaw[6].win">
                                <br />勝: {{ monthInfoRaw[6].win.name_short }}
                                </span>
                                <span v-if="monthInfoRaw[6].lose">
                                <br />敗: {{ monthInfoRaw[6].lose.name_short }}
                                </span>
                                <span v-if="monthInfoRaw[6].save">
                                <br />S: {{ monthInfoRaw[6].save.name_short }}
                                </span>
                            </span>
                        </span>
                    </span>
                </td>
                <td v-else></td>
                <td v-if="monthInfoRaw[0] != undefined && monthInfoRaw[0].date">
                    {{ monthInfoRaw[0].date }}
                    <span v-if="monthInfoRaw[0].game">
                        <span v-if="monthInfoRaw[0].game.visitor_team_id == data.team.id">
                            <router-link v-bind:to="{name: 'game.view', params: {gameId: monthInfoRaw[0].game.id.toString() }}">
                                <button class="btn btn-success">vs {{ monthInfoRaw[0].game.visitor_team.ryaku_name }}</button>&nbsp;
                            </router-link>

                            <span v-if="monthInfoRaw[0].game.inning == 999">
                                <span v-if="monthInfoRaw[0].game.visitor_point > monthInfoRaw[0].game.home_point" style="font-size:20px;">○</span>
                                <span v-if="monthInfoRaw[0].game.visitor_point < monthInfoRaw[0].game.home_point" style="font-size:20px;">●</span>
                                <span v-if="monthInfoRaw[0].game.visitor_point == monthInfoRaw[0].game.home_point" style="font-size:20px;">△</span>
                                <br />
                                {{ monthInfoRaw[0].game.visitor_point }} - {{ monthInfoRaw[0].game.home_point }}

                                <span v-if="monthInfoRaw[0].win">
                                <br />勝: {{ monthInfoRaw[0].win.name_short }}
                                </span>
                                <span v-if="monthInfoRaw[0].lose">
                                <br />敗: {{ monthInfoRaw[0].lose.name_short }}
                                </span>
                                <span v-if="monthInfoRaw[0].save">
                                <br />S: {{ monthInfoRaw[0].save.name_short }}
                                </span>
                            </span>
                        </span>
                        <span v-if="monthInfoRaw[0].game.home_team_id == data.team.id">
                            <router-link v-bind:to="{name: 'game.view', params: {gameId: monthInfoRaw[0].game.id.toString() }}">
                                <button class="btn btn-success">vs {{ monthInfoRaw[0].game.home_team.ryaku_name }}</button>&nbsp;
                            </router-link>

                            <span v-if="monthInfoRaw[0].game.inning == 999">
                                <span v-if="monthInfoRaw[0].game.home_point > monthInfoRaw[0].game.visitor_point" style="font-size:20px;">○</span>
                                <span v-if="monthInfoRaw[0].game.home_point < monthInfoRaw[0].game.visitor_point" style="font-size:20px;">●</span>
                                <span v-if="monthInfoRaw[0].game.home_point == monthInfoRaw[0].game.visitor_point" style="font-size:20px;">△</span>
                                <br />
                                {{ monthInfoRaw[0].game.home_point }} - {{ monthInfoRaw[0].game.visitor_point }}

                                <span v-if="monthInfoRaw[0].win">
                                <br />勝: {{ monthInfoRaw[0].win.name_short }}
                                </span>
                                <span v-if="monthInfoRaw[0].lose">
                                <br />敗: {{ monthInfoRaw[0].lose.name_short }}
                                </span>
                                <span v-if="monthInfoRaw[0].save">
                                <br />S: {{ monthInfoRaw[0].save.name_short }}
                                </span>
                            </span>
                        </span>
                    </span>
                </td>
                <td v-else></td>
            </tr>
        </table>

    </div>
</template>
<script>
    export default {
        props: {
            teamId: String,
            month: String
        },
        watch: {
            '$route' (to, from) {
                this.initial();
            }
        },
        methods: {
            initial() {
            console.log(this.month)
                // チーム情報など込みで詳細画面表示に必要な情報をまとめて取得（したい）
                this.getData('/api/teams/view/' + this.teamId);
                this.getMonthList('/api/teams/get-month-list/' + this.teamId);
                this.getMonthInfo('/api/teams/get-month-info/' + this.teamId + '/' + this.month);
            },
            getData(getPath) {
                axios.get(getPath)
                    .then((res) => {
                        this.data = res.data;
                    });
            },
            getMonthList(getPath) {
                axios.get(getPath)
                    .then((res) => {
                        console.log(res)
                        this.monthList = res.data;
                    });
            },
            getMonthInfo(getPath) {
                axios.get(getPath)
                    .then((res) => {
                        console.log(res)
                        this.monthInfo = res.data;
                    });
            },
        },
        data: function () {
            return {
                data: {},
                monthList: {},
                monthInfo: {}
            }
        },
        mounted() {
            this.initial();
        }
    }
</script>
