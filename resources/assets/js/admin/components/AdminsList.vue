<template>
    <div>
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="admin in admins">
                    <td>{{ admin.first_name }} {{admin.last_name}}</td>
                    <td>{{ admin.email }}</td>
                    <td>
                        <span v-for="role in admin.roles" class="tag">{{ role.name }}</span>
                    </td>
                    <td>
                        <button class="manage-admin button is-small" @click="manage(admin)">
                            <span class="icon is-small">
                                <i class="fa fa-cog"></i>
                            </span>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
        <p v-show="error">Unable to load admins data.</p>

        <modal :active="managing" @hide="managing = false">
            <manage-role v-if="selectedAdmin" :admin="selectedAdmin"></manage-role>
        </modal>
    </div>
</template>

<script>
import ManageRole from './ManageRole';

export default {
    mounted() {
        this.getAll();
    },
    components: {
        ManageRole,
    },
    data() {
        return {
            admins: [],
            error: false,
            managing: false,
            selectedAdmin: null,
        };
    },
    methods: {
        getAll() {
            axios.get('/api/admin')
                .then((response) => this.admins = response.data.admins)
                .catch((error) => this.error = true);
        },
        manage(admin) {
            this.selectedAdmin = admin;
            this.managing = true;
        }
    }
}
</script>
