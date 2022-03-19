<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-6">

                <form v-on:submit.prevent="submit(submitPath, {name: 'season.view', params: {seasonId: seasonId.toString() }})">
                    <select-component label="選手" :errors="errors.player_id" :options="players" :empty=true v-model="data.player_id"/>
                    <select-component label="移籍先チーム" :errors="errors.team_id" :options="teams" :empty=true v-model="data.team_id"/>
                    <input-component label="背番号" :errors="errors.number" v-model="data.number"/>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    import InputComponent from '../common/form/InputComponent';
    import SelectComponent from '../common/form/SelectComponent';
    import addMixin from '../../mixins/form/add.js';
    export default {
        components: {InputComponent, SelectComponent},
        mixins : [addMixin],
        props: {
            seasonId: String
        },
        computed: {
          submitPath() {
            return '/api/players/trade/' + this.seasonId;
          }
        },
        data: function () {
            return {
                data: {
                    'season_id': this.seasonId
                },
                errors: {},
                players: {},
                teams: {}
            }
        },
        methods: {
            getPlayers(getPath) {
                axios.get(getPath)
                    .then((res) => {
                        this.players = res.data;
                    });
            },
            getTeams(getPath) {
                axios.get(getPath)
                    .then((res) => {
                        this.teams = res.data;
                    });
            },
        },

        mounted() {
            this.getPlayers('/api/players/get-options/' + this.seasonId);
            this.getTeams('/api/teams/get-options/' + this.seasonId);
        }

    }
</script>
