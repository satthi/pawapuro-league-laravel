<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-6">
                <p>※ゲーム数は140試合固定/DH制は各対戦につき1カード(3試合)ずつ</p>
                    {{ data }}

                <form v-on:submit.prevent="submit(submitPath, {name: 'game.index', params: {seasonId: seasonId.toString() }})">
                    <div class="form-group row">
                        <label for="id" class="col-sm-3 col-form-label">シーズン</label>
                        <div v-text="season.name"></div>
                    </div>

                    <input-component label="開始日" :errors="errors.start_date" v-model="data.start_date"/>
                    <!-- 前年順位の設定を最後にやるけどひとまず無視 -->

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
    import addMixin from '../../mixins/form/add.js';
    export default {
        components: {InputComponent},
        mixins : [addMixin],
        props: {
            seasonId: String
        },
        computed: {
          submitPath() {
            return '/api/games/auto-add/' + this.seasonId;
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
