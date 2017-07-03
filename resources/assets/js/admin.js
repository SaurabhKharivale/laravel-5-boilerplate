import AdminsList from './admin/components/AdminsList';
import CreateAdmin from './admin/components/CreateAdmin';

const app = new Vue({
    el: '#admin',
    components: {
        AdminsList,
        CreateAdmin,
    }
});
