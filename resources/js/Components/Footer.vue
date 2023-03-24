<template>
  <!-- FOOTER -->
  <footer>
    <!-- img -->
    <div class="new_footer_area">
      <div class="new_footer_top">
        <div class="footer_bg">
          <div class="footer_bg_one"></div>
          <div class="footer_bg_two"></div>
        </div>
      </div>
    </div>
    <div class="text-center p-4 text-zinc-700">
      © 2021 Copyright Boolbnb
    </div>

    <!-- Bar -->
    <div class="footer w-full px-20 flex flex-between items-center text-sm text-gray-600">
      <div class="w-1/2">
        <a href="/" class="mr-4 hover:underline">© 2023 Boolbnb, Inc.</a>
      </div>

      <div class="w-1/2 flex justify-end items-center">
        <a href="/" class="mr-4 hover:underline"><i class="uil uil-globe"></i> English</a>
        <a href="/" class="mr-4 hover:underline">&euro; EUR</a>
      </div>
    </div>
  </footer>
</template>

<style lang="scss" scoped>
// Footer

.new_footer_area {
  .new_footer_top {
    padding: 30px 0px 300px;
    position: relative;
    overflow-x: hidden;

    .footer_bg {
      position: absolute;
      bottom: 0;
      background: url("../../../public/footer_bg.png") no-repeat scroll center 0;
      width: 100%;
      height: 266px;

      .footer_bg_one {
        background: url("../../../public/volks.gif") no-repeat center center;
        width: 330px;
        height: 105px;
        background-size: 100%;
        position: absolute;
        bottom: 0;
        left: 30%;
        -webkit-animation: myfirst 22s linear infinite;
        animation: myfirst 22s linear infinite;
      }

      .footer_bg_two {
        background: url("../../../public/cyclist.gif") no-repeat center center;
        width: 88px;
        height: 100px;
        background-size: 100%;
        bottom: 0;
        left: 38%;
        position: absolute;
        -webkit-animation: myfirst 30s linear infinite;
        animation: myfirst 30s linear infinite;
      }
    }
  }
}

@-moz-keyframes myfirst {
  0% {
    left: -25%;
  }

  100% {
    left: 100%;
  }
}

@-webkit-keyframes myfirst {
  0% {
    left: -25%;
  }

  100% {
    left: 100%;
  }
}

@keyframes myfirst {
  0% {
    left: -25%;
  }

  100% {
    left: 100%;
  }
}

.footer {
  height: 40px;
  background-color: #fff;
  border-top: 1px solid #37373737;
  position: fixed;
  bottom: 0;
  left: 0;
  right: 0;
}

.footer a {
  text-decoration: none;
  border-bottom: 2px solid transparent;
}
</style>