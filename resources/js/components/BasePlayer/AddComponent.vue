<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-6">
                <form v-on:submit.prevent="submit(submitPath, {name: 'base-player.index', params: {baseTeamId: baseTeamId.toString() }})">
                    <div class="form-group row">
                        <label for="id" class="col-sm-3 col-form-label">チーム名</label>
                        <div v-text="team.name"></div>
                    </div>

                    <input-component label="背番号" :errors="errors.number" v-model="data.number"/>
                    <input-component label="選手名" :errors="errors.name" v-model="data.name"/>
                    <input-component label="表示名" :errors="errors.name_short" v-model="data.name_short"/>
                    <input-component label="投" :errors="errors.hand_p" v-model="data.hand_p"/>
                    <input-component label="打" :errors="errors.hand_b" v-model="data.hand_b"/>
                    <input-component label="メインポジション" :errors="errors.position_main" v-model="data.position_main"/>
                    <input-component label="サブポジション1" :errors="errors.position_sub1" v-model="data.position_sub1"/>
                    <input-component label="サブポジション2" :errors="errors.position_sub2" v-model="data.position_sub2"/>
                    <input-component label="サブポジション3" :errors="errors.position_sub3" v-model="data.position_sub3"/>

                    <button type="submit" class="btn btn-primary">Submit</button>
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
            baseTeamId: String
        },
        computed: {
          submitPath() {
            return '/api/base-players/' + this.baseTeamId;
          }
        },
        data: function () {
            return {
                data: {
                    'base_team_id': this.baseTeamId
                },
                errors: {},
                team: {},
            }
        },
        methods: {
            getTeamData(getPath) {
                axios.get(getPath)
                    .then((res) => {
                        this.team = res.data;
                    });
            },
        },

        mounted() {
            this.getTeamData('/api/base-teams/' + this.baseTeamId);
        }

    }
</script>
