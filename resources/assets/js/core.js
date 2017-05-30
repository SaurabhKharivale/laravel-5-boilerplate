require('./bootstrap');

Vue.component('notifier', require('./core/components/Notification/Notifier.vue'));

new Vue({
    el: '#register-form',
    data: {
        form: new Form({
            first_name: '',
            last_name: '',
            email: '',
            mobile: '',
            password: '',
            password_confirmation: '',
        })
    },
    methods: {
        onSubmit() {
            this.form.submit('post', '/register')
                .then((response) => {
                    window.location = response.redirect_to;
                })
                .catch((error) => {
                    flash('OOPS! Try again.', 'danger');
                });
        },
    },
});

new Vue({
    el: '#login-form',
    data: {
        form: new Form({
            email: '',
            password: '',
        })
    },
});

new Vue({
    el: '#flash-notification',
});
