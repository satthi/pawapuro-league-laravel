<template>
    <div class="container">
        <h2>シーズン一覧</h2>
        <div class="clearfix">
            <router-link v-bind:to="{name: 'season.add'}">
                <button class="btn btn-success float-right">追加</button>
            </router-link>
        </div>
        <table class="table table-hover">
            <thead class="thead-light">
            <tr>
                <th scope="col">シーズン名</th>
                <th scope="col"></th>
            </tr>
            </thead>
            <tbody>

                <tr v-for="(season, index) in seasons" :key="index">
                    <td scope="row">{{ season.name }}</td>
                    <td>
                        <router-link v-bind:to="{name: 'season.view', params: {seasonId: season.id.toString() }}">
                            <button class="btn btn-success">詳細</button>
                        </router-link>
                        <router-link v-bind:to="{name: 'season.edit', params: {seasonId: season.id.toString() }}">
                            <button class="btn btn-success">Edit</button>
                        </router-link>
                        <button class="btn btn-danger" v-bind:disabled="!season.is_deletable" v-on:click="deleteSeason(season.id)">Delete</button>
                    </td>
                </tr>

            </tbody>
        </table>
    </div>
</template>
<script>
    export default {
        data: function () {
            return {
                seasons: []
            }
        },
        methods: {
            getSeasons() {
                axios.get('/api/seasons')
                    .then((res) => {
                        this.seasons = res.data;
                    });
            },
            deleteSeason(id) {
                axios.delete('/api/seasons/delete/' + id)
                    .then((res) => {
                        this.getSeasons();
                    });
            }
        },
         mounted() {
            this.getSeasons();
        }
    }
</script>
