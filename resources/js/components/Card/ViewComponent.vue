<template>
    <div class="container" v-if="Object.keys(dataset).length">
        <div class="card_omote">
            <card-omote-component :data="dataset" />
        </div>
        <div class="card_ura">
            <card-ura-component :data="dataset" />
        </div>
        <div>
            <form v-on:submit.prevent="submit('/api/cards/update/' + dataset.player.id)">
                <div v-if="dataset.player.position_main != 1">
                    <table class="card_input_table">
                        <tr>
                            <th>コスト</th>
                            <td>
                                <input-component label="" :value="dataset.player.card_cost" v-model="dataset.player.card_cost"/>
                            </td>
                        </tr>
                        <tr>
                            <th>打撃</th>
                            <td>
                                <input-component label="" :value="dataset.player.card_info.batter.meat" v-model="dataset.player.card_info.batter.meat"/>
                            </td>
                        </tr>
                        <tr>
                            <th>長打力</th>
                            <td>
                                <input-component label="" :value="dataset.player.card_info.batter.power" v-model="dataset.player.card_info.batter.power"/>
                            </td>
                        </tr>
                        <tr>
                            <th>走力</th>
                            <td>
                                <input-component label="" :value="dataset.player.card_info.batter.run" v-model="dataset.player.card_info.batter.run"/>
                            </td>
                        </tr>
                        <tr>
                            <th>バント</th>
                            <td>
                                <input-component label="" :value="dataset.player.card_info.batter.bant" v-model="dataset.player.card_info.batter.bant"/>
                            </td>
                        </tr>
                        <tr>
                            <th>守備力</th>
                            <td>
                                <input-component label="" :value="dataset.player.card_info.batter.defence" v-model="dataset.player.card_info.batter.defence"/>
                            </td>
                        </tr>
                        <tr>
                            <th>精神力</th>
                            <td>
                                <input-component label="" :value="dataset.player.card_info.batter.mental" v-model="dataset.player.card_info.batter.mental"/>
                            </td>
                        </tr>
                    </table>
                    <table class="card_input_table">
                        <tr>
                            <th>捕手</th>
                            <td>
                                <input-component label="" :value="dataset.player.card_info.batter.position_c" v-model="dataset.player.card_info.batter.position_c"/>
                            </td>
                        </tr>
                        <tr>
                            <th>一塁手</th>
                            <td>
                                <input-component label="" :value="dataset.player.card_info.batter.position_1b" v-model="dataset.player.card_info.batter.position_1b"/>
                            </td>
                        </tr>
                        <tr>
                            <th>二塁手</th>
                            <td>
                                <input-component label="" :value="dataset.player.card_info.batter.position_2b" v-model="dataset.player.card_info.batter.position_2b"/>
                            </td>
                        </tr>
                        <tr>
                            <th>三塁手</th>
                            <td>
                                <input-component label="" :value="dataset.player.card_info.batter.position_3b" v-model="dataset.player.card_info.batter.position_3b"/>
                            </td>
                        </tr>
                        <tr>
                            <th>遊撃手</th>
                            <td>
                                <input-component label="" :value="dataset.player.card_info.batter.position_ss" v-model="dataset.player.card_info.batter.position_ss"/>
                            </td>
                        </tr>
                        <tr>
                            <th>左翼手</th>
                            <td>
                                <input-component label="" :value="dataset.player.card_info.batter.position_lf" v-model="dataset.player.card_info.batter.position_lf"/>
                            </td>
                        </tr>
                        <tr>
                            <th>中堅手</th>
                            <td>
                                <input-component label="" :value="dataset.player.card_info.batter.position_cf" v-model="dataset.player.card_info.batter.position_cf"/>
                            </td>
                        </tr>
                        <tr>
                            <th>右翼手</th>
                            <td>
                                <input-component label="" :value="dataset.player.card_info.batter.position_rf" v-model="dataset.player.card_info.batter.position_rf"/>
                            </td>
                        </tr>
                    </table>
                </div>
                <div v-else>
                    <table class="card_input_table">
                        <tr>
                            <th>コスト</th>
                            <td>
                                <input-component label="" :value="dataset.player.card_cost" v-model="dataset.player.card_cost"/>
                            </td>
                        </tr>
                        <tr>
                            <th>体力</th>
                            <td>
                                <input-component label="" :value="dataset.player.card_info.pitcher.stamina" v-model="dataset.player.card_info.pitcher.stamina"/>
                            </td>
                        </tr>
                        <tr>
                            <th>球速</th>
                            <td>
                                <input-component label="" :value="dataset.player.card_info.pitcher.speed" v-model="dataset.player.card_info.pitcher.speed"/>
                            </td>
                        </tr>
                        <tr>
                            <th>球威</th>
                            <td>
                                <input-component label="" :value="dataset.player.card_info.pitcher.power" v-model="dataset.player.card_info.pitcher.power"/>
                            </td>
                        </tr>
                        <tr>
                            <th>変化球</th>
                            <td>
                                <input-component label="" :value="dataset.player.card_info.pitcher.henka" v-model="dataset.player.card_info.pitcher.henka"/>
                            </td>
                        </tr>
                        <tr>
                            <th>制球力</th>
                            <td>
                                <input-component label="" :value="dataset.player.card_info.pitcher.control" v-model="dataset.player.card_info.pitcher.control"/>
                            </td>
                        </tr>
                        <tr>
                            <th>精神力</th>
                            <td>
                                <input-component label="" :value="dataset.player.card_info.pitcher.mental" v-model="dataset.player.card_info.pitcher.mental"/>
                            </td>
                        </tr>
                    </table>
                    <table class="card_input_table">
                        <tr>
                            <th>左</th>
                            <td>
                                <input-component label="" :value="dataset.player.card_info.pitcher.henka_l" v-model="dataset.player.card_info.pitcher.henka_l"/>
                                <input-component label="" :value="dataset.player.card_info.pitcher.henka_l_name" v-model="dataset.player.card_info.pitcher.henka_l_name"/>
                            </td>
                        </tr>
                        <tr>
                            <th>左下</th>
                            <td>
                                <input-component label="" :value="dataset.player.card_info.pitcher.henka_lb" v-model="dataset.player.card_info.pitcher.henka_lb"/>
                                <input-component label="" :value="dataset.player.card_info.pitcher.henka_lb_name" v-model="dataset.player.card_info.pitcher.henka_lb_name"/>
                            </td>
                        </tr>
                        <tr>
                            <th>下</th>
                            <td>
                                <input-component label="" :value="dataset.player.card_info.pitcher.henka_b" v-model="dataset.player.card_info.pitcher.henka_b"/>
                                <input-component label="" :value="dataset.player.card_info.pitcher.henka_b_name" v-model="dataset.player.card_info.pitcher.henka_b_name"/>
                            </td>
                        </tr>
                        <tr>
                            <th>右下</th>
                            <td>
                                <input-component label="" :value="dataset.player.card_info.pitcher.henka_rb" v-model="dataset.player.card_info.pitcher.henka_rb"/>
                                <input-component label="" :value="dataset.player.card_info.pitcher.henka_rb_name" v-model="dataset.player.card_info.pitcher.henka_rb_name"/>
                            </td>
                        </tr>
                        <tr>
                            <th>右</th>
                            <td>
                                <input-component label="" :value="dataset.player.card_info.pitcher.henka_r" v-model="dataset.player.card_info.pitcher.henka_r"/>
                                <input-component label="" :value="dataset.player.card_info.pitcher.henka_r_name" v-model="dataset.player.card_info.pitcher.henka_r_name"/>
                            </td>
                        </tr>
                    </table>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
                <router-link v-bind:to="{name: 'card.view', params: {playerId: (Number(playerId) + 1).toString() }}">
                    <button class="btn btn-success float-right">次のID</button>
                </router-link>
            </form>
        </div>


        <h4>野手成績</h4>
        <table class="table table-hover seiseki_table">
            <tr>
                <th>試<br />合</th>
                <th>打<br />率</th>
                <th>本<br />塁<br />打</th>
                <th>打<br />点</th>
                <th>打<br />席</th>
                <th>打<br />数</th>
                <th>安<br />打</th>
                <th>二<br />塁<br />打</th>
                <th>三<br />塁<br />打</th>
                <th>三<br />振</th>
                <th>併<br />殺</th>
                <th>四<br />球</th>
                <th>死<br />球</th>
                <th>犠<br />打</th>
                <th>犠<br />飛</th>
                <th>盗<br />塁</th>
                <th>盗<br />塁<br />失</th>
                <th>出<br />塁<br />率</th>
                <th>長<br />打<br />率</th>
                <th>O<br />P<br />S</th>
            </tr>
            <tr>
                <td>{{ dataset.player.game }}</td>
                <td>{{ dataset.player.display_avg }}</td>
                <td>{{ dataset.player.hr }}</td>
                <td>{{ dataset.player.daten }}</td>
                <td>{{ dataset.player.daseki }}</td>
                <td>{{ dataset.player.dasu }}</td>
                <td>{{ dataset.player.hit }}</td>
                <td>{{ dataset.player.hit_2 }}</td>
                <td>{{ dataset.player.hit_3 }}</td>
                <td>{{ dataset.player.sansin }}</td>
                <td>{{ dataset.player.heisatsu }}</td>
                <td>{{ dataset.player.walk }}</td>
                <td>{{ dataset.player.dead }}</td>
                <td>{{ dataset.player.bant }}</td>
                <td>{{ dataset.player.sac_fly }}</td>
                <td>{{ dataset.player.steal_success }}</td>
                <td>{{ dataset.player.steal_miss }}</td>
                <td>{{ dataset.player.display_obp }}</td>
                <td>{{ dataset.player.display_slg }}</td>
                <td>{{ dataset.player.display_ops }}</td>
            </tr>
        </table>
        <div v-if="dataset.player.position_main == enums.Position.POSITION_P.value || dataset.player.p_inning > 0">
            <h4>投手成績成績</h4>
            <table class="table table-hover seiseki_table">
                <tr>
                    <th>試<br />合</th>
                    <th>防<br />御<br />率</th>
                    <th>勝<br />利</th>
                    <th>敗<br />北</th>
                    <th>ホ<br />ー<br />ル<br />ド</th>
                    <th>セ<br />ー<br />ブ</th>
                    <th>勝<br />率</th>
                    <th>奪<br />三<br />振</th>
                    <th>奪<br />三<br />振<br />率</th>
                    <th>被<br />安<br />打</th>
                    <th>被<br />打<br />率</th>
                    <th>被<br />本<br />塁<br />打</th>
                    <th>自<br />責<br />点</th>
                    <th>回<br />数</th>
                    <th>完<br />投</th>
                    <th>完<br />封</th>
                    <th>四<br />球</th>
                    <th>死<br />球</th>
                </tr>
                <tr>
                    <td>{{ dataset.player.p_game }}</td>
                    <td>{{ dataset.player.display_p_era }}</td>
                    <td>{{ dataset.player.p_win }}</td>
                    <td>{{ dataset.player.p_lose }}</td>
                    <td>{{ dataset.player.p_hold }}</td>
                    <td>{{ dataset.player.p_save }}</td>
                    <td>{{ dataset.player.display_p_win_ratio }}</td>
                    <td>{{ dataset.player.p_sansin }}</td>
                    <td>{{ dataset.player.display_p_sansin_ratio }}</td>
                    <td>{{ dataset.player.p_hit }}</td>
                    <td>{{ dataset.player.display_p_avg }}</td>
                    <td>{{ dataset.player.p_hr }}</td>
                    <td>{{ dataset.player.p_jiseki }}</td>
                    <td>{{ dataset.player.display_p_inning }}</td>
                    <td>{{ dataset.player.p_kanto }}</td>
                    <td>{{ dataset.player.p_kanpu }}</td>
                    <td>{{ dataset.player.p_walk }}</td>
                    <td>{{ dataset.player.p_dead }}</td>
                </tr>
            </table>
        </div>
    </div>
</template>
<style scoped>
.card_input_table {
    float:left;
    width: 130px;
}

.card_input_table th {
width: 60px;
}
.card_input_table td {
width: 70px;
}
</style>

<script>
    import InputComponent from '../common/form/InputComponent';
    import CardOmoteComponent from '../common/card/OmoteComponent';
    import CardUraComponent from '../common/card/UraComponent';
    import EnumsMixin from '../../mixins/enums.js';
    export default {
        components: {InputComponent, CardOmoteComponent, CardUraComponent},
        watch: {
            '$route' (to, from) {
                this.initial();
            }
        },
        mixins : [EnumsMixin],
        methods: {
            initial() {
                this.getData('/api/players/view/' + this.playerId);
            },
            getData(getPath) {
                axios.get(getPath)
                    .then((res) => {
                        this.dataset = res.data;
                    });
            },
            submit(postPath) {
                var postData = this.dataset;
                axios.post(postPath, postData)
                    .then((res) => {
                    alert('更新');
                    })
                    .catch((error) => {
                        this.errors = error.response.data.errors;
                    });
            }
        },
        props: {
            playerId: String
        },
        data: function () {
            return {
                dataset: {}
            }
        },
        mounted() {
            this.initial();
        }
    }
</script>
