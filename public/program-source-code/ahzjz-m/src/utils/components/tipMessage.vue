<template>
    <div v-if="tipModal.status == 'show' ? true : false" class="tipMsg" @touchmove="forbidScroll">
        
        <div class="tip-content">
            <div class="tip-icon">
                <span v-if="tipModal.type == 'success' ? true : false" class="success"></span>
                <span v-else-if="tipModal.type == 'fail' ? true : false" class="fail"></span>
                <!-- <span v-else class="txt-tip">{{ tipModal.msg }}</span> -->
            </div>
            <p class="tip-text">{{tipModal.msg}}</p>
        </div>
    </div>
</template>
<script>
import { mapActions, mapState } from 'vuex';

export default {
    name: 'tipMessage',
    computed: {
        ...mapState([
            'tipModal'
        ])
    },
    methods: {
        forbidScroll(e) {
            e.preventDefault();
        }
    }
}
</script>
<style lang="scss" scoped>
.tipMsg{
    position: fixed;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    background: rgba(0, 0, 0, 0.65);
    z-index: 999999;
    .tip-content{
        min-width: 300px;
        min-height: 150px;
        padding: 0 10px 10px 10px;
        position: absolute;
        top: 48%;
        left: 50%;
        transform: translate(-50%,-50%);
        background: #a2a2ab;
        border-radius: 5%;
        font-size: 28px;
        color: #ffffff;
        text-align: center;
    }
    .tip-icon{
        position: relative;
        text-align: center;
        margin: 15px auto;
        .success{
            height:60px;
            width:60px;
            display:block;
            position: relative;
            margin: 0 auto;
            border-radius:50px;
            border: #ffffff 2px solid;
        }
        .success:after, .success:before{
            content:'';
            height:38px;
            width:6px;
            display:block;
            background:#fff;
            position:absolute;
            top:10px;
            left:34px;
            border-radius:10px;
            transform:rotate(45deg);
        }
        .success:before{
            height:20px;
            transform:rotate(-45deg);
            top:26px;
            left:17px;
        }
        .fail{
            height:60px;
            width:60px;
            display:block;
            position:relative;
            margin: 0 auto;
            border-radius:50px;
            border:#ffffff 2px solid;
        }

        .fail:before, .fail:after{
            content:'';
            height:6px;
            width:40px;
            display:block;
            background:#fff;
            border-radius:10px;
            position:absolute;
            top:26px;
            left:11px;
            transform:rotate(-45deg);
        }

        .fail:after{
            transform:rotate(45deg);
        }
    }
    .tip-text{
        font-size: 28px;
    }
}
</style>
