<template>
    <div>
        <label>Tasks</label>
        <table>
            <tr>
                <th v-for="row in Object.keys(data[0])">{{ row }}</th>
            </tr>
            <tr v-for="row in data" :key="row.id">
                <td v-for="dat in Object.keys(row)">
                    <!-- @vue-ignore -->
                    {{ row[`${dat}`] ?? 'null' }}
                </td>
            </tr>
        </table>
    </div>
</template>

<script setup lang="ts">
defineProps<{
    data: any[]
}>()
</script>

<style scoped>
table,
th,
td {
    border: 2px solid black;
}
</style>
