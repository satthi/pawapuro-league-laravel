<template>
    <div class="container">
        <div class="clearfix">
            <router-link v-bind:to="{name: 'base-team.add'}">
                <button class="btn btn-success float-right">追加</button>
            </router-link>
        </div>
        <table class="table table-hover">
            <thead class="thead-light">
            <tr>
                <th scope="col">チーム名</th>
                <th scope="col">略称</th>
                <th scope="col">Edit</th>
                <th scope="col">Delete</th>
            </tr>
            </thead>
            <tbody>

                <tr v-for="(baseTeam, index) in baseTeams" :key="index">
                    <td scope="row">{{ baseTeam.name }}</td>
                    <td>{{ baseTeam.ryaku_name }}</td>
                    <td>
                        <router-link v-bind:to="{name: 'base-team.edit', params: {baseTeamId: baseTeam.id.toString() }}">
                            <button class="btn btn-success">Edit</button>
                        </router-link>
                    </td>
                    <td>
                        <button class="btn btn-danger" v-on:click="deleteBaseTeam(baseTeam.id)">Delete</button>
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
                baseTeams: []
            }
        },
        methods: {
            getBaseTeams() {
                axios.get('/api/base-teams')
                    .then((res) => {
                        this.baseTeams = res.data;
                    });
            },
            deleteBaseTeam(id) {
                axios.delete('/api/base-teams/' + id)
                    .then((res) => {
                        this.getBaseTeams();
                    });
            }
        },
         mounted() {
            this.getBaseTeams();
        }
    }
</script>
