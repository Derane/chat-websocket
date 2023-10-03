<script>
import Main from '@/Layouts/Main.vue';

export default {
    name: "Index",

    props: [
        'users'
    ],

    layout: Main,
    methods: {
        store(id) {
            this.$inertia.post(route('chats.store'), {
                users: [id]
            })
        },
    },
}
</script>

<template>
    <div class="flex">
        <div class="w-1/2 p-4 mr-4 bg-white border border-gray-300">
            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Atque aut dignissimos eius
            et eum ex illo impedit modi officia
            omnis, porro, praesentium qui repellendus repudiandae sint, sunt tempora vel voluptatibus.
        </div>
        <div class="w-1/2 p-4  bg-white border border-gray-300">
            <h3 class="text-yellow-950 mb-4">Users</h3>
            <div v-for="user in users.data" :key="user.id" class="items-center flex pb-2 mb-2 border-b border-gray-300">
                <p class="mr-2">ID: {{ user.id }}</p>
                <p class="mr-4">Name: {{ user.name }}</p>
                <a @click.prevent="store(user.id)"
                   class="inline-block bg-indigo-600 text-white text-xs px-3 py-2 rounded-lg" href="#">Message</a>
            </div>
        </div>

    </div>
</template>

<style scoped>

</style>
