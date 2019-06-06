<template>
    <div class="how-to-exchange">
       <p>兑换说明</p>
       <div class="flex-wrap-sb-c">
           <div class="get-step">
               <p>step1</p>
               <a class="step-img" href="./static/img/howToExchange1.png">
                    <img src="../../assets/howToExchange1.png" alt="image">
               </a>
           </div>
           <div class="get-step">
               <p>step2</p>
               <a class="step-img" href="./static/img/howToExchange2.png">
                    <img src="../../assets/howToExchange2.png" alt="image">
               </a>
           </div>
           <div class="get-step">
               <p>step3</p>
               <a class="step-img" href="./static/img/howToExchange3.png">
                    <img src="../../assets/howToExchange3.png" alt="image">
               </a>
           </div>
           <div class="get-step">
               <p>step4</p>
               <a class="step-img" href="./static/img/howToExchange4.png">
                    <img src="../../assets/howToExchange4.png" alt="image">
               </a>
           </div>
       </div>
    </div>
</template>
<script>
export default {
    name: 'howToExchange'
}
</script>
<style lang="scss" scope>
.how-to-exchange{
    width: 710px;
    margin: 0 auto;
    >p{
        font-size: 30px;
        color: #ffffff;
        text-align: center;
    }
    .get-step{
        font-size: 22px;
        color: #ffffff;
        text-align: center;
        >p{
            margin: 25px 0 10px;
        }
    }
    .step-img{
        display: block;
        text-decoration: none;
        width: 330px;
        height: 250px;
        img{
            width: 330px;
            // height: 176px;
        }
        // background: url('../../assets/giftbag/step1.png') no-repeat;
        // background-size: 100% 100%;
    }
}
</style>
