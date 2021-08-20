export default {
    methods: {
        getData(getPath) {
            axios.get(getPath)
                .then((res) => {
                    this.data = res.data;
                });
        },
        submit(postPath, redirectPath) {
            this.errors = [];
            axios.put(postPath, this.data)
                .then((res) => {
                    this.$router.push({name: redirectPath});
                })
                .catch((error) => {
                    this.errors = error.response.data.errors;
                });
        }
    }
}