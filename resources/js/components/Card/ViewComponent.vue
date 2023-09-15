<template>
    <div class="container">
        <div class="card_omote">
            <card-omote-component :data="dataset" />
        </div>
        <div class="card_ura">
            <card-ura-component :data="dataset" />
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
                        this.dataset = res.data;
                    });
            },
        },
        props: {
            playerId: String
        },
        data: function () {
            return {
                dataset: {}
            }
        },
        mounted() {
            this.initial();
        }
    }
</script>
