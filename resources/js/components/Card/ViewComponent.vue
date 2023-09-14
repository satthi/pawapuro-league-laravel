<template>
    <div class="container">
        <div class="card_omote">
            <card-omote-component :data={data} />
        </div>
        <div class="card_ura">
            <card-ura-component :data={data} />
        </div>
    </div>
</template>

<script>
    import CardOmoteComponent from '../common/card/OmoteComponent';
    import CardUraComponent from '../common/card/UraComponent';
    export default {
        components: {CardOmoteComponent, CardUraComponent},
        methods: {
            initial() {
                this.getData('/api/players/view/' + this.playerId);
            },
            getData(getPath) {
                axios.get(getPath)
                    .then((res) => {
                    console.log(res)
                        this.data = res.data;
                    });
            },
        },
        props: {
            playerId: String
        },
        data: function () {
            return {
                data: {}
            }
        },
        mounted() {
            this.initial();
        }
    }
</script>
