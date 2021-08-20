export default {
    methods: {
        submit(postPath, redirectPath) {
            this.errors = [];
            axios.post(postPath, this.data)
                .then((res) => {
                    if (res.data.save) {
                        this.$router.push({name: redirectPath});
                    } else {
                        this.errors = res.data.errorMessages;
                    }
                });
        }
    }
}