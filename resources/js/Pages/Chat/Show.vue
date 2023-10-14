<script>
import Main from '@/Layouts/Main.vue';

export default {
    name: "Show",

    props: [
        'chat',
        'users'
    ],
    methods : {
        store() {
            axios.post('/messages', {})
        }
        },
    data() {
      return {
          body: '',
      }
    },
    layout: Main,

}
</script>

<template>
    <div class="flex">
        <div class="w-3/4 p-4 mr-4 bg-white border border-gray-300">
            <h3 class="text-yellow-950 mb-4 text-lg">{{ chat.title ?? 'Your chat' }}</h3>
            <div class="mb-4">

            </div>
            <div>
                <h3 @click.prevent="store" class="text-yellow-950 mb-4 text-lg">Send message</h3>
                <div>
                    <div class="mb-4">
                    <input placeholder="message" class="rounded-full border border-gray-300" type="text" v-model="body">
                    </div>
                    <div>
                        <a class="inline-block bg-indigo-600 text-white text-xs px-3 py-2 rounded-lg" href="#">Send</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="w-1/4 p-4  bg-white border border-gray-300">
            <h3 class="text-yellow-950 mb-4 text-lg">Users</h3>
            <div v-for="user in users" class="items-center flex pb-2 mb-2 border-b border-gray-300">
                <p class="mr-2">ID: {{ user.id }}</p>
                <p class="mr-4">Name: {{ user.name }}</p>
            </div>
        </div>
        </div>

</template>

<style scoped>

</style>
