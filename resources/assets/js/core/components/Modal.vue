<template>
    <transition name="modal">
        <div v-show="active" class="modal is-active">
            <div class="modal-background" @click="$emit('hide')"></div>
            <div class="modal-content">
                <div class="box">
                    <slot></slot>
                </div>
            </div>
            <button class="modal-close" @click="$emit('hide')"></button>
        </div>
    </transition>
</template>

<script>
export default {
    props: ['active'],
}
</script>
