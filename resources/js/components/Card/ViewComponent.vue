<template>
    <div class="container">
        <div class="card_omote">
            <div class="card_omote_team_1">
                MORIYA FROGS
            </div>
            <img class="card_omote_shashin" src="/img/base_player/92/file" />
            <div class="card_omote_name">
                90. 霊烏路　空
            </div>
            <div class="card_omote_status_wrap">
                <div class="card_omote_dageki">
                    <div class="status_label">
                        打撃
                    </div>
                    <div class="status_waku">
                    </div>
                    <div class="status_value_bar">
                    </div>
                    <div class="status_value">
                        75
                    </div>
                </div>
                <div class="card_omote_power">
                    <div class="status_label">
                        長打力
                    </div>
                    <div class="status_waku">
                    </div>
                    <div class="status_value_bar">
                    </div>
                    <div class="status_value">
                        75
                    </div>
                </div>
                <div class="card_omote_run">
                    <div class="status_label">
                        走力
                    </div>
                    <div class="status_waku">
                    </div>
                    <div class="status_value_bar">
                    </div>
                    <div class="status_value">
                        75
                    </div>
                </div>
                <div class="card_omote_bant">
                    <div class="status_label">
                        バント
                    </div>
                    <div class="status_waku">
                    </div>
                    <div class="status_value_bar">
                    </div>
                    <div class="status_value">
                        75
                    </div>
                </div>
                <div class="card_omote_def">
                    <div class="status_label">
                        守備力
                    </div>
                    <div class="status_waku">
                    </div>
                    <div class="status_value_bar">
                    </div>
                    <div class="status_value">
                        75
                    </div>
                </div>
                <div class="card_omote_mental">
                    <div class="status_label">
                        精神力
                    </div>
                    <div class="status_waku">
                    </div>
                    <div class="status_value_bar">
                    </div>
                    <div class="status_value">
                        75
                    </div>
                </div>
            </div>
            <div class="card_omote_cost">
                <div class="card_omote_cost_star">
                    ★★★★★★★★★☆
                </div>
                <div class="card_omote_cost_number">
                    9
                </div>
            </div>
            <div class="card_omote_team_2">
                MORIYA FROGS
            </div>
        </div>

        <div class="card_ura">
            <div class="card_ura_team_1">
                MORIYA FROGS
            </div>
            <img class="card_ura_shashin" src="/img/base_player/92/file" />
            <div class="card_ura_name">
                霊烏路　空
            </div>
            <div class="card_ura_profile">
                左投左打<br />
                背番号90<br />
                外野手
            </div>
            <div class="card_ura_position_wrap">
                <svg widht="230" height="220" xmlns="http://www.w3.org/2000/svg">
                    <g fill="#fff" stroke="#000" stroke-width="1">
                        <path d="M115,10 L20,105" />
                        <path d="M20,105 L115,200" />
                        <path d="M115,200 L210,105" />
                        <path d="M210,105 L115,10" />
                        <path d="M67.5,152.5 L115,105" />
                        <path d="M162.5,152.5 L115,105" />

                        <path d="M71.5,156.5 L75.5,152.5" />
                        <path d="M75.5,152.5 L71.5,148.5" />

                        <path d="M158.5,148.5 L154.5,152.5" />
                        <path d="M158.5,156.5 L154.5,152.5" />

                        <path d="M111,109 L115,113" />
                        <path d="M115,113 L119,109" />

                        <path d="M111,196 L111,192" />
                        <path d="M111,192 L119,192" />
                        <path d="M119,192 L119,196" />

                    </g>
                 </svg>
            </div>
            <div class="card_ura_seiseki">
                <table class="card_ura_seiseki_table">
                    <tr>
                        <th>シーズン</th>
                        <th>試合</th>
                        <th>打席</th>
                        <th>打数</th>
                        <th>安打</th>
                        <th>打率</th>
                        <th>HR</th>
                        <th>打点</th>
                        <th>盗塁</th>
                    </tr>
                    <tr>
                        <td>1期</td>
                        <td>140</td>
                        <td>.310</td>
                        <td>610</td>
                        <td>130</td>
                        <td>.310</td>
                        <td>45</td>
                        <td>125</td>
                        <td>30</td>
                    </tr>
                </table>
            </div>
            <div class="card_ura_block_area">
            </div>
            <div class="card_ura_team_2">
                MORIYA FROGS
            </div>

        </div>

    </div>
</template>
<style scoped>
    .card_omote {
        width: 400px;
        height: 600px;
        background-color: green;
        position: relative;
        float: left;
        margin: 10px;
    }
    .card_omote_team_1 {
        width: 360px;
        height: 20px;
        position: absolute;
        color: white;
        top: 0px;
        left: 20px;
        text-align: center;
    }
    .card_omote_shashin {
        width: 360px;
        height: 320px;
        object-fit: cover;
        background-color: white;
        position: absolute;
        top: 20px;
        left: 20px;
    }
    .card_omote_name {
        width: 360px;
        height: 40px;
        background-color: black;
        color: white;
        text-align: center;
        position: absolute;
        font-size: 2em;
        top: 360px;
        left: 20px;
    }
    .card_omote_status_wrap {
        width: 360px;
        height: 130px;
        left: 20px;
        top: 410px;
        background-color: black;
        position: absolute;
    }
    .card_omote_dageki {
        top: 2px;
        position: absolute;
    }
    .card_omote_power {
        top: 23px;
        position: absolute;
    }
    .card_omote_run {
        top: 44px;
        position: absolute;
    }
    .card_omote_bant {
        top: 65px;
        position: absolute;
    }
    .card_omote_def {
        top: 86px;
        position: absolute;
    }
    .card_omote_mental {
        top: 107px;
        position: absolute;
    }
    .status_label {
        width: 75px;
        height: 20px;
        background-color: white;
        text-align: center;
        position: absolute;
        left: 0px;
    }
    .status_waku {
        width: 240px;
        height: 20px;
        text-align: center;
        position: absolute;
        background-color: gray;
        left: 75px;
    }
    .status_value_bar {
        width: 200px;
        height: 10px;
        background-color: white;
        text-align: center;
        position: absolute;
        top: 5px;
        left: 80px;
    }
    .status_value {
        width: 60px;
        height: 20px;
        text-align: center;
        position: absolute;
        background-color: gray;
        color: white;
        left: 300px;
    }
    .card_omote_cost {
        width: 360px;
        height: 35px;
        position: absolute;
        font-size: 2.5em;
        color:white;
        top: 535px;
        left: 20px;
    }
    .card_omote_cost_star {
        width: 360px;
        position: absolute;
        text-align: left;
    }
    .card_omote_cost_number {
        width: 360px;
        position: absolute;
        text-align: right;
    }
    .card_omote_team_2 {
        width: 360px;
        height: 20px;
        position: absolute;
        color: white;
        top: 580px;
        left: 20px;
        text-align: center;
    }

    .card_ura {
        width: 400px;
        height: 600px;
        background-color: green;
        position: relative;
        float: left;
        margin: 10px;
    }
    .card_ura_team_1 {
        width: 360px;
        height: 20px;
        position: absolute;
        color: white;
        top: 0px;
        left: 20px;
        text-align: center;
    }
    .card_ura_shashin {
        width: 130px;
        height: 160px;
        object-fit: cover;
        background-color: white;
        position: absolute;
        top: 20px;
        left: 20px;
    }
    .card_ura_name {
        width: 230px;
        height: 40px;
        text-align: center;
        position: absolute;
        background-color: black;
        color: white;
        font-size: 2em;
        top: 20px;
        left: 150px;
    }
    .card_ura_profile {
        padding: 10px;
        width: 130px;
        height: 100px;
        background-color: black;
        color: white;
        position: absolute;
        top: 180px;
        left: 20px;
    } 
    .card_ura_position_wrap {
        width: 230px;
        height: 220px;
        background-color: white;
        position: absolute;
        font-size: 2em;
        top: 60px;
        left: 150px;
    }
    .card_ura_seiseki {
        width: 360px;
        height: 50px;
        background-color: white;
        position: absolute;
        top: 280px;
        left: 20px;
    }

    .card_ura_seiseki_table {
        width: 100%;
    }

    .card_ura_block_area {
        width: 360px;
        height: 240px;
        background-color: white;
        position: absolute;
        top: 340px;
        left: 20px;
    }

    .card_ura_team_2 {
        width: 360px;
        height: 20px;
        position: absolute;
        color: white;
        top: 580px;
        left: 20px;
        text-align: center;
    }
</style>
<script>
    export default {
        methods: {
            initial() {

            },
        },
        data: function () {
            return {
                teams: {},
                hensen: {},
            }
        },
        mounted() {
            this.initial();
        }
    }
</script>
