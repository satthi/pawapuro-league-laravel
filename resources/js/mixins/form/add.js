export default {
    methods: {
        submit(postPath, redirectPath) {
            this.errors = [];
            axios.post(postPath, this.data)
                .then((res) => {
                    this.$router.push({name: redirectPath});
                })
                .catch((error) => {
                    this.errors = error.response.data.errors;
                });
        }
    }
}