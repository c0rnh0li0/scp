<template>
    <tr class="">
        <td class="">{{ props.item.name }}</td>
        <td class="">{{ props.item.duration }} Months</td>
        <td class="">{{ props.item.price }} {{ props.item.valute.name }}</td>
    </tr>
</template>

<script>
    export default {
        name: "List",
        props: ['props']
    }
</script>

<style scoped>

</style>