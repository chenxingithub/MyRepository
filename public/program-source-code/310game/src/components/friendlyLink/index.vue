<template>
    <div class="friendly-link">
        <div class="link-tit">
            <span>友情链接</span>
        </div>
        <div class="link-con">
            <a href="https://www.ibingniao.com/" target="_blank">广州冰鸟网络</a>
            <a href="https://www.ibingniao.com/" target="_blank">冰鸟链接</a>
        </div>
    </div>
</template>
<script>
export default {
    name: 'friendlyLink'
}
</script>
<style lang="scss">
.friendly-link{
    width: 1200px;
    // margin-top: 35px;
    margin: 0 auto;
    .link-tit{
        position: relative;
        padding: 35px 0 26px 40px;
        span{
            font-size: 18px;
            position: relative;
        }
        span:before{
            height: 20px;
            width: 5px;
            content: " ";
            background: #31aae2;
            position: absolute;
            top: 2px;
            left: -20px;
        }
    }
    .link-con{
        border-top: 1px solid #dfdfdf;
        padding: 20px 20px 34px;
        line-height: 16px;
        a{
            color: #000000;
            font-size: 14px;
            margin-right: 55px;
            display: inline-block;
            transition: color 0.2s linear;
        }
        a:hover{
          color: #31aae2;
          transition: color 0.2s linear;
        }
    }
}
</style>
