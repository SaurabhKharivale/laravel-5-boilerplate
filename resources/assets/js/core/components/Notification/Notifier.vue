<template>
    <div class="notification-container">
        <flash v-for="(message, index) in messages" :key="message.id"
                :message="message.text" :type="message.type"
                @remove="removeMessage(index, message.id)">
        </flash>
    </div>
</template>

<script>
import Flash from './Flash.vue';
export default {
    props: {
        multiple: {
            type: Boolean,
            default:false
        },
        message: [String, Number],
        type: {
            type: String,
            default: ''
        },
    },
    components: {
        Flash
    },
    data() {
        return {
            id: 1,
            messages: [],
        };
    },
    created() {
        this.boot();
        this.setupFlashListner();
    },
    methods: {
        boot() {
            if(this.message) {
                this.addMessage({'text': this.message, 'type': this.type, 'id': this.id });
            }
        },
        setupFlashListner() {
            window.events.$on('flash',(message, type) => {
                this.updateMessageId();
                this.addMessage({'text': message, 'type': type, 'id': this.id });
            });
        },
        addMessage(message) {
            if(this.multiple){
                this.messages.unshift(message);
                return;
            }

            this.messages = [message];
        },
        removeMessage(index, id) {
            this.messages.splice(index, 1);
        },
        updateMessageId() {
            this.id ++;
        }
    }
};
</script>

<style>
.notification-container {
    max-width: 500px;
    position: fixed;
    top: 100px;
    right: 25px;
}
</style>
