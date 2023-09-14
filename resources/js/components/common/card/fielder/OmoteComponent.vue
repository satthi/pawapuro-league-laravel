<template>
    <div class="card_omote">
        <div class="card_omote_team_1">
            {{ data.data.data.player.team.name }}
        </div>
        <img class="card_omote_shashin" :src='`/img/base_player/${data.data.data.player.base_player_id}/file`' />
        <div class="card_omote_name">
            {{ data.data.data.player.number }}. {{ data.data.data.player.name }}
        </div>
        <div class="card_omote_status_wrap">
            <div class="card_omote_dageki">
                <status-bar-component label="打撃" :value="60"/>
            </div>
            <div class="card_omote_power">
                <status-bar-component label="長打力" :value="70"/>
            </div>
            <div class="card_omote_run">
                <status-bar-component label="走力" :value="80"/>
            </div>
            <div class="card_omote_bant">
                <status-bar-component label="バント" :value="90"/>
            </div>
            <div class="card_omote_def">
                <status-bar-component label="守備力" :value="70"/>
            </div>
            <div class="card_omote_mental">
                <status-bar-component label="精神力" :value="70"/>
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
            {{ data.data.data.player.team.name }}
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
</style>
<script>
    import StatusBarComponent from '../common/StatusBarComponent';
//    import PitcherCardComponent from './pitcher/OmoteComponent';
    export default {
        components: {StatusBarComponent},
        props: ['data'],
    }
</script>
