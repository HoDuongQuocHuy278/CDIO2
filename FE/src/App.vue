<template>
    <component :is="layoutComponent">
        <router-view />
    </component>
</template>

<script>
import AdminLayout from '@/layout/wrapper/Admin/index.vue'

const default_layout = 'default'

export default {
    computed: {
        layoutComponent() {
            if (this.$route.meta.layout === 'AdminLayout') {
                return AdminLayout
            }
            return 'div'
        }
    }
}
</script>
