<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-6">

                    {{ data }}

                <form v-on:submit.prevent="submit(submitPath, {name: 'season.view', params: {seasonId: seasonId.toString() }})">
                    <div class="form-group row">
                        <label for="id" class="col-sm-3 col-form-label">シーズン</label>
                        <div v-text="season.name"></div>
                    </div>

                    <input-component label="開催日" :errors="errors.date" v-model="data.date"/>
                    <select-component label="ホームチーム" :errors="errors.home_team_id" :options="teamOptions" :empty=true v-model="data.home_team_id"/>
                    <select-component label="ビジターチーム" :errors="errors.visitor_team_id" :options="teamOptions" :empty=true v-model="data.visitor_team_id"/>
                    <checkbox-component label="DHフラグ" :errors="errors.dh_flag" v-model="data.dh_flag"/>

                    <button type="submit" class="btn btn-primary">Submit</button>

                    <router-link v-bind:to="{name: 'season.view', params: {seasonId: seasonId.toString() }}">
                        <button class="btn btn-success float-right">一覧に戻る</button>
                    </router-link>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    import InputComponent from '../common/form/InputComponent';
    import SelectComponent from '../common/form/SelectComponent';
    import CheckboxComponent from '../common/form/CheckboxComponent';
    import addMixin from '../../mixins/form/add.js';
    export default {
        components: {InputComponent, SelectComponent, CheckboxComponent},
        mixins : [addMixin],
        props: {
            seasonId: String
        },
        computed: {
          submitPath() {
            return '/api/games/add/' + this.seasonId;
          }
        },
        data: function () {
            return {
                data: {
                    'season_id': this.seasonId,
                    'dh_flag' : false,
                },
                errors: {},
                season: {},
                teamOptions: {}
            }
        },
        methods: {
            getSeasonData(getPath) {
                axios.get(getPath)
                    .then((res) => {
                        this.season = res.data;
                        console.log(this.season)
                    });
            },
            getTeamOptions(getPath) {
                axios.get(getPath)
                    .then((res) => {
                        this.teamOptions = res.data;
                    });
            }
        },

        mounted() {
            this.getSeasonData('/api/seasons/view/' + this.seasonId);
            this.getTeamOptions('/api/teams/get-options/' + this.seasonId);
        }

    }
</script>
