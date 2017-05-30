<template>
    <div class="notification" :class="status" v-show="show">
        <button class="delete" @click="show = false"></button>
        {{ body }}
    </div>
</template>

<script>
export default {
    props: {
        message: {
            type: [String, Number],
            required: true
        },
        type: {
            type: String,
            default: ''
        }
    },
    data() {
        return {
            body: '',
            status_type: '',
            show: false,
        };
    },
    computed: {
        status() {
            if(_.isEmpty(this.status_type)) return;

            return 'is-' + this.status_type;
        }
    },
    created() {
        if(this.message) {
            this.flash(this.message, this.type);
        }
    },
    methods: {
        flash(message, type = '') {
            this.body = message;
            this.status_type = type;
            this.show = true;

            this.hide();
        },
        hide() {
            setTimeout(() => {
                this.show = false;
                this.$emit('remove', this);
            }, 4000);
        }
    }
};
</script>
