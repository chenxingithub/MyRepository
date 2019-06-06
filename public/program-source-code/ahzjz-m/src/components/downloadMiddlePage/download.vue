<template>
    <div class="download">

    </div>
</template>
<script>
import { mapState } from 'vuex'
import { judgeClientOs, isWeiXin } from '../../utils/index.js'
export default {
    name: 'download',
    computed: {
        ...mapState([
            'baseInfo'
        ])
    },
    mounted: function() {
        console.log(this.baseInfo)
    }
}
</script>
<style lang="scss" scoped>
body,html{
    background: #ffffff;
}
</style>
