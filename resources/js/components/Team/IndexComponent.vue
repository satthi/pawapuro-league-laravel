<template>
    <div class="container">
        <div class="clearfix">
            <router-link v-bind:to="{name: 'team.add'}">
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

                <tr v-for="(team, index) in teams" :key="index">
                    <td scope="row">{{ team.name }}</td>
                    <td>{{ team.ryaku_name }}</td>
                    <td>
                        <router-link v-bind:to="{name: 'team.edit', params: {teamId: team.id.toString() }}">
                            <button class="btn btn-success">Edit</button>
                        </router-link>
                    </td>
                    <td>
                        <button class="btn btn-danger" v-on:click="deleteTeam(team.id)">Delete</button>
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
                teams: []
            }
        },
        methods: {
            getTeams() {
                axios.get('/api/teams')
                    .then((res) => {
                        this.teams = res.data;
                    });
            },
            deleteTeam(id) {
                axios.delete('/api/teams/' + id)
                    .then((res) => {
                        this.getTeams();
                    });
            }
        },
         mounted() {
            this.getTeams();
        }
    }
</script>
