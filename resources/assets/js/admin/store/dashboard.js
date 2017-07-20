const dashboard = {
    state: {
        admins: [],
        roles: [],
    },
    mutations: {
        setAdmins(state, admins) {
            state.admins = admins;
        },
        setRoles(state, roles) {
            state.roles = roles;
        },
        addAdmin(state, admin) {
            state.admins.push(admin);
        },
        addRole(state, role) {
            state.roles.push(role);
        }
    },
    actions: {
        loadAdmins({ commit }) {
            axios.get('/api/admin')
                .then(response => commit('setAdmins', response.data.admins))
                .catch(error => console.log('Unable to fetch list of admins.'));
        },
        loadRoles({ commit }) {
            axios.get('/api/role')
                .then(response => commit('setRoles', response.data.roles))
                .catch(error => console.log('Unable to fetch roles.'));
        },
        initialize({ dispatch }) {
            dispatch('loadAdmins');
            dispatch('loadRoles');
        }
    }
};

export default dashboard;
