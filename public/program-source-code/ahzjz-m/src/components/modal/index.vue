<template>
    <div v-if="modalType" class="tip-modal modal-cover" @touchmove="forbidScroll">
        <div :class="['modal-content', modalType == 'wx-code' ? 'wx-code' : 'browser-open-tip']">
            <a @click="closeModal" href="javascript:;" class="modal-close"></a>
            <img v-if="modalType == 'open-browser'  ? true : false" src="../../assets/browser-open-tip.png" alt="image">
            <img v-if="modalType == 'wx-code'  ? true : false" src="../../assets/wx-code.png" alt="image">
            <img v-if="modalType == 'share-friend'  ? true : false" src="../../assets/shareToFriend.png" alt="image">
        </div>
    </div>
</template>
<script>
export default {
    name: 'tipModal',
    props: {
        modalType: String
    },
    methods: {
        closeModal() {
            this.modal_type = '';
            this.$emit('closeModal', '');

        },
        forbidScroll(e) {
            e.preventDefault();
        }
    }
}
</script>
<style lang="scss" scope>
.tip-modal{
    .browser-open-tip{
        width: 504px;
        height: 270px;
        img{
            width: 504px;
            height: 270px;
        }
    }
    .wx-code{
        width: 504px;
        height: 430px;
        img{
            width: 504px;
            height: 430px;
        }
    }
    .modal-close{
        display: block;
        width: 46px;
        height: 46px;
        background: url('../../assets/modal-close.png') no-repeat;
        background-size: 100% 100%;
        position: absolute;
        top: 12px;
        right: 12px;
    }
}
</style>
