export default {
    methods: {
        submit(postPath, redirectRoute) {
            this.errors = [];
            console.log(this.data)
            axios.post(postPath, this.data)
                .then((res) => {
                    this.$router.push(redirectRoute);
                })
                .catch((error) => {
                    this.errors = error.response.data.errors;
                });
        }
    }
}