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
    data() {
        return {
            editing: false,
            selectedRole: null,
        };
    },
    computed: {
        roles() {
            return this.$store.state.dashboard.roles;
        }
    },
    methods: {
        edit(role) {
            this.selectedRole = role;
            this.editing = true;
        }
    },
};
</script>
