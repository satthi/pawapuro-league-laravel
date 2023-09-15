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
            <div>
                <svg width="230" height="220" xmlns="http://www.w3.org/2000/svg" style="background-color: gray;">
                     <circle cx="115" cy="50" r="20" stroke="black" stroke-width="1" fill="#FFFFFF"></circle>
                    <path d="M 103,35 a 30 30 -30 0 1 0,30" fill="none" stroke="black"/>
                    <path d="M 127,65 a 30 30 150 0 1 0,-30" fill="none" stroke="black"/>
                 </svg>
             </div>
             <div class="card_ura_henka_l">
                <henka-bar-component :value="5" />
             </div>
             <div class="card_ura_henka_l_text">
                スライダー
             </div>
             <div class="card_ura_henka_ld">
                <henka-bar-component :value="5" />
             </div>
             <div class="card_ura_henka_ld_text">
                 ナックルカーブ
             </div>
             <div class="card_ura_henka_d">
                <henka-bar-component :value="5" />
             </div>
             <div class="card_ura_henka_d_text">
                 チェンジアップ
             </div>
             <div class="card_ura_henka_rd">
                <henka-bar-component :value="5" />
             </div>
             <div class="card_ura_henka_rd_text">
                 Cチェンジ
             </div>
             <div class="card_ura_henka_r">
                <henka-bar-component :value="5" />
             </div>
             <div class="card_ura_henka_r_text">
                 ツーシーム
             </div>

        </div>
        <div class="card_ura_seiseki">
            <table class="card_ura_seiseki_table">
                <tr>
                    <th>シーズン</th>
                    <th>試合</th>
                    <th>勝</th>
                    <th>負</th>
                    <th>HP</th>
                    <th>SP</th>
                    <th>防御率</th>
                </tr>
                <tr>
                    <td>{{ data.data.data.player.team.season.name }}</td>
                    <td>{{ data.data.data.player.p_game }}</td>
                    <td>{{ data.data.data.player.p_win }}</td>
                    <td>{{ data.data.data.player.p_lose }}</td>
                    <td>{{ data.data.data.player.p_hold }}</td>
                    <td>{{ data.data.data.player.p_save }}</td>
                    <td>{{ data.data.data.player.display_p_era }}</td>
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
        background-color: green;
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
    .card_ura_position_name {
        font-size: 0.4em;
    }
    .card_ura_henka_l {
        position: absolute;
        top: 15px;
        left: 40px;
        transform: rotate(90deg);
    }
    .card_ura_henka_l_text {
        position: absolute;
        top: 15px;
        left: 10px;
        color: white;
        font-size: 0.5em;
    }

    .card_ura_henka_ld {
        position: absolute;
        top: 70px;
        left: 50px;
        transform: rotate(45deg);
    }

    .card_ura_henka_ld_text {
        position: absolute;
        top: 130px;
        left: 5px;
        color: white;
        font-size: 0.5em;
    }
    .card_ura_henka_d {
        position: absolute;
        top: 90px;
        left: 108px;
    }
    .card_ura_henka_d_text {
        position: absolute;
        top: 160px;
        left: 80px;
        color: white;
        font-size: 0.5em;
    }

    .card_ura_henka_rd {
        position: absolute;
        top: 70px;
        left: 160px;
        transform: rotate(-45deg);
    }

    .card_ura_henka_rd_text {
        position: absolute;
        top: 130px;
        left: 135px;
        color: white;
        font-size: 0.5em;
    }
    .card_ura_henka_r {
        position: absolute;
        top: 15px;
        left: 175px;
        transform: rotate(-90deg);
    }
    .card_ura_henka_r_text {
        position: absolute;
        top: 15px;
        left: 140px;
        color: white;
        font-size: 0.5em;
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
    import HenkaBarComponent from '../common/HenkaBarComponent';
    export default {
        components: {HenkaBarComponent},
        props: ['data'],
    }
</script>
