<template>
    <div class="card_ura" :style="{backgroundColor:data.data.data.player.team.base_team.base_color }">
        <div class="card_ura_team_1" :style="{color:data.data.data.player.team.base_team.second_color }">
            {{ data.data.data.player.team.name }}
        </div>
        <img class="card_ura_shashin" :src="data.data.data.player.img_path" />
        <div class="card_ura_name" v-if="data.data.data.player.name.length > 7" :style="{fontSize: 7 / data.data.data.player.name.length * 2 + 'em'}">
            {{ data.data.data.player.name }}
        </div>
        <div class="card_ura_name" v-else>
            {{ data.data.data.player.name }}
        </div>
        <div class="card_ura_profile">
            {{ data.data.data.player.hand_full_text }}
            <br />
            背番号{{ data.data.data.player.number }}<br />
            <span v-if="data.data.data.player.position_main === 1">投手</span>
            <span v-if="data.data.data.player.position_main === 2">捕手</span>
            <span v-if="data.data.data.player.position_main === 3">内野手</span>
            <span v-if="data.data.data.player.position_main === 4">外野手</span>
        </div>
        <div class="card_ura_position_wrap">
            <div syle="background-color: black;">
                <svg width="230" height="220" xmlns="http://www.w3.org/2000/svg" style="background-color: gray;">
                    <g fill="#000" stroke="#fff" stroke-width="1">
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
             <div class="card_ura_position_c">
                <status-mark-component :value="60" />
             </div>
             <div class="card_ura_position_1b">
                <status-mark-component :value="70" />
             </div>
             <div class="card_ura_position_2b">
                <status-mark-component :value="80" />
             </div>
             <div class="card_ura_position_3b">
                <status-mark-component :value="90" />
             </div>
             <div class="card_ura_position_ss">
                <status-mark-component :value="50" />
             </div>
             <div class="card_ura_position_lf">
                <status-mark-component :value="40" />
             </div>
             <div class="card_ura_position_cf">
                 <status-mark-component :value="39" />
             </div>
             <div class="card_ura_position_rf">
                 <status-mark-component :value="39" />
             </div>
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
                    <td>{{ data.data.data.player.team.season.name }}</td>
                    <td>{{ data.data.data.player.game }}</td>
                    <td>{{ data.data.data.player.daseki }}</td>
                    <td>{{ data.data.data.player.dasu }}</td>
                    <td>{{ data.data.data.player.hit }}</td>
                    <td>{{ data.data.data.player.display_avg }}</td>
                    <td>{{ data.data.data.player.hr }}</td>
                    <td>{{ data.data.data.player.daten }}</td>
                    <td>{{ data.data.data.player.steal_success }}</td>
                </tr>
            </table>
        </div>
        <div class="card_ura_block_area">
        </div>
        <div class="card_ura_team_2" :style="{color:data.data.data.player.team.base_team.second_color }">
            {{ data.data.data.player.team.name }}
        </div>

    </div>
</template>
<style scoped>
    .card_ura {
        width: 400px;
        height: 600px;
        position: relative;
        float: left;
        margin: 10px;
    }
    .card_ura_team_1 {
        width: 360px;
        height: 20px;
        position: absolute;
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

    .card_ura_seiseki_table th {
        text-align:center;
        white-space: nowrap;
    }
    .card_ura_seiseki_table td {
        text-align:center;
        vertical-align: top;
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
        top: 580px;
        left: 20px;
        text-align: center;
    }
</style>
<script>
    import StatusMarkComponent from '../common/StatusMarkComponent';
    export default {
        components: {StatusMarkComponent},
        props: ['data'],
    }
</script>
