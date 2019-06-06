<template>
    <div id="entry-main">
        <div class="entry-video">
            <div class="entry-video-border video_circle"></div>
        </div>
        <div class="entery-main-title">

        </div>
        <div class="eyes">
            <!-- <span class="fire fire1 leaf leaf1" style="animation-play-state:running">
                <img style="animation-play-state:running" src="../../assets/fire01.png" alt="fire">
            </span> -->
            <span class="fire fire2 leaf leaf2" style="animation-play-state:running">
                <img style="animation-play-state:running" src="../../assets/fire02.png" alt="fire">
            </span>
            <span class="fire fire3 leaf leaf3" style="animation-play-state:running">
                <img style="animation-play-state:running" src="../../assets/fire03.png" alt="fire">
            </span>
            <span class="fire fire4 leaf leaf4" style="animation-play-state:running">
                <img style="animation-play-state:running" src="../../assets/fire03.png" alt="fire">
            </span>
            <span class="fire fire5 leaf leaf5" style="animation-play-state:running">
                <img style="animation-play-state:running" src="../../assets/fire02.png" alt="fire">
            </span>
            <span class="fire fire6 leaf leaf6" style="animation-play-state:running">
                <img style="animation-play-state:running" src="../../assets/fire01.png" alt="fire">
            </span>
            <span class="fire fire7 leaf leaf7" style="animation-play-state:running">
                <img style="animation-play-state:running" src="../../assets/fire02.png" alt="fire">
            </span>
            <span class="fire fire8 leaf leaf8" style="animation-play-state:running">
                <img style="animation-play-state:running" src="../../assets/fire03.png" alt="fire">
            </span>
             <span class="fire fire9 leaf leaf9" style="animation-play-state:running">
                <img style="animation-play-state:running" src="../../assets/fire01.png" alt="fire">
            </span>
            <span class="fire fire10 leaf leaf10" style="animation-play-state:running">
                <img style="animation-play-state:running" src="../../assets/fire02.png" alt="fire">
            </span>
            <span class="fire fire11 leaf leaf11" style="animation-play-state:running">
                <img style="animation-play-state:running" src="../../assets/2_03.png" alt="fire">
            </span>
            <span class="fire fire12 leaf leaf12" style="animation-play-state:running">
                <img style="animation-play-state:running" src="../../assets/2_04.png" alt="fire">
            </span>
            <span class="fire fire13 leaf leaf13" style="animation-play-state:running">
                <img style="animation-play-state:running" src="../../assets/2_05.png" alt="fire">
            </span>
        </div>
    </div>
</template>
<script>
export default {
    name: "entryMain"
}
</script>
<style lang="scss">
#entry-main{
    width: 750px;
    height: 1100px;
    position: relative;
    background: url('../../assets/entry-sec1-bg.jpg') no-repeat;
    background-size: 100% 100%;
    overflow: hidden;
    .entery-main-title{
        position: absolute;
        top: 590px;
        left: 0;
        height: 100px;
        width: 750px;
        height: 491px;
        background: url('../../assets/entry-main-title.png') no-repeat;
        background-size: 100% 100%;
        z-index: 150;
    }
    .entry-video{
        position: absolute;
        top: 20px;
        left: 95px;
        width: 102px;
        height: 102px;
        background: url('../../assets/entry-video-btn.png') no-repeat;
        background-size: 30px 35px;
        background-position: 51 - 10px center;
        .entry-video-border{
            width: 102px;
            height: 102px;
            background: url('../../assets/entry-video-boder.png') no-repeat;
            background-size: 100% 100%;
        }
    }
    .eyes{
        width: 145px;
        height: 65px;
        position: absolute;
        top: 588px;
        left: 218px;
        background: url('../../assets/1.gif') no-repeat;
        background-size: 100% 100%;
        img{
            width: 100%;
            height: 100%;
        }
    }
    .fire{
        display: block;
        position: absolute;
    }
    .fire1{
        top: 350px;
        left: -50px;
    }
    .fire2{
        top: 340px;
        left: -100px;
        img{
            width: 30px;
            height: 18px;
        }
    }
    .fire3{
        top: 360px;
        left: -120px;
        img{
            width: 35px;
            height: 20px;
        }
    }
    .fire4{
        top: 300px;
        left: -140px;
        img{
            width: 42px;
            height: 28px;
        }
    }
    .fire5{
        top: 310px;
        left: 180px;
        img{
            width: 42px;
            height: 28px;
        }
    }
    .fire6{
        top: 150px;
        left: -200px;
        img{
            width: 42px;
            height: 28px;
        }
    }
    .fire7{
        top: 60px;
        left: 220px;
        img{
            width: 30px;
            height: 18px;
        }
    }
    .fire8{
        top: 40px;
        left: 10px;
        img{
            width: 32px;
            height: 20px;
        }
    }
    .fire9{
        top: 120px;
        left: -50px;
        img{
            width: 46px;
            height: 35px;
        }
    }
    .fire10{
        top: 10px;
        left: -100px;
        img{
           width: 25px;
            height: 14px;
        }
    }
    .fire11{
        top: 50px;
        left: -50px;
        img{
            width: 19px;
            height: 19px;
        }
    }
    .fire12{
        top: 70px;
        left: -150px;
        img{
            width: 25px;
            height: 14px;
        }
    }
}
.leaf {position: absolute;z-index: 99;animation: leaf_move 1.45s cubic-bezier(1, 0.68, 1, 0.98) infinite;/* -webkit-animation: leaf_move 3s cubic-bezier(1, 0.68, 1, 0.98) infinite; */animation-play-state: paused;-webkit-animation-play-state: paused;}
.leaf img { filter: alpha(opacity=0); opacity: 0; -moz-opacity: 0; display: block; width: 100%; animation: leaf_move2 1.45s linear infinite; -webkit-animation: leaf_move1 4.5s linear infinite; animation-play-state: paused; -webkit-animation-play-state: paused; }

.leaf1 {  width: 110px; height: 79px; top: 10%; left: 30%; }

.leaf2 { width: 89px; height: 95px; top: 20%; left: 50%; animation-delay: 1s; -webkit-animation-delay: 1s; }
.leaf2 img { animation-delay: 1s; -webkit-animation-delay: 1s; }

.leaf3 { width: 110px; height: 79px; top: 45%; left: 90%; animation-delay: 1.2s; -webkit-animation-delay: 1.2s; }
.leaf3 img { animation-delay: 1.2s; -webkit-animation-delay: 1.2s; }

.leaf4 { width: 0.9066rem; top: 80%; left: 50%; animation-delay: 1.5s; -webkit-animation-delay: 1.5s; }
.leaf4 img { animation-delay: 1.5s; -webkit-animation-delay: 1.5s; }

.leaf5 { width: 0.5733rem; top: 45%; left: 85%; animation-delay: 1.8s; -webkit-animation-delay: 1.8s; }
.leaf5 img { animation-delay: 1.8s; -webkit-animation-delay: 1.8s; }

.leaf6 { width: 0.5733rem; top: 60%; left: 90%; animation-delay: 2.1s; -webkit-animation-delay: 2.1s; }
.leaf6 img { animation-delay: 2.1s; -webkit-animation-delay: 2.1s; }

.leaf7 { width: 0.5733rem; top: 60%; left: 90%; animation-delay: 2.4s; -webkit-animation-delay: 2.4s; }
.leaf7 img { animation-delay: 2.4s; -webkit-animation-delay: 2.4s; }

.leaf8 { width: 0.5733rem; top: 60%; left: 90%; animation-delay: 2.6s; -webkit-animation-delay: 2.6s; }
.leaf8 img { animation-delay: 2.6s; -webkit-animation-delay: 2.6s; }

.leaf9 { width: 0.5733rem; top: 60%; left: 90%; animation-delay: 1.2s; -webkit-animation-delay: 1.2s; }
.leaf9 img { animation-delay: 1.2s; -webkit-animation-delay: 1.2s; }

.leaf10 { width: 0.5733rem; top: 60%; left: 90%; animation-delay: 1.4s; -webkit-animation-delay: 1.4s; }
.leaf10 img { animation-delay: 1.4s; -webkit-animation-delay: 1.4s; }

@keyframes leaf_move { 0% {transform: translate3d(0, 0, 0);}
  100% {transform: translate3d(200%, -950%, 0);} }
@-webkit-keyframes leaf_move { 0% {transform: translate3d(0, 0, 0);}
  100% {transform: translate3d(200%, -950%, 0);} }
// @keyframes leaf_move1 { 0% {transform: translate3d(0, 0, 0) rotate(0);opacity: 0;}
//   25% {transform: translate3d(-150%, 0, 0) rotate(0deg);opacity: 0.9;}
//   50% {transform: translate3d(-300%, 0, 0) rotate(0deg);opacity: 0.9;}
//   75% { transform: translate3d(-450%, 0, 0) rotate(0deg); opacity: 0.9; }
//   85% { transform: translate3d(-580%, 0, 0) rotate(0deg); opacity: .65; }
//   95% { transform: translate3d(-750%, 0, 0) rotate(0deg); opacity: .5; }
//   99.99% {transform: translate3d(-950%, 0, 0) rotate(0deg);opacity: 0;}
//   100% {transform: translate3d(0, 0, 0) rotate(0);opacity: 0;} }
@keyframes leaf_move1 { 0% { -webkit-transform: translate3d(0, 0, 0) rotate(0deg); opacity: 0; }
  25% { -webkit-transform: translate3d(150%, 20%, 0) rotate(0deg); opacity: 0.85; }
  50% { -webkit-transform: translate3d(300%, 100%, 0) rotate(0deg); opacity: 0.7; }
  75% { -webkit-transform: translate3d(450%, 200%, 0) rotate(0deg); opacity: 0.2; }
  85% { transform: translate3d(580%, 260%, 0) rotate(0deg); opacity: .65; }
  95% { transform: translate3d(750%, 300%, 0) rotate(0deg); opacity: .3; }
  99.99% { -webkit-transform: translate3d(950%, 350%, 0) rotate(0deg); opacity: 0; }
  100% { -webkit-transform: translate3d(0, 0, 0) rotate(0deg); opacity: 0; } }
@-webkit-keyframes leaf_move1 { 0% { -webkit-transform: translate3d(0, 0, 0) rotate(0deg); opacity: 0; }
  25% { -webkit-transform: translate3d(150%, 20%, 0) rotate(0deg); opacity: 0.85; }
  50% { -webkit-transform: translate3d(300%, 100%, 0) rotate(0deg); opacity: 0.7; }
  75% { -webkit-transform: translate3d(450%, 200%, 0) rotate(0deg); opacity: 0.2; }
  85% { transform: translate3d(580%, 260%, 0) rotate(0deg); opacity: .65; }
  95% { transform: translate3d(750%, 300%, 0) rotate(0deg); opacity: .3; }
  99.99% { -webkit-transform: translate3d(950%, 350%, 0) rotate(0deg); opacity: 0; }
  100% { -webkit-transform: translate3d(0, 0, 0) rotate(0deg); opacity: 0; } }
// @-webkit-keyframes leaf_move2 { 0% { -webkit-transform: translate3d(0, 0, 0) rotate(0); opacity: 0; }
//   25% { -webkit-transform: translate3d(150%, 0, 0) rotate(45deg); opacity: 0.9; }
//   50% { -webkit-transform: translate3d(300%, 200%, 0) rotate(45deg); opacity: 0.8; }
//   75% { -webkit-transform: translate3d(450%, 0, 0) rotate(45deg); opacity: 0.2; }
//   85% { transform: translate3d(580%, 150%, 0) rotate(45deg); opacity: .65; }
//   95% { transform: translate3d(750%, 0, 0) rotate(45deg); opacity: .3; }
//   99.99% { -webkit-transform: translate3d(950%, 0, 0) rotate(45deg); opacity: 0; }
//   100% { -webkit-transform: translate3d(0, 0, 0) rotate(45deg); opacity: 0; } }


@keyframes rotation {
    from {
        transform:rotate(0deg);
        -ms-transform:rotate(0deg); 	/* IE 9 */
        -moz-transform:rotate(0deg); 	/* Firefox */
        -webkit-transform:rotate(0deg); /* Safari 和 Chrome */
        -o-transform:rotate(0deg); 	/* Opera */
        visibility: hidden;
    }

    to {
        transform:rotate(360deg);
        -ms-transform:rotate(360deg); 	/* IE 9 */
        -moz-transform:rotate(360deg); 	/* Firefox */
        -webkit-transform:rotate(360deg); /* Safari 和 Chrome */
        -o-transform:rotate(360deg);    /* Opera */
    }
}
/*============================ 修改 1 ===========================*/
.video_circle {
    animation: rotation 1.8s linear infinite;
}
</style>
