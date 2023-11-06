<script>
import Main from '@/Layouts/Main.vue';

export default {
    name: "Show",

    props: [
        'chat',
        'users',
        'messages'
    ],
    methods: {
        store() {
            axios.post('/messages', {
                chat_id: this.chat.id,
                body: this.body
            }).then(res => {
                    this.messages.unshift(res.data)
                    this.body = ''
                }
            )
        },
       getMessages() {
            axios.get(`/chats/${this.chat.id}?page=${++this.page}`)
                .then(res => {
                    this.messages.push(...res.data)
                })
       }
    },
    created() {
        window.Echo.channel(`store-message.${this.chat.id}`).listen('.store-message', res => {
            this.messages.push(res.message)
            if(this.$page.url === `/chats/${this.chat.id}`){
                axios.patch('/message_statuses', {
                    user_id: this.$page.props.auth.user.id,
                    message_id: res.message.id
                })
            }
        })
    },
    data() {
        return {
            body: '',
            page: 1,
        }
    },
    layout: Main,

}
</script>

<template>
    <div class="flex">
        <div class="w-3/4 p-4 mr-4 bg-white border border-gray-300">

            <h3 class="text-yellow-950 mb-4 text-lg">{{ chat.title ?? 'Your chat' }}</h3>
            <div v-if="messages" class="mb-4">
                <div class="text-center mb-2">
                    <a @click.prevent="getMessages" href="#" class="inline-block bg-sky-600 text-white text-xs px-3 py-2 rounded-lg">Load more</a>
                </div>
                <div v-for="message in messages.slice().reverse()" :class="['mb-4',
                    message.is_owner ? 'text-right': 'text-left']">
                    <div :class="['p-2 bg-sky-150 border border-sky-100 inline-block',
                    message.is_owner ? 'bg-green-50': 'bg-sky-50 border-sky-100']">
                        <p class="text-sm">{{ message.user_name }}</p>
                        <p class="mb-2">{{ message.body }}</p>
                        <p class="text-xs italic">{{ message.time }}</p>
                    </div>

                </div>
            </div>
            <div>
                <a href="#" @click.prevent="store()" class="text-yellow-950 mb-4 text-lg">Send message</a>
                <div>
                    <div class="mb-4">
                        <input placeholder="message" class="rounded-full border border-gray-300" type="text"
                               v-model="body">
                    </div>
                    <div>
                        <a @click.prevent="store"
                           class="inline-block bg-indigo-600 text-white text-xs px-3 py-2 rounded-lg" href="#">Send</a>
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
