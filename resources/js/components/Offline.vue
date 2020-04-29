<template>
    <div>
        <header class="offline-header">
            <div class="container">
                <figure class="offline-logo animated fadeInDown delay-07s">
                    <a href="#"><img src="/img/skopje-logo-w.png" alt=""></a>
                </figure>
                <h1 class="animated fadeInDown delay-07s">It seems like you are OFFLINE...</h1>
                <ul class="we-create animated fadeInUp delay-1s">
                    <li>
                        Sorry, this page is not available while you are offline.<br />
                        Please try again once you are back online.
                    </li>
                </ul>
            </div>
        </header>
    </div>
</template>

<script>
    export default {
        name: "Offline"
    }
</script>

<style scoped>
    .offline-header{
        text-align:center;
        background: rgb(0,36,71);
        background: -moz-linear-gradient(0deg, rgba(0,36,71,1) 0%, rgba(0,109,195,1) 100%);
        background: -webkit-linear-gradient(0deg, rgba(0,36,71,1) 0%, rgba(0,109,195,1) 100%);
        background: linear-gradient(0deg, rgba(0,36,71,1) 0%, rgba(0,109,195,1) 100%);
        filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="#002447",endColorstr="#006dc3",GradientType=1);
        padding:280px 0;
    }
    .offline-logo{
        width:130px;
        margin:0 auto 35px;
    }
    .offline-header h1{
        font-family: 'Montserrat',sans-serif;
        font-size:50px;
        font-weight:400;
        letter-spacing: -1px;
        margin:0 0 22px 0 ;
        color:#fff;
    }
</style>