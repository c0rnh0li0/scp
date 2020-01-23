<template>
    <div>
        <div class="container">
            <nav>
                <router-link to="/login">Login</router-link>
                <router-link to="/register">Register</router-link>
            </nav>

            <div class="flex-center position-ref full-height">
                <div class="content">
                    <div class="title m-b-md">
                        SCP
                    </div>

                    <div class="links">
                        <a href="#">UNDER CONSTRUCTION</a>
                    </div>
                </div>
                <router-view></router-view>
            </div>
        </div>
    </div>

</template>

<script>
    export default {
        name: "Index"
    }
</script>

<style scoped>

</style>