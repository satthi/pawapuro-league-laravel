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
                    if (res.data.save) {
                        this.$router.push({name: redirectPath});
                    } else {
                        this.errors = res.data.errorMessages;
                        console.log(this.errors);
                    }
                });
        }
    }
}
