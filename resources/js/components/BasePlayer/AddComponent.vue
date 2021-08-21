<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-6">

                    {{ data }}

                <form v-on:submit.prevent="submit(submitPath, {name: 'base-player.index', params: {baseTeamId: baseTeamId.toString() }})">
                    <div class="form-group row">
                        <label for="id" class="col-sm-3 col-form-label">チーム名</label>
                        <div v-text="team.name"></div>
                    </div>

                    <input-component label="背番号" :errors="errors.number" v-model="data.number"/>
                    <input-component label="選手名" :errors="errors.name" v-model="data.name"/>
                    <input-component label="表示名" :errors="errors.name_short" v-model="data.name_short"/>

                    <!-- <input-component label="投" :errors="errors.hand_p" v-model="data.hand_p"/> -->
                    <select-component label="投" :errors="errors.hand_p" :options="enums.Kiki" :empty=true v-model="data.hand_p"/>
                    <select-component label="打" :errors="errors.hand_b" :options="enums.Kiki" :empty=true v-model="data.hand_b"/>

                    <select-component label="メインP" :errors="errors.position_main" :options="enums.PlayerPosition" :empty=true v-model="data.position_main"/>
                    <select-component label="サブP1" :errors="errors.position_sub1" :options="enums.PlayerPosition" :empty=true v-model="data.position_sub1"/>
                    <select-component label="サブP2" :errors="errors.position_sub2" :options="enums.PlayerPosition" :empty=true v-model="data.position_sub2"/>
                    <select-component label="サブP3" :errors="errors.position_sub3" :options="enums.PlayerPosition" :empty=true v-model="data.position_sub3"/>

                    <button type="submit" class="btn btn-primary">Submit</button>

                    <router-link v-bind:to="{name: 'base-player.index', params: {baseTeamId: baseTeamId.toString() }}">
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
    import addMixin from '../../mixins/form/add.js';
    import EnumsMixin from '../../mixins/enums.js';
    export default {
        components: {InputComponent, SelectComponent},
        mixins : [addMixin, EnumsMixin],
        props: {
            baseTeamId: String
        },
        computed: {
          submitPath() {
            return '/api/base-players/add/' + this.baseTeamId;
          }
        },
        data: function () {
            return {
                data: {
                    'base_team_id': this.baseTeamId
                },
                errors: {},
                team: {}
            }
        },
        methods: {
            getTeamData(getPath) {
                axios.get(getPath)
                    .then((res) => {
                        this.team = res.data;
                        console.log(this.team)
                    });
            },
        },

        mounted() {
            this.getTeamData('/api/base-teams/view/' + this.baseTeamId);
        }

    }
</script>
