<template>
    <div>
        <tickets></tickets>
        <offers :from-dashboard="true"></offers>
    </div>
</template>

<script>
    import Tickets from './Tickets'
    import Offers from './Offers'

    export default {
        components: {
            Tickets,
            Offers
        },
        created() {
            console.log('tourist Dashboard Component mounted.')
        }
    }
</script>
