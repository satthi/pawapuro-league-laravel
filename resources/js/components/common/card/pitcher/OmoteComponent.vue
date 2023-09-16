<template>
    <div class="card_omote" :style="{backgroundColor:data.player.team.base_team.base_color }">
        <div class="card_omote_team_1" :style="{color:data.player.team.base_team.second_color }">
            {{ data.player.team.name }}
        </div>
        <img class="card_omote_shashin" :src="data.player.img_path" />
        <div class="card_omote_name" v-if="data.player.name.length > 10" :style="{fontSize: 10 / data.player.name.length * 2 + 'em'}">
            {{ data.player.number }}. {{ data.player.name }}
        </div>
        <div class="card_omote_name" v-else>
            {{ data.player.number }}. {{ data.player.name }}
        </div>
        <div class="card_omote_status_wrap">
            <div class="card_omote_dageki">
                <status-bar-component label="体力" :value="data.player.card_info.pitcher.stamina"/>
            </div>
            <div class="card_omote_power">
                <status-bar-component label="球速" :value="data.player.card_info.pitcher.speed"/>
            </div>
            <div class="card_omote_run">
                <status-bar-component label="球威" :value="data.player.card_info.pitcher.power"/>
            </div>
            <div class="card_omote_bant">
                <status-bar-component label="変化球" :value="data.player.card_info.pitcher.henka"/>
            </div>
            <div class="card_omote_def">
                <status-bar-component label="制球力" :value="data.player.card_info.pitcher.control"/>
            </div>
            <div class="card_omote_mental">
                <status-bar-component label="精神力" :value="data.player.card_info.pitcher.mental"/>
            </div>
        </div>
        <div class="card_omote_cost" :style="{color:data.player.team.base_team.second_color }">
            <div class="card_omote_cost_star">
                <span v-for="i in 10">{{ i <= data.player.card_cost ? '★' : '☆' }}</span>
            </div>
            <div class="card_omote_cost_number">
                {{ data.player.card_cost }}
            </div>
        </div>
        <div class="card_omote_team_2" :style="{color:data.player.team.base_team.second_color }">
            {{ data.player.team.name }}
        </div>
    </div>
</template>
<style scoped>
    .card_omote {
        width: 400px;
        height: 600px;
        position: relative;
        float: left;
        margin: 10px;
    }
    .card_omote_team_1 {
        width: 360px;
        height: 20px;
        position: absolute;
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
