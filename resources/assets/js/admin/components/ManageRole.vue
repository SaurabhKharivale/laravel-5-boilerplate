<template>
    <div>
        <p>Admin: {{ name }}</p>
        <div class="field">
            <span v-for="role in admin.roles" class="tag is-medium">
                {{ role.name }}
                <a class="delete is-small" @click="remove(role)"></a>
            </span>
        </div>
        <div class="field" v-if="availableRoles.length > 0">
            <p class="control">
                <span class="select">
                    <select name="role_id" v-model="role">
                        <option value="" disabled selected>Select a role</option>
                        <option v-for="role in availableRoles" :value="role.id">{{ role.label }}</option>
                    </select>
                </span>
            </p>
        </div>
        <div class="field" v-if="availableRoles.length > 0">
            <p class="control">
                <button class="assign-role button is-primary" @click="assign">Assign</button>
            </p>
        </div>
    </div>
</template>

<script>
export default {
    props: ['admin'],
    data() {
        return {
            roles: [],
            role: null,
        };
    },
    mounted() {
        axios.get('/api/role')
            .then(response => this.roles = response.data.roles);
    },
    computed: {
        name() {
            return this.admin.first_name + ' ' + this.admin.last_name;
        },
        availableRoles() {
            return this.roles.filter(role => ! _.find(this.admin.roles, role));
        },
        url() {
            return '/api/admin/' + this.admin.id + '/role';
        }
    },
    methods: {
        assign() {
            axios.post(this.url, {'role_id': this.role})
                .then(response => flash('New role assined to ' + this.name))
                .catch(error => flash(error.message));
        },
        remove(role) {
            axios.delete(this.url, {params: {'role_id': role.id}})
                .then(response => flash(response.data.message))
                .catch(error => flash(error.message));
        }
    }
}
</script>
