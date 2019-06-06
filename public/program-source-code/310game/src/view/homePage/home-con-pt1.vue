<template>
    <div class="home-con-pt1">
        <div class="con-pt1-l">
            <fun-list></fun-list>
            <game-gift></game-gift>
        </div>
        <div class="con-pt1-r">
            <game-show-list></game-show-list>
        </div>
    </div>
</template>
<script>
import funList from './funList'
import gameGift from './gameGift'
import gameShowList from './gameShowList'

export default {
    name: 'homeContentPart1',
    components: {
        funList,
        gameGift,
        gameShowList
    }
}
</script>
<style lang="scss">
.home-con-pt1{
    width: 1200px;
    background: #ffffff;
    height: 667px;
    margin: 0 auto;
    border: 1px solid #ebebeb;
    box-sizing: border-box;
    .con-pt1-l{
        position: absolute;
        top: 0;
        left: 0;
        width: 330px;
        border-right: 1px solid #ebebeb;
    }
    .con-pt1-r{
        margin-left: 330px;
        // background: #fff88f;
    }
}
</style>
