<template>
    <div id="entryPage">
        <Entry-header></Entry-header>
        <entry-main></entry-main>
        <publishFirst-welfare></publishFirst-welfare>
        <game-feature></game-feature>
        <enrty-footer></enrty-footer>
    </div>
</template>
<script>
import EntryHeader from './header'
import entryMain from './main'
import publishFirstWelfare from './publishFirst-welfare'
import gameFeature from './game-feature'
import enrtyFooter from './footer'

export default {
    name: 'entryPage',
    components: {
        EntryHeader,
        entryMain,
        publishFirstWelfare,
        gameFeature,
        enrtyFooter
    }
}
</script>
<style lang="scss">
#entryPage{
    width: 750px;
    height: auto;
    padding-top: 100px;
}
</style>
