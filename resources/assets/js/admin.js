import AdminsList from './admin/components/AdminsList';
import CreateAdmin from './admin/components/CreateAdmin';
import ManageRoles from './admin/components/ManageRoles';
import CreateRole from './admin/components/CreateRole';

const app = new Vue({
    el: '#admin',
    components: {
        AdminsList,
        CreateAdmin,
        ManageRoles,
        CreateRole,
    }
});
