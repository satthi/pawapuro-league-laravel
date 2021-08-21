<template>
    <div class="container">
        <h2>チーム名：{{ team.name }}</h2>
        <div class="clearfix">
            <router-link v-bind:to="{name: 'base-player.add', params: {basePlayerId: baseTeamId.toString() }}">
                <button class="btn btn-success float-right">追加</button>
            </router-link>
        </div>
        <table class="table table-hover">
            <thead class="thead-light">
            <tr>
                <th scope="col">メインP</th>
                <th scope="col">背番号</th>
                <th scope="col">選手名</th>
                <th scope="col">表示名</th>
                <th scope="col">投打</th>
                <th scope="col"></th>
            </tr>
            </thead>
            <tbody>

                <tr v-for="(basePlayer, index) in basePlayers" :key="index">
                    <td scope="row">{{ basePlayer.position_main_text }}</td>
                    <td scope="row">{{ basePlayer.number }}</td>
                    <td scope="row">{{ basePlayer.name }}</td>
                    <td>{{ basePlayer.name_short }}</td>
                    <td>{{ basePlayer.hand_full_text }}</td>
                    <td>
                        <router-link v-bind:to="{name: 'base-player.edit', params: {basePlayerId: basePlayer.id.toString() }}">
                            <button class="btn btn-success">Edit</button>
                        </router-link>
                        <button class="btn btn-danger" v-on:click="deleteBasePlayer(basePlayerId.id)">Delete</button>
                    </td>
                </tr>

            </tbody>
        </table>
    </div>
</template>
<script>
    export default {
        props: {
            baseTeamId: String
        },
        data: function () {
            return {
                basePlayers: {},
                team: {}
            }
        },
        methods: {
            getBasePlayers() {
                axios.get('/api/base-players/' + this.baseTeamId)
                    .then((res) => {
                        this.basePlayers = res.data;
                    });
            },
            deleteBasePlayer(id) {
                axios.delete('/api/base-players/delete/' + id)
                    .then((res) => {
                        this.getBasePlayers();
                    });
            },
            getTeamData(getPath) {
                axios.get(getPath)
                    .then((res) => {
                        this.team = res.data;
                    });
            }
        },
         mounted() {
            this.getBasePlayers();
            this.getTeamData('/api/base-teams/' + this.baseTeamId);
        }
    }
</script>
