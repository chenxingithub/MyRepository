let prefetchUrls = [
  "./static/img/load-bg.jpg",
  "./static/img/ouset.png",
  "./static/localImg/pet/pet-icon/1.png",
  "./static/localImg/pet/pet-icon/2.png",
  "./static/localImg/pet/pet-icon/3.png",
  "./static/localImg/pet/pet-icon/4.png",
  "./static/img/news-info-bg.png",
  "./static/img/news-slide.png",
  "./static/img/occup-intro-bg.png",
  "./static/img/portrait2.png",
  "./static/img/pet-intro-bg.png",
  "./static/img/game-features-bg.png",
  "./static/img/portrait1.png",
  "https://bingniao-cdn3.oss-cn-shenzhen.aliyuncs.com/cms/yhphs/ocpa-intro/01.gif",
  // "https://bingniao-cdn3.oss-cn-shenzhen.aliyuncs.com/cms/yhphs/ocpa-intro/02.gif",
  "https://bingniao-cdn3.oss-cn-shenzhen.aliyuncs.com/cms/yhphs/home.gif",
  "https://bingniao-cdn3.oss-cn-shenzhen.aliyuncs.com/cms/yhphs/pet-intro/pet/1.gif"
];

let loadSource = (callback) => {
  let img = new Image();
  let length = prefetchUrls.length;
  let percent = 0, index = 0;
  let loadPercent = document.getElementsByClassName('load-num')[0];
  let loadModal = document.getElementById('loadingPage');
  let loadBgImg = new Image();
  let loadFlag = true;
  loadBgImg.src = "https://bingniao-cdn3.oss-cn-shenzhen.aliyuncs.com/cms/yhphs/home.gif";


  let loading = () => {

    img.src = prefetchUrls[index];

    if(!loadModal) {
      loadModal = document.getElementById('loadingPage');
    }
    if(loadFlag) {
      if(loadBgImg.complete) {
        if(loadModal) {
          loadModal.style.display = 'none';
          loadFlag = false;
          return false;
        }
      } else {
        if(loadModal) {
          loadFlag = false;
          loadModal.style.display = 'block';
        }
      }
    }

    img.onload = () => {
      index++;

      percent = Math.floor(index/length*100);

      if(!loadPercent) {
        loadPercent = document.getElementsByClassName('load-num')[0];
      } else {
        loadPercent.innerText = percent;
      }

      if(index < length) {
        loading();
      } else {
        callback();
      }
    }
  }

  loading();
}

let loaded = () => {
  let load = document.getElementById('loadingPage');
  if(load) {
    setTimeout(() => {
      load.parentNode.removeChild(load);
    }, 300);
  }
}
loadSource(loaded);
