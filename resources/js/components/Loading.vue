<template>
    <div class="lds-circle">
        <div>
            <img src="/img/skopje-logo-b.png" alt="">
        </div>
    </div>
</template>

<script>
    export default {
        name: "Loading"
    }
</script>

<style scoped>
    .lds-circle {
        z-index: 99999;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        transform: -webkit-translate(-50%, -50%);
        transform: -moz-translate(-50%, -50%);
        transform: -ms-translate(-50%, -50%);
    }
    .lds-circle > div {
        display: inline-block;
        width: 256px;
        height: 256px;
        background: #fff;
        animation: lds-circle 2.4s cubic-bezier(0, 0.2, 0.8, 1) infinite;
    }
    @keyframes lds-circle {
        0%, 100% {
            animation-timing-function: cubic-bezier(0.5, 0, 1, 0.5);
        }
        0% {
            transform: rotateY(0deg);
        }
        50% {
            transform: rotateY(180deg);
            animation-timing-function: cubic-bezier(0, 0.5, 0.5, 1);
        }
        100% {
            transform: rotateY(360deg);
        }
    }
</style>