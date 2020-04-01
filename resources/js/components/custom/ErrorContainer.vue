<template>
    <div class="error--text">
        <div class="v-messages">
            <div v-if="message">{{ message[0] }}</div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "ErrorContainer",
        props: ['message'],
        watch: {
            message(newVal, oldVal) {
                this.message = newVal
            },
        },
        data: () => ({})
    }
</script>

<style scoped>

</style>