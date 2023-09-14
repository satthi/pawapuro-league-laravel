<template>
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
            <div>
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
             <div class="card_ura_position_c">Ⓐ</div>
             <div class="card_ura_position_1b">Ⓐ</div>
             <div class="card_ura_position_2b">Ⓐ</div>
             <div class="card_ura_position_3b">Ⓐ</div>
             <div class="card_ura_position_ss">Ⓐ</div>
             <div class="card_ura_position_lf">Ⓐ</div>
             <div class="card_ura_position_cf">Ⓐ</div>
             <div class="card_ura_position_rf">Ⓐ</div>
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
</template>
<style scoped>
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
    .card_ura_position_c {
        position: absolute;
        top: 187px;
        left: 105px;
        font-size: 0.8em;
    }
    .card_ura_position_1b {
        position: absolute;
        top: 140px;
        left: 135px;
        font-size: 0.8em;
    }
    .card_ura_position_2b {
        position: absolute;
        top: 105px;
        left: 137px;
        font-size: 0.8em;
    }
    .card_ura_position_3b {
        position: absolute;
        top: 140px;
        left: 77px;
        font-size: 0.8em;
    }
    .card_ura_position_ss {
        position: absolute;
        top: 105px;
        left: 75px;
        font-size: 0.8em;
    }
    .card_ura_position_lf {
        position: absolute;
        top: 60px;
        left: 55px;
        font-size: 0.8em;
    }
    .card_ura_position_cf {
        position: absolute;
        top: 30px;
        left: 105px;
        font-size: 0.8em;
    }
    .card_ura_position_rf {
        position: absolute;
        top: 60px;
        left: 150px;
        font-size: 0.8em;
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