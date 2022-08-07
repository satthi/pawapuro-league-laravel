<template>
    <div class="container">
        <h2>タイトル登録</h2>
        <div class="row justify-content-center">
            <div class="col-sm-6">
                <form v-on:submit.prevent="submit(submitPath, {name: 'season.title', params: {seasonId: seasonId.toString() }})">

                    <select-component label="mvp" :options="players" :empty=true :value="data.mvp_player_id" v-model="data.mvp_player_id"/>
                    <select-component label="B9 投手" :options="players" :empty=true :value="data.b9_1_player_id" v-model="data.b9_1_player_id"/>
                    <select-component label="B9 捕手" :options="players" :empty=true :value="data.b9_2_player_id" v-model="data.b9_2_player_id"/>
                    <select-component label="B9 一塁手" :options="players" :empty=true :value="data.b9_3_player_id" v-model="data.b9_3_player_id"/>
                    <select-component label="B9 二塁手" :options="players" :empty=true :value="data.b9_4_player_id" v-model="data.b9_4_player_id"/>
                    <select-component label="B9 三塁手" :options="players" :empty=true :value="data.b9_5_player_id" v-model="data.b9_5_player_id"/>
                    <select-component label="B9 遊撃手" :options="players" :empty=true :value="data.b9_6_player_id" v-model="data.b9_6_player_id"/>
                    <select-component label="B9 外野手1" :options="players" :empty=true :value="data.b9_7_player_id" v-model="data.b9_7_player_id"/>
                    <select-component label="B9 外野手2" :options="players" :empty=true :value="data.b9_8_player_id" v-model="data.b9_8_player_id"/>
                    <select-component label="B9 外野手3" :options="players" :empty=true :value="data.b9_9_player_id" v-model="data.b9_9_player_id"/>

                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    import SelectComponent from '../common/form/SelectComponent';
    import editMixin from '../../mixins/form/edit.js';
    export default {
        components: {SelectComponent},
        mixins : [editMixin],
        props: {
            seasonId: String
        },
        computed: {
          submitPath() {
            return '/api/seasons/edit/' + this.seasonId;
          }
        },
        data: function () {
            return {
                data: {},
                errors: {},
                players: {}
            }
        },
        methods: {
            getPlayers(getPath) {
                axios.get(getPath)
                    .then((res) => {
                        this.players = res.data;
                    });
            },
        },
        mounted() {
            this.getData('/api/seasons/view/' + this.seasonId);
            this.getPlayers('/api/players/get-options/' + this.seasonId);
        }
    }
</script>