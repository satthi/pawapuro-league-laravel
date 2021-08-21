<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-6">
                    {{ data }}

                <form v-on:submit.prevent="submit(submitPath, {name: 'base-player.index', params: {baseTeamId: data.base_team_id.toString() }})">
                    <select-component label="チーム名" :errors="errors.base_team_id" :options="teamOptions" :empty=true :value="data.base_team_id" v-model="data.base_team_id"/>

                    <input-component label="背番号" :errors="errors.number" :value="data.number" v-model="data.number"/>
                    <input-component label="選手名" :errors="errors.name" :value="data.name" v-model="data.name"/>
                    <input-component label="表示名" :errors="errors.name_short" :value="data.name_short" v-model="data.name_short"/>

                    <!-- <input-component label="投" :errors="errors.hand_p" v-model="data.hand_p"/> -->
                    <select-component label="投" :errors="errors.hand_p" :options="enums.Kiki" :empty=true :selected="data.hand_p" v-model="data.hand_p"/>
                    <select-component label="打" :errors="errors.hand_b" :options="enums.Kiki" :empty=true :value="data.hand_b" v-model="data.hand_b"/>

                    <select-component label="メインP" :errors="errors.position_main" :options="enums.PlayerPosition" :empty=true :value="data.position_main" v-model="data.position_main"/>
                    <select-component label="サブP1" :errors="errors.position_sub1" :options="enums.PlayerPosition" :empty=true :value="data.position_sub1" v-model="data.position_sub1"/>
                    <select-component label="サブP2" :errors="errors.position_sub2" :options="enums.PlayerPosition" :empty=true :value="data.position_sub2" v-model="data.position_sub2"/>
                    <select-component label="サブP3" :errors="errors.position_sub3" :options="enums.PlayerPosition" :empty=true :value="data.position_sub3" v-model="data.position_sub3"/>

                    <button type="submit" class="btn btn-primary">Submit</button>

                    <router-link v-bind:to="{name: 'base-player.index', params: {baseTeamId: data.base_team_id.toString() }}">
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
    import editMixin from '../../mixins/form/edit.js';
    import EnumsMixin from '../../mixins/enums.js';
    export default {
        components: {InputComponent, SelectComponent},
        mixins : [editMixin, EnumsMixin],
        props: {
            basePlayerId: String
        },
        computed: {
          submitPath() {
            return '/api/base-players/edit/' + this.basePlayerId;
          }
        },
        data: function () {
            return {
                data: {
                    'base_team_id' : 'dummy'
                },
                errors: {},
                teamOptions: {}
            }
        },
        methods: {
            getTeamOptions(getPath) {
                axios.get(getPath)
                    .then((res) => {
                        this.teamOptions = res.data;
                        console.log(this.teamOptions);
                    });
            },
        },
        mounted() {
            this.getTeamOptions('/api/base-teams/get-options')
            this.getData('/api/base-players/view/' + this.basePlayerId);
        }
    }
</script>