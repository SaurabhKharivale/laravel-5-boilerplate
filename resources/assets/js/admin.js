import AdminsList from './admin/components/AdminsList';
import CreateAdmin from './admin/components/CreateAdmin';
import ManageRoles from './admin/components/ManageRoles';
import CreateRole from './admin/components/CreateRole';
import store from './admin/store/index.js';

const app = new Vue({
    el: '#admin',
    store,
    components: {
        AdminsList,
        CreateAdmin,
        ManageRoles,
        CreateRole,
    },
    mounted() {
        this.$store.dispatch('initialize');
    }
});
