<template>
    <div>
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="admin in admins">
                    <td>{{ admin.first_name }} {{admin.last_name}}</td>
                    <td>{{ admin.email }}</td>
                </tr>
            </tbody>
        </table>
        <p v-show="error">Unable to load admins data</p>
    </div>
</template>

<script>
export default {
    mounted() {
        this.getAll();
    },
    data() {
        return {
            admins: [],
            error: false,
        };
    },
    methods: {
        getAll() {
            axios.get('/api/admin')
                .then((response) => this.admins = response.data.admins)
                .catch((error) => this.error = true);
        }
    }
}
</script>
