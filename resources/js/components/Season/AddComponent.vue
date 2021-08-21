<template>
    <div class="container">
        <h2>シーズン新規登録</h2>
        <div class="row justify-content-center">
            <div class="col-sm-6">
                {{ data }}
                <form v-on:submit.prevent="submit('/api/seasons/add', {name: 'season.index'})">
                    <input-component label="シーズン名" :errors="errors.name" v-model="data.name"/>
                    <input-component label="開始日" :errors="errors.start_date" v-model="data.start_date"/>
                    <input-component label="試合数" :errors="errors.game_count" v-model="data.game_count"/>
                    <checkbox-component label="レギュラーフラグ" :errors="errors.regular_flag" v-model="data.regular_flag"/>

                    <hr />
                    <h4>参加チーム</h4>
                    <multiple-checkbox-component v-for="(baseTeam, i) in baseTeams" :key="i" :dataid="baseTeam.id" :label="baseTeam.name" v-model="data.selected_teams"/>

                    <div v-for="error in errors.selected_teams" class="invalid-feedback" style="display:block;">{{ error }}</div>

                    <button type="submit" class="btn btn-primary">Submit</button>

                    <router-link v-bind:to="{name: 'season.index'}">
                        <button class="btn btn-success float-right">一覧に戻る</button>
                    </router-link>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    import InputComponent from '../common/form/InputComponent';
    import CheckboxComponent from '../common/form/CheckboxComponent';
    import MultipleCheckboxComponent from '../common/form/MultipleCheckboxComponent';
    import addMixin from '../../mixins/form/add.js';
    export default {
        components: {InputComponent, CheckboxComponent, MultipleCheckboxComponent},
        mixins : [addMixin],
        data: function () {
            return {
                data: {
                    'selected_teams' : []
                },
                errors: {},
                baseTeams: {}
            }
        },
        methods: {
            getBaseTeams() {
                axios.get('/api/base-teams')
                    .then((res) => {
                        this.baseTeams = res.data;
                    });
            }
        },
         mounted() {
            this.getBaseTeams();
        }
    }
</script>
