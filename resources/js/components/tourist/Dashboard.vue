<template>
    <div>
        <tickets class="mb-4"></tickets>
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
        created() {}
    }
</script>
