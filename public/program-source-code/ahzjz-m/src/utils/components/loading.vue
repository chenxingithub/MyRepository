<template>
    <div class="loading" v-show="loading">
        <div class="loading-msg">
            <div class="spinner"></div>
            <p>{{loadingMsg}}</p>
        </div>
    </div>
</template>
<script>
export default {
    name: 'loading',
    props: [
        'loadingMsg',
        "loading"
    ]
}
</script>
<style lang="scss" scoped>
.loading{
    position: fixed;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    background: rgba(0, 0, 0, 0.65);
    z-index: 999999;
    .loading-msg{
        width: 420px;
        height: 300px;
        position: absolute;
        top: 48%;
        left: 50%;
        transform: translate(-50%,-50%);
        background: rgba(157, 173, 185, .85);
        border-radius: 5%;
        font-size: 28px;
        color: #ffffff;
        text-align: center;
    }

    .spinner {
        width: 80px;
        height: 80px;
        background-color: #22b7cf;
        margin: 70px auto;
        -webkit-animation: rotateplane 1.2s infinite ease-in-out;
        animation: rotateplane 1.2s infinite ease-in-out;
    }
    
    @-webkit-keyframes rotateplane {
        0% { -webkit-transform: perspective(120px) }
        50% { -webkit-transform: perspective(120px) rotateY(180deg) }
        100% { -webkit-transform: perspective(120px) rotateY(180deg)  rotateX(180deg) }
    }
    
    @keyframes rotateplane {
        0% {
            transform: perspective(120px) rotateX(0deg) rotateY(0deg);
            -webkit-transform: perspective(120px) rotateX(0deg) rotateY(0deg)
        } 50% {
            transform: perspective(120px) rotateX(-180.1deg) rotateY(0deg);
            -webkit-transform: perspective(120px) rotateX(-180.1deg) rotateY(0deg)
        } 100% {
            transform: perspective(120px) rotateX(-180deg) rotateY(-179.9deg);
            -webkit-transform: perspective(120px) rotateX(-180deg) rotateY(-179.9deg);
        }
    }
}
</style>
