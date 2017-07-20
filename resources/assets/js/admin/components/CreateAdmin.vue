<template>
    <div>
        <button class="create-new-admin button" @click="creating = true">Create new admin</button>

        <modal :active="creating" @hide="creating = false">
            <form class="form">
                <div class="field">
                    <h1 class="title">New admin details</h1>
                </div>
                <div class="field">
                    <p class="control">
                        <input v-model="form.first_name" name="first_name" placeholder="First name" autofocus
                                class="input" :class="{'is-danger': form.errors.has('first_name')}">
                    </p>
                    <p class="help is-danger" v-if="form.errors.has('first_name')">
                        {{ form.errors.get('first_name') }}
                    </p>
                </div>
                <div class="field">
                    <p class="control">
                        <input v-model="form.last_name" name="last_name" placeholder="Last name"
                                class="input" :class="{'is-danger': form.errors.has('last_name')}">
                    </p>
                    <p class="help is-danger" v-if="form.errors.has('last_name')">
                        {{ form.errors.get('last_name') }}
                    </p>
                </div>
                <div class="field">
                    <p class="conctrol">
                        <input v-model="form.email" name="email" placeholder="Email"
                                class="input" :class="{'is-danger': form.errors.has('email')}">
                    </p>
                    <p class="help is-danger" v-if="form.errors.has('email')">
                        {{ form.errors.get('email') }}
                    </p>
                </div>
                <div class="field">
                    <p class="control">
                        <button class="save-admin button is-primary" @click.prevent="submit">Save</button>
                    </p>
                </div>
            </form>
        </modal>
    </div>
</template>

<script>
export default {
    data() {
        return {
            creating: false,
            form: new Form({
                first_name: '',
                last_name: '',
                email: '',
            })
        };
    },
    methods: {
        submit() {
            this.form.submit('post', '/api/admin')
                .then((response) => {
                    this.$store.commit('addAdmin', response.admin);
                    this.creating = false;
                    flash(response.message, response.type);
                })
                .catch((error) => flash('Admin creation failed. Please Try again.', 'danger'));
        }
    }
}
</script>
