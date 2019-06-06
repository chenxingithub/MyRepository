<template>
<div id="app">
<keep-alive exclude="Tinymce">
    <router-view :key="$route.path" v-if="$route.meta.keepAlive"></router-view>
</keep-alive>
<router-view :key="$route.path" v-if="!$route.meta.keepAlive"></router-view>
</div>
</template>


<script>
export default {
  name: 'app'
}
</script>

<style>
#app {

}
</style>
