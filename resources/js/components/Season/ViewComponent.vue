<template>
    <div class="container" v-if="Object.keys(data).length">
        <h2>{{  data.season.name }}</h2>
        <div class="clearfix">
            <form v-on:submit.prevent="reShukei('/api/seasons/re-shukei/' + seasonId.toString())">
                <button type="submit" class="btn btn-success">再集計</button>
                <router-link v-bind:to="{name: 'game.index', params: {seasonId: seasonId.toString() }}">
                    <button class="btn btn-success">日程一覧</button>
                </router-link>
                <router-link v-bind:to="{name: 'season.fielder-rank', params: {seasonId: seasonId.toString(), sortType: 'avg' }}">
                    <button class="btn btn-success">野手成績</button>
                </router-link>
                <router-link v-bind:to="{name: 'season.pitcher-rank', params: {seasonId: seasonId.toString(), sortType: 'p_era' }}">
                    <button class="btn btn-success">投手成績</button>
                </router-link>
                <router-link v-if="nextGameInfo.id != null" v-bind:to="{name: 'game.view', params: {gameId: nextGameInfo.id.toString() }}">
                    <button class="btn btn-success">次の試合({{ nextGameInfo.date }} {{ nextGameInfo.home_team.name }} VS {{ nextGameInfo.visitor_team.name }})</button>
                </router-link>
                <router-link v-bind:to="{name: 'season.trade', params: {seasonId: seasonId.toString()}}">
                    <button class="btn btn-success">トレード</button>
                </router-link>
            </form>
            <div style="margin-top:10px;">
                <router-link v-for="(monthListParts, index) in data.monthList" :key="index" v-bind:to="{name: 'season.month', params: { seasonId: seasonId.toString(), month: monthListParts.month }}">
                    <button class="btn btn-success">{{ monthListParts.month }}</button>&nbsp;
                </router-link>
            </div>
        </div>

        <table class="table table-hover">
            <tr>
                <th></th>
                <th>チーム名</th>
                <th>試合</th>
                <th>勝</th>
                <th>負</th>
                <th>分</th>
                <th>勝率</th>
                <th>差</th>
                <th>残</th>
                <th>打率</th>
                <th>HR</th>
                <th>防御率</th>
                <th>得点</th>
                <th>失点</th>
                <th>優確</th>
                <th>優可</th>
                <th>自可</th>
                <th>M</th>
            </tr>
            <tr v-for="team in data.teams">
                <td>{{ team.rank }}</td>
                <td>
                    <router-link v-bind:to="{name: 'team.view', params: {teamId: team.id.toString() }}">
                        {{ team.name }}
                    </router-link>
                </td>
                <td>{{ team.game }}</td>
                <td>{{ team.win }}</td>
                <td>{{ team.lose }}</td>
                <td>{{ team.draw }}</td>
                <td>{{ team.display_win_ratio }}</td>
                <td>{{ team.diff }}</td>
                <td>{{ team.remain }}</td>
                <td>{{ team.display_avg }}</td>
                <td>{{ team.hr }}</td>
                <td>{{ team.display_era }}</td>
                <td>{{ team.point }}</td>
                <td>{{ team.p_point }}</td>
                <td>{{ team.yusho_kakutei ? '○' : '' }}</td>
                <td>{{ team.yusho_kano ? '○' : '' }}</td>
                <td>{{ team.jiriki_yusho_kano ? '○' : '' }}</td>
                <td>{{ team.yusho_kano ? team.magic_number : '-' }}</td>
            </tr>
        </table>

        <table class="table table-hover">
            <tr>
                <th></th>
                <th v-for="team in data.teams">{{ team.ryaku_name }}</th>
            </tr>
            <tr v-for="baseTeam in data.teams">
                <th>{{ baseTeam.ryaku_name }}</th>
                <td v-for="compareTeam in data.teams">
                    <span v-if="baseTeam.id == compareTeam.id">-</span>
                    <span v-else-if="data.vs[baseTeam.id] == undefined || data.vs[baseTeam.id][compareTeam.id] == undefined">0勝0敗0分</span>
                    <span v-else>{{ data.vs[baseTeam.id][compareTeam.id]['win'] }}勝{{ data.vs[baseTeam.id][compareTeam.id]['lose'] }}敗{{ data.vs[baseTeam.id][compareTeam.id]['draw'] }}分</span>
                </td>
            </tr>
        </table>


        <div class="row">
            <div class="col-sm-3">
                <h4>打率</h4>
                <table class="table table-hover">
                    <tr v-for="avgPlayer in data.ranking.avg">
                        <td>{{ avgPlayer.team.ryaku_name }}</td>
                        <td>{{ avgPlayer.name }}</td>
                        <td>{{ avgPlayer.display_avg }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-sm-3">
                <h4>HR</h4>
                <table class="table table-hover">
                    <tr v-for="hrPlayer in data.ranking.hr">
                        <td>{{ hrPlayer.team.ryaku_name }}</td>
                        <td>{{ hrPlayer.name }}</td>
                        <td>{{ hrPlayer.hr }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-sm-3">
                <h4>打点</h4>
                <table class="table table-hover">
                    <tr v-for="dateniPlayer in data.ranking.daten">
                        <td>{{ dateniPlayer.team.ryaku_name }}</td>
                        <td>{{ dateniPlayer.name }}</td>
                        <td>{{ dateniPlayer.daten }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-sm-3">
                <h4>盗塁</h4>
                <table class="table table-hover">
                    <tr v-for="stealPlayer in data.ranking.steal">
                        <td>{{ stealPlayer.team.ryaku_name }}</td>
                        <td>{{ stealPlayer.name }}</td>
                        <td>{{ stealPlayer.steal_success }}</td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-3">
                <h4>防御率</h4>
                <table class="table table-hover">
                    <tr v-for="eraPlayer in data.ranking.era">
                        <td>{{ eraPlayer.team.ryaku_name }}</td>
                        <td>{{ eraPlayer.name }}</td>
                        <td>{{ eraPlayer.display_p_era }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-sm-3">
                <h4>勝利</h4>
                <table class="table table-hover">
                    <tr v-for="winPlayer in data.ranking.win">
                        <td>{{ winPlayer.team.ryaku_name }}</td>
                        <td>{{ winPlayer.name }}</td>
                        <td>{{ winPlayer.p_win }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-sm-3">
                <h4>ホールド</h4>
                <table class="table table-hover">
                    <tr v-for="holdPlayer in data.ranking.hold">
                        <td>{{ holdPlayer.team.ryaku_name }}</td>
                        <td>{{ holdPlayer.name }}</td>
                        <td>{{ holdPlayer.p_hold }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-sm-3">
                <h4>セーブ</h4>
                <table class="table table-hover">
                    <tr v-for="savePlayer in data.ranking.save">
                        <td>{{ savePlayer.team.ryaku_name }}</td>
                        <td>{{ savePlayer.name }}</td>
                        <td>{{ savePlayer.p_save }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        methods: {
            initial() {
                // チーム情報など込みで詳細画面表示に必要な情報をまとめて取得（したい）
                this.getData('/api/seasons/detail/' + this.seasonId);

                // 次のゲーム
                this.nextGame('/api/seasons/next-game/' + this.seasonId);
            },
            getData(getPath) {
                axios.get(getPath)
                    .then((res) => {
                    console.log(res)
                        this.data = res.data;
                    });
            },
            nextGame(getPath) {
                axios.get(getPath)
                    .then((res) => {
                        this.nextGameInfo = res.data;
                        console.log(this.nextGameInfo)
                    });
            },
            reShukei(postPath) {
                axios.post(postPath, this.data)
                    .then((res) => {
                        this.initial();
                        alert('再集計完了');
                    });
            }
        },
        props: {
            seasonId: String
        },
        data: function () {
            return {
                data: {
                    season: {},
                    ranking: {},
                    vs: {},
                    monthList : {},
                },
                nextGameInfo: {
                    'id' : null,
                },
            }
        },
        mounted() {
            this.initial();
        }
    }
</script>
