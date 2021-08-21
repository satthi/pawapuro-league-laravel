export default {
    data: function () {
        return {
            enums: {}
        }
    },
    mounted() {
        axios.get('/api/enums')
            .then((res) => {
                this.enums = res.data;
                console.log(this.enums);
            });
    }
}
