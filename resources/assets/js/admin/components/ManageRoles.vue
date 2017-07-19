<template>
    <div>
        <h3 class="heading">Manage roles</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Label</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="role in roles">
                    <td>{{ role.name }}</td>
                    <td>{{ role.label }}</td>
                    <td>
                        <a href="#" @click="edit(role)">
                            <p class="icon is-small">
                                <i class="fa fa-gear"></i>
                            </p>
                        </a>
                    </td>
                </tr>
            </tbody>
        </table>

        <modal :active="editing" @hide="editing = false"></modal>
    </div>
</template>

<script>
export default {
    mounted() {
        this.getRoles();
    },
    data() {
        return {
            editing: false,
            roles: [],
            selectedRole: null,
        };
    },
    methods: {
        getRoles() {
            axios.get('/api/role')
                .then(response => this.roles = response.data.roles)
                .catch(error => flash(error.message, 'danger'));
        },
        edit(role) {
            this.selectedRole = role;
            this.editing = true;
        }
    },
};
</script>
