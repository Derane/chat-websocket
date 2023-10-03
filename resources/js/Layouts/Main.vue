<script>
import {Link} from "@inertiajs/vue3";

export default {
    components: {
        Link
    },

}
</script>

<template>
    <div class="p-4 mb-4 w-3/4 mx-auto">
        <div>
            <div>
                <Link :href="route('chats.index')">Chats</Link>
            </div>
        </div>
        <slot/>
    </div>
</template>

<style scoped>

</style>
