<template>
<div>
    <navbar></navbar>
    <sidebar></sidebar>
        <router-view></router-view>
    <footerbar></footerbar>
    <asides></asides>
</div>
</template>

<script>
    import { mapActions } from 'vuex';
    import Sidebar from './admin/common/Sidebar.vue';
    import FooterBar from './admin/common/FooterBar.vue';
    import Asides from './admin/common/Asides.vue';
    import Navbar from './admin/common/Navbar.vue';
    export default {
        components: {
            'navbar': Navbar,
            'asides': Asides,
            'sidebar': Sidebar,
            'footerbar': FooterBar
        },
    }
</script>
