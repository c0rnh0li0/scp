<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Admin home</div>

                    <div class="card-body">
                        I'm an admin component.
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'Dashboard',
        mounted() {
            console.log('Admin Dashboard Component mounted.')
        }
    }
</script>
