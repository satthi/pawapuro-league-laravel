<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-6">
                    {{ data }}

                <form v-on:submit.prevent="submit(submitPath, {name: 'game.view', params: {gameId: gameId.toString() }})">
                    <select-component label="先行 予告先発" :errors="errors.visitor_probable_pitcher_id" :options="visitorPlayerOptions" :empty=true :value="data.visitor_probable_pitcher_id" v-model="data.visitor_probable_pitcher_id"/>
                    <select-component label="後攻 予告先発" :errors="errors.home_probable_pitcher_id" :options="homePlayerOptions" :empty=true :value="data.home_probable_pitcher_id" v-model="data.home_probable_pitcher_id"/>

                    <button type="submit" class="btn btn-primary">Submit</button>

                    <router-link v-bind:to="{name: 'game.view', params: {gameId: gameId.toString() }}">
                        <button class="btn btn-success float-right">ゲーム詳細に戻る</button>
                    </router-link>
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
            gameId: String
        },
        computed: {
          submitPath() {
            return '/api/games/probable-pitcher-edit/' + this.gameId;
          }
        },
        data: function () {
            return {
                data: {},
                errors: {},
                homePlayerOptions: {},
                visitorPlayerOptions: {}
            }
        },
        methods: {
            getPlayerOptions(getPath) {
                axios.get(getPath)
                    .then((res) => {
                        this.homePlayerOptions = res.data.home;
                        this.visitorPlayerOptions = res.data.visitor;
                    });
            },
        },
        mounted() {
            this.getPlayerOptions('/api/games/get-probable-pitcher-options/' + this.gameId)
            this.getData('/api/games/view/' + this.gameId);
        }
    }
</script>