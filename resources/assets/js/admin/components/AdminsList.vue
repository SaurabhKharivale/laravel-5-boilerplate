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
            <manage-admin-roles v-if="selectedAdmin" :admin="selectedAdmin"></manage-admin-roles>
        </modal>
    </div>
</template>

<script>
import ManageAdminRoles from './ManageAdminRoles';

export default {
    components: {
        ManageAdminRoles,
    },
    data() {
        return {
            error: false,
            managing: false,
            selectedAdmin: null,
        };
    },
    computed: {
        admins () {
            return this.$store.state.dashboard.admins;
        }
    },
    methods: {
        manage(admin) {
            this.selectedAdmin = admin;
            this.managing = true;
        }
    }
}
</script>
