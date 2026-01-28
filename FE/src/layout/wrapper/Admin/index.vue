<template>
    <div class="admin-layout">
        <!-- Sidebar -->
        <MenuRocker />

        <!-- Main content -->
        <div class="main">
            <TopRocker />

            <div class="content">
                <router-view />
            </div>

            <BotRocker />
        </div>
    </div>
</template>


<script>
import MenuRocker from '@/layout/components/Admin/MenuRocker.vue'
import TopRocker from '@/layout/components/Admin/TopRocker.vue'
import BotRocker from '@/layout/components/Admin/BotRocker.vue'

export default {
    name: "AdminLayout",
    components: {
        TopRocker,
        MenuRocker,
        BotRocker,
    },
};

</script>

<style scoped>
.admin-layout {
    display: flex;
    height: 100vh;
    background: #f5f7fa;
}

.main {
    flex: 1;
    display: flex;
    flex-direction: column;
}

.content {
    flex: 1;
    padding: 24px;
    overflow-y: auto;
}
</style>