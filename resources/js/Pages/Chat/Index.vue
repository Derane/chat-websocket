<script>
import {Link} from "@inertiajs/vue3";

export default {
    name: "Index",

    props: [
        'users',
        'chats'
    ],
    components: {
        Link
    },
    data() {
        return {
            isGroup: false,
            userIds: [],
            title: ''
        }
    },
    created() {
        window.Echo.private(`users.${this.$page.props.auth.user.id}`)
            .listen('.store-message-status', res => {
                this.chats.filter(chat => {
                    if (chat.id === res.chat_id) {
                        console.log(res);
                        chat.unreadable_count++
                    }
                })
            })
    },
    methods: {
        store(id) {
            this.$inertia.post(route('chats.store'), {
                users: [id],
                title: null,
            })
        },
        storeGroup() {
            this.$inertia.post(route('chats.store'), {
                users: this.userIds,
                title: this.title,
            })
        },
        toggleUsers(id) {
            let index = this.userIds.indexOf(id);
            if (index === -1) {
                this.userIds.push(id);
            } else {
                this.userIds.splice(index, 1);
            }
            console.log(this.userIds);
        },
        refreshUserIds() {
            this.userIds = []
            this.isGroup = false
        }
    },


}
</script>

<template>
    <div class="flex">
        <h3 class="text-yellow-950 mb-4">Chats</h3>
        <div v-if="chats" class="w-1/2 p-4 mr-4 bg-white border border-gray-300">
            <div v-for="chat in chats" class="pb-2 mb-2 border-b border-gray-300">
                <Link :href="route('chats.show', chat.id)">
                    <div>
                        <div>
                            <div class="flex">
                                <p class="mr-2">{{ chat.id }}</p>
                                <p>{{ chat.title ?? "Your chat" }}</p>
                            </div>
                            <div :class="['-2 flex justify-between items-center',
                           chat.unreadable_count !== 0 ? 'bg-sky-50' : '']">
                                <div class="text-sm">
                                    <p class="text-gray-600">{{ chat.last_message.user_name }}</p>
                                    <p class="mb-2 text-gray-500">{{ chat.last_message.body }}</p>
                                    <p class="italic text-gray-400">{{ chat.last_message.time }}</p>
                                </div>
                                <div v-if="chat.unreadable_count !== 0">
                                    <p class="text-xs rounded-full bg-sky-500 text-white px-2 py-1">
                                        {{ chat.unreadable_count }}</p>
                                </div>
                            </div>
                        </div>

                    </div>
                </Link>
            </div>
        </div>
        <div class="w-1/2 p-4  bg-white border border-gray-300">
            <div class="flex items-center mb-4 justify-between">
                <h3 class="text-yellow-950 ">Users</h3>
                <a v-if="!isGroup" @click.prevent="isGroup = true"
                   class="inline-block bg-indigo-600 text-white text-xs px-3 py-2 rounded-lg" href="#">Make group</a>
                <div v-if="isGroup">
                    <input class="h-8 mr-4 border border-green-200 rounded-full inline-block" type="text"
                           placeholder="group title" v-model="title">
                    <a @click.prevent="storeGroup"
                       class="inline-block mr-2 bg-green-600 text-white text-xs px-3 py-2 rounded-lg" href="#">Go
                        chat</a>
                    <a @click.prevent="refreshUserIds"
                       class="inline-block bg-indigo-600 text-white text-xs px-3 py-2 rounded-lg" href="#">X</a>
                </div>

            </div>
            <div v-for="user in users.data" :key="user.id" class="items-center flex pb-2 mb-2 border-b border-gray-300">
                <div class="items-center flex">
                    <p class="mr-2">ID: {{ user.id }}</p>
                    <p class="mr-4">Name: {{ user.name }}</p>
                    <a @click.prevent="store(user.id)"
                       class="inline-block bg-indigo-600 text-white text-xs px-3 py-2 rounded-lg" href="#">Message</a>
                </div>
                <div v-if="isGroup" class="ml-1">
                    <input @click.prevent=toggleUsers(user.id) type="checkbox">
                </div>
            </div>
        </div>

    </div>
</template>

<style scoped>

</style>
