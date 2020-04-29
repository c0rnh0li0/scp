<template>
    <div class="lds-overlay" id="loading-overlay" v-if="displayOverlay">
        <div class="lds-circle">
            <img class="ids-image" src="/img/skopje-logo-b.png" alt="">
        </div>
    </div>
</template>

<script>
    export default {
        name: "Loading",
        props: ['isloading'],
        watch: {
            isloading(newVal, oldVal) {
                let fadeClass = newVal ? 'fade-in' : 'fade-out'
                $('#loading-overlay').addClass(fadeClass)

                if (!newVal) {
                    let that = this
                    setTimeout(() => {
                        that.displayOverlay = false
                    }, 990)
                }
                else {
                    this.displayOverlay = true
                }
            }
        },
        data: () => ({
            displayOverlay: true
        }),
    }
</script>

<style scoped>
    .lds-overlay {
        background-color:rgba(255, 255, 255, 1);
        position: fixed;
        z-index: 99998;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        overflow: hidden;
    }
    .lds-circle {
        z-index: 99999;
        position: fixed;
        top: 50%;
        left: 50%;
        margin: -128px auto auto -128px;
        -webkit-animation: rotateY 2s infinite linear;
        animation: rotateY 2s infinite linear;
    }
    .ids-image {
        width: 256px;
        height: 256px;
    }
    @-webkit-keyframes rotateY {
        to { -webkit-transform: rotateY(360deg); }
    }
    @keyframes rotateY {
        to { transform: rotateY(360deg); }
    }

    .fade-in {
        animation: fadeIn ease .2s;
        -webkit-animation: fadeIn ease .2s;
        -moz-animation: fadeIn ease .2s;
        -o-animation: fadeIn ease .2s;
        -ms-animation: fadeIn ease .2s;
    }
    @keyframes fadeIn {
        0% {
            opacity:0;
        }
        100% {
            opacity:1;
        }
    }

    @-moz-keyframes fadeIn {
        0% {
            opacity:0;
        }
        100% {
            opacity:1;
        }
    }

    @-webkit-keyframes fadeIn {
        0% {
            opacity:0;
        }
        100% {
            opacity:1;
        }
    }

    @-o-keyframes fadeIn {
        0% {
            opacity:0;
        }
        100% {
            opacity:1;
        }
    }

    @-ms-keyframes fadeIn {
        0% {
            opacity: 0;
        }
        100% {
            opacity: 1;
        }
    }

    .fade-out {
        animation: fadeOut ease 1s;
        -webkit-animation: fadeOut ease 1s;
        -moz-animation: fadeOut ease 1s;
        -o-animation: fadeOut ease 1s;
        -ms-animation: fadeOut ease 1s;
    }
    @keyframes fadeOut {
        0% {
            opacity:1;
        }
        100% {
            opacity:0;
        }
    }

    @-moz-keyframes fadeOut {
        0% {
            opacity:1;
        }
        100% {
            opacity:0;
        }
    }

    @-webkit-keyframes fadeOut {
        0% {
            opacity:1;
        }
        100% {
            opacity:0;
        }
    }

    @-o-keyframes fadeOut {
        0% {
            opacity:1;
        }
        100% {
            opacity:0;
        }
    }

    @-ms-keyframes fadeOut {
        0% {
            opacity: 1;
        }
        100% {
            opacity: 0;
        }
    }
</style>