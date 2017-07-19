<template>
    <div>
        <form class="form" v-show="creating" @submit.prevent="add">
            <div class="field">
                <p class="control">
                    <input class="input" v-model="form.name" placeholder="Name">
                </p>
                <p class="help is-danger" v-if="form.errors.has('name')">
                    {{ form.errors.get('name') }}
                </p>
            </div>
            <div class="field">
                <p class="control">
                    <input class="input" v-model="form.label" placeholder="Label">
                </p>
                <p class="help is-danger" v-if="form.errors.has('label')">
                    {{ form.errors.get('label') }}
                </p>
            </div>
            <div class="field">
                <p class="control">
                    <textarea class="textarea" v-model="form.description" placeholder="Description"></textarea>
                </p>
                <p class="help is-danger" v-if="form.errors.has('description')">
                    {{ form.errors.get('description') }}
                </p>
            </div>
            <div class="field">
                <p class="control">
                    <button class="button is-fullwidth is-primary">Add</button>
                </p>
            </div>
        </form>
        <button class="button is-fullwidth" @click="creating = true" v-if="! creating">Create role</button>
    </div>
</template>

<script>
export default {
    data() {
        return {
            creating: false,
            form: new Form({
                name: '',
                label: '',
                description: '',
            }),
        };
    },
    methods: {
        add() {
            this.form.submit('post', '/api/role')
                .then(response => flash(response.message, response.type))
                .catch(error => flash('Role creation failed.', 'danger'));
        }
    }
}
</script>
