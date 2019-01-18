/* eslint-disable no-unused-vars */

const googleMapValue = googleMapValue || {};

(function($) {

  // common
  ///////////////////
  const DATA = {
    domain: '',
    spW: 320,
    tabW: 768,
    pcW: 980,
    wideScreenW: 1024,
    fullHdW: 1440,
    scrollTop: 0,
    scrollLeft: 0,

    init() {

      const self = this
      ;(self.domain =
        `${window.location.protocol}//${window.location.host}` || ''),
        (self.winW = window.innerWidth)
      self.winH = window.innerHeight

      self.isMini = self.winW < self.spW
      self.isSp = self.spW <= self.winW && self.winW <= self.tabW
      self.isTab = self.tabW <= self.winW && self.winW <= self.pcW
      self.isPc = self.winW >= self.pcW && self.winW <= self.wideScreenW
      self.isWidescreen =
        self.winW >= self.wideScreenW && self.winW <= self.fullHdW
      self.isFullHd = self.winW >= self.fullHdW

      self.isDesktop = self.winW >= self.tabW
      self.isMobile = self.winW <= self.tabW

      const resize = () => {

        self.winW = window.innerWidth
        self.winH = window.innerHeight

        self.isMini = self.winW <= self.spW
        self.isSp = self.spW <= self.winW && self.winW <= self.tabW
        self.isTab = self.tabW <= self.winW && self.winW <= self.pcW
        self.isPc = self.winW >= self.pcW && self.winW <= self.wideScreenW
        self.isWidescreen =
          self.winW >= self.wideScreenW && self.winW <= self.fullHdW
        self.isFullHd = self.winW >= self.fullHdW

        self.isDesktop = self.winW >= self.tabW
        self.isMobile = self.winW <= self.tabW

      }
      resize()

      window.addEventListener('resize', resize)

      const scroll = () => {

        self.scrollTop = window.pageYOffset

      }

      window.addEventListener('scroll', scroll)

    },
    transitionEnd:
      'oTransitionEnd mozTransitionEnd webkitTransitionEnd transitionend',
    animationEnd: 'webkitAnimationEnd oanimationend msAnimationEnd animationend',
  }
  DATA.init();

  const util = {

    /**
     * obj merge
     * オプションのキーが存在するときだけ新しいオブジェクトにマージする
     */
    extend(a, b) {

      for (let key in b) {

        if (b.hasOwnProperty(key)) {

          a[key] = b[key];

        }

      }
      return a;

    }

  };

  // main
  /////////////////////
  const main = () => {

    /**
     *
     * @type {*}
     */
    const $wrap = $('#wrap');


    // Process when the window resize is over
    //////////////////////////////
    const finishResizeEvent = (func) => {

      let timer = false;

      $(window).on('resize', function() {

        if (timer !== false) {

          clearTimeout(timer);

        }

        timer = setTimeout(func, 300);

      });

    };


    const loaded = () => {

      $wrap.addClass('is-active').find('.js-late').addClass('is-active');

      let tl = new TimelineMax();

      tl.to($('#js-loader'), .5, {

        scale: 1.4,
        onComplete: function(obj) {

          $(obj.target).fadeOut();

        },
        onCompleteParams: ['{self}']

      }).fromTo($('#main'), 1, {

        y: 50

      }, {

        y: 0,
        autoAlpha: 1,
        ease: Power2.easeOut,

      }, '+=.2');

    };

    /**
    *
    * span element
    *
    */
    const spanText = ($text) => {

      $text.children().addBack().contents().each(function() {

        if (this.nodeType == 3) {

          $(this).replaceWith($(this).text().replace(/(\S)/g, '<span>$1</span>'));

        }

      });

    };
    spanText($('.js-message'));


    const fadeText = (target) => {

      let $animItem = $('span', target),
          delayTime = $(target).attr('data-delay') * 1;

      TweenMax.set($animItem, {

        transformPerspective: 500,
        autoAlpha: 1,
        z: 0

      });

      TweenMax.staggerFrom($animItem, 1.5, {

        xPercent: 50,
        yPercent: 100,
        rotationY: -90,
        autoAlpha: 0,
        z: 0,
        ease: Power3.easeOut,
        delay: delayTime

      }, .1);

    };
    fadeText('.js-message');


    /*
    *
    * TweenMax slide animation
    *
    */
    const heroSlide = () => {

      let slideId = 0,
          $slide  = $('.p-hero_list'),
          itemSum = $slide.eq(0).find('li').length,
          itemCount = itemSum - 2,
          slideW = $slide.find('li').width(),
          timer = false;

      const slideTop = () => {

        slideId += 1;

        let anim = TweenMax.to($slide, 1.2, {

          x: -slideW * slideId,
          ease: Sine.easeOut,
          onComplete: function() {

            if (slideId > itemCount) {

              TweenMax.set($slide, {

                x: 0

              });
              slideId = 0;

            }

          }

        });

        anim.play();

      };

      timer = setInterval(slideTop, 4000);

    };


    const maskMove = (target) => {

      if ($(target).length) {

        let mask = Snap(target),
            path = ['M0,0V450H800V0ZM435.12,413.49H380.43c-352.78,6-321.2-74.92-315.52-207.16V182.2C47.68,38,211.88,35,368.88,36.49l68.55-.32c241.86,0,294.24,16.63,297.66,166v12.13c-5.72,229.77-98.93,192-265.3,199.17Z', 'M0,0V450H800V0ZM634.5,413.28l-398,.21c-56,4.79-186,4.79-172-181.21v-28c15-191,53-171,169-167.77l282-2.23c208-3,227,52,218,188v33C736.5,342.28,737.5,402.28,634.5,413.28Z'],
            index = 0,
            time = 3000;

        const play = () => {

          if (index === 0) {

            mask.animate({

              d: path[1]

            }, time, play);

            index = 1;

          } else {

            mask.animate({

              d: path[0]

            }, time, play);

            index = 0;

          }

        };
        play();

      }


    };
    maskMove('#js-mask01');
    maskMove('#js-mask02');
    maskMove('#js-mask03');


    const loaderAnim = () => {

      let $animItem = $('span', '#js-loader');

      TweenMax.set($animItem, {

        transformPerspective: 500

      });

      TweenMax.staggerFrom($animItem, 1, {

        y: -20,
        rotationX: 90,
        ease: Power3.easeOut,
        yoyo: true,
        repeat: -1,
        repeatDelay: .5

      }, .2);

    };
    loaderAnim();

    // show mobile navigation
    /////////////////////////////

    /**
     *
     * @param target {object}
     * @param navi {object}
     *
     */
    const actionSpHeader = (target, navi) => {

      if (target.length) {

        /**
         *
         * @type {Object}
         */
        const $spGnav = navi,
              $spGnavBtn = target;

        /**
         *
         * @type {boolean}
         */
        let isSpGnavOpen = false;

        $spGnavBtn.on('click', function(e) {

          e.preventDefault();

          if (!isSpGnavOpen) {

            $('body').on('touchmove.noScroll', function(e) {

              e.preventDefault();

            });

            $spGnav.addClass('is-open');
            isSpGnavOpen = true;

          } else {

            $('body').off('.noScroll');
            $spGnav.removeClass('is-open');
            isSpGnavOpen = false;

          }

        });

      }

    };


    // drop down action
    /////////////////////////////
    /**
     *
     * @param target
     */
    const dropDownMenu = (target) => {

      /**
       *
       * @type {*}
       */
      let $target = $(target);

      $target.on('click', function(e) {

        e.preventDefault();
        $(this).toggleClass('is-active').next().stop().slideToggle(550, 'easeInOutCubic');

      });

    };
    dropDownMenu('.js-dropdown-trigger');

    /**
    *
    *  rect animation
    *
    */
    /**
     *
     * @type {RectAnimation}
     */
    const RectAnimation = class {

      /**
       * Constructor
       *
       * @param element DOM
       * @param {string} アニメーションの方向を決定します
       */
      constructor(target, direction) {

        this.target = $(target);
        this.direction = direction;

        this.layout();

      }

      setOption(options) {

        this.options = {

          isContentHidden: true,

          direction: 'lr',
          bgcolor: '#333333',
          duration: .5,
          easing: Power2.easeInOut,
          onCover: function() {

            return false;

          },
          onStart: function() {

            return false;

          },
          onComplete: function() {

            return false;

          }

        };
        util.extend(this.options, options);

      }

      getHeight() {

        const height = this.target.height();
        return height;

      }

      getWidth() {

        const width = this.target.width();
        return width;

      }

      /**
       *
       * @returns {string}
       */
      initRect() {

        const height = this.getHeight(),
              width = this.getWidth();

        if (this.direction === 'lr') {

          return `rect(0px 0px ${height} 0px)`;

        } else if (this.direction === 'rl') {

          return `rect(0px ${width} ${height} ${width})`;

        } else if (this.direction === 'tb') {

          return `rect(0px ${width} 0px 0px)`;

        } else if (this.direction === 'bt') {

          return `rect(${height} ${width} ${height} 0px)`;

        }

      }

      layout() {

        let position = this.target.css('position');

        if (position !== 'fixed' && position !== 'absolute' && position !== 'relative') {

          this.target.css('position', 'relative');

        }

        this.mask = $('<div>').addClass('c-block_mask').css({

          position: 'absolute',
          zIndex: 99,
          top: 0,
          left: 0,
          right: 0,
          bottom: 0,

        });

        TweenMax.set(this.mask, {

          clip: this.initRect(this.direction)

        });

        this.target.wrapInner('<div>').children().css('opacity', 0).addClass('c-block_inner').parent().prepend(this.mask);

      }

      getRect(width, height, end) {

        let rect = {

          top: 0,
          right: 0,
          bottom: 0,
          left: 0

        };

        if (this.direction === 'lr') {

          rect.bottom = height;
          rect.right = width;

          if (end) {

            rect.left = width;

          }

        } else if (this.direction === 'rl') {

          rect.bottom = height;
          rect.right = width;

          if (end) {

            rect.right = 0;

          }

        } else if (this.direction === 'tb') {

          rect.right = width;
          rect.bottom = height;

          if (end) {

            rect.top = height;

          }

        } else if (this.direction === 'bt') {

          rect.right = width;
          rect.bottom = height;

          if (end) {

            rect.bottom = 0;

          }

        }

        return `rect(${rect.top}px ${rect.right}px ${rect.bottom}px ${rect.left}px)`;

      }

      // public
      // - - - -- - - - - -  - - - -
      anim() {


        const tl = new TimelineMax({

              }),
              height = this.getHeight(),
              width = this.getWidth(),
              fromRect = this.getRect(width, height, false),
              toRect = this.getRect(width, height, true),
              self = this,
              mask = this.options;

        tl.to(this.mask, .5, {

          clip: fromRect,
          ease: Power3.easeInOut,

        }).add(function() {

          self.mask.next('.c-block_inner').css('opacity', 1);

        }).to(this.mask, .5, {

          clip: toRect,
          ease: Power2.easeInOut

        });


      }

    };

    // const leftToRightMask = new RectAnimation('.js-block-anime', 'lr');
    // leftToRightMask.anim();

    /**
     * element viewport in addClass
     * @param target
     */
    const showElement = (target) => {

      $(target).each(function() {

        let $this = $(this),
            offset = $this.offset().top,
            count = 0,
            mask;

        let direction = $this.attr('data-direction');

        if (direction) {

          mask = new RectAnimation(this, direction);

        }

        $(window).on('scroll', function() {

          if (DATA.scrollTop + DATA.winH > offset + 210) {

            $this.addClass('is-show');

            if (count === 0) {

              mask.anim();
              count = 1;

            }

          } else {

            $this.removeClass('is-show');

          }

        });

      });

    };
    showElement('.js-fadein', $wrap);


    // carousel
    /////////////////////////////
    const carouselInit = () => {

      const fadeSingle = () => {

        $('.js-slider-fade').owlCarousel({
          animateOut: 'fadeOut',
          items: 1,
          margin: 0,
          stagePadding: 0,
          smartSpeed: 450,
          loop: true,
          autoplay: true,
          autoplayTimeout: 3000,
          autoplayHoverPause: true
        });

      }
      fadeSingle();

      const slideBasic = () => {

        $('.js-slider-basic').owlCarousel({
          loop: true,
          margin: 10,
          nav: true,
          responsive: {
            0: {
              items: 1
            },
            600: {
              items: 3
            },
            1000: {
              items: 5
            }
          }
        });

      }
      slideBasic();


      const slideCenter = () => {

        $('.js-slider-center').owlCarousel({
          center: true,
          items: 2,
          loop: true,
          margin: 10,
          smartSpeed: 1000,
          autoplay: true,
          autoplayTimeout: 1500,
          autoplayHoverPause: true
        });

      }
      slideCenter();


      const customNavi = () => {

        const owl = $('.js-slider-my-nav');

        owl.owlCarousel({
          loop: true,
          margin: 10,
          smartSpeed: 600,
          autoplay: true,
          autoplayTimeout: 1500,
          autoplayHoverPause: true
        });

        owl.next().find('.js-slider-nav-prev').on('click', function(e) {

          e.preventDefault();
          owl.trigger('prev.owl.carousel');

        });

        owl.next().find('.js-slider-nav-next').on('click', function(e) {

          e.preventDefault();
          owl.trigger('next.owl.carousel');

        });

      }
      customNavi();


      const thumbnail = () => {

        $('.js-slider-thumb').owlCarousel({
          animateOut: 'fadeOut',
          smartSpeed: 600,
          loop: true,
          autoplay: true,
          autoplayTimeout: 1500,
          autoplayHoverPause: true,
          items: 1,
          dots: false,
          thumbs: true,
          thumbImage: true,
          thumbContainerClass: 'owl-thumbs',
          thumbItemClass: 'owl-thumb-item'
        });

      };
      thumbnail();

    };


    const zipCordComplete = () => {

      $('#zip').keyup(function() {

        AjaxZip3.zip2addr(this, '', 'address', 'address');

      });

    };
    zipCordComplete();


    // home tab
    /////////////////////////
    const actionTab = () => {

      /**
       * @type {*}
       */
      let tabWrap = $('.js-tab'),
          trigger = tabWrap.find('.js-tab-trigger'),
          // tabItem = tabWrap.find('.js-tab-item'),
          anchor;

      trigger.on('click', function(e) {

        e.preventDefault();
        let $self = $(this);

        // trigger
        $self.closest(tabWrap).find('.js-tab-trigger').removeClass('is-active');
        anchor = $self.addClass('is-active').attr('href');

        // panel item
        $self.closest(tabWrap).find('.js-tab-item').removeClass('is-open').filter(anchor).addClass('is-open');

      });

    };
    actionTab();

    /*
     *
     * anchor scroll
     *
     */
    const anchorScroll = (target) => {

      $(target).on('click', function() {

        if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {

          let hash = $(this.hash),
              targetOffset = 0;

          hash = hash.length && hash;

          const updatePos = () => {

            TweenMax.to($wrap, 0, {

              scrollTo: {

                y: targetOffset + 1

              }

            });
            TweenMax.to($wrap, 0, {

              scrollTo: {

                y: targetOffset - 1

              }

            });

          }

          if (hash.length) {

            //wrap内をスクロールするから wrapのスクロール量を足しておく
            targetOffset = hash.offset().top - 80 + $wrap.scrollTop();

            TweenMax.to($wrap, 2, {

              scrollTo: {

                y: targetOffset,
                x: 0

              },
              ease: Power3.easeOut,
              onComplete: function() {

                updatePos();

              }

            });

          }

          return false;

        }

      });

    };
    anchorScroll('.js-scroll');


    const gMap = () => {

      /**
       * マップのid
       *
       * @type {string}
       */
      const MY_MAPTYPE_ID = 'test';

        /**
         * 要素を取得する
         * @type {Element}
         */
      let canvas = document.getElementById('js-map');

        /**
         * マップのスタイル
         * @type {array}
         */
      let stylez = [
        {
          'featureType': 'road',
          'elementType': 'geometry',
          'stylers': [
            {
              'visibility': 'off'
            }
          ]
        },
        {
          'featureType': 'poi',
          'elementType': 'geometry',
          'stylers': [
            {
              'visibility': 'off'
            }
          ]
        },
        {
          'featureType': 'landscape',
          'elementType': 'geometry',
          'stylers': [
            {
              'color': '#FFFAF0'
            }
          ]
        },
        {
          'featureType': 'water',
          'stylers': [
            {
              'color': '#d9edf7'
            }
          ]
        },
        {
          'featureType': 'road',
          'elementType': 'labels',
          'stylers': [
            {
              'visibility': 'off'
            }
          ]
        },
        {
          'featureType': 'transit',
          'stylers': [
            {
              'visibility': 'off'
            }
          ]
        },
        {
          'featureType': 'administrative',
          'elementType': 'geometry',
          'stylers': [
            {
              'lightness': 40
            }
          ]
        },
        {
          'featureType': 'poi.park',
          'elementType': 'geometry',
          'stylers': [
            {
              'visibility': 'on',
              'color': '#c5dac6'
            }
          ]
        },
        {
          'featureType': 'landscape.natural.terrain',
          'elementType': 'geometry.fill',
          'stylers': [
            {
              'visibility': 'on'
            },
            {
              'color': '#CCAA88'
            },
            {
              'lightness': 40
            }
          ]
        },
        {
          'featureType': 'landscape.man_made',
          'elementType': 'geometry.fill',
          'stylers': [
            {
              'visibility': 'on'
            },
            {
              'color': '#EEEEEE'
            }
          ]
        },
        {
          'featureType': 'road',
          'stylers': [
            {
              'visibility': 'simplified'
            },
            {
              'color': '#FF0000'
            },
            {
              'gamma': 9
            }
          ]
        },
        {
          'featureType': 'road.highway',
          'stylers': [
            {
              'visibility': 'on'
            },
            {
              'color': '#FF0000'
            },
            {
              'gamma': 8
            }
          ]
        },
        {
          'featureType': 'road.highway.controlled_access',
          'stylers': [
            {
              'visibility': 'on'
            },
            {
              'color': '#FF0000'
            },
            {
              'gamma': 4
            }
          ]
        },
        {
          'featureType': 'road',
          'elementType': 'labels',
          'stylers': [
            {
              'visibility': 'off'
            }
          ]
        },
        {
          'featureType': 'poi.government',
          'elementType': 'geometry',
          'stylers': [
            {
              'visibility': 'on'
            },
            {
              'color': '#DDDDDD'
            }
          ]
        },
        {
          'featureType': 'transit.station',
          'elementType': 'geometry',
          'stylers': [
            {
              'visibility': 'on'
            },
            {
              'color': '#CCCCCC'
            }
          ]
        },
        {
          'featureType': 'transit.line',
          'elementType': 'geometry',
          'stylers': [
            {
              'visibility': 'on'
            },
            {
              'color': '#AAAAAA'
            },
            {
              'gamma': 4
            }
          ]
        }
      ];

        /**
         * 地図の中心
         * @type {google.maps.LatLng}
         */
      let latlng = new google.maps.LatLng(googleMapValue.latitude, googleMapValue.longitude);

        /**
         * オプションのセット
         * @type {{zoom: number, center: google.maps.LatLng}}
         */
      let mapOptions = {
        zoom: 15,
        center: latlng,
        mapTypeId: MY_MAPTYPE_ID,
        scrollwheel: false,

      };

      let map = new google.maps.Map(canvas, mapOptions);

      /**
       * マーカーのセット
       * @type {google.maps.Marker}
       */
      let marker = new google.maps.Marker({

        position: map.getCenter(),
        map: map,
        title: 'marker',
        icon: {
          url: 'data:image/png;base64,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',
          scaledSize: new google.maps.Size(51, 70)
        }

      });

      // let styledMapOptions = {
      //
      //   name: 'mymap'
      //
      // };

        /**
         * マップスタイルのセット
         * @type {google.maps.StyledMapType}
         */
      let jayzMapType = new google.maps.StyledMapType(stylez);
      map.mapTypes.set(MY_MAPTYPE_ID, jayzMapType);

    };


    /**
     * parallax scroll effect
     */
    const parallaxScroll = () => {

      /**
       *
       * @type {number}
       */
      let delta = 3,
          speed = .8,
          target = $('[data-parallax]', $wrap),
          el,
          coefficent,
          offsetTop,
          transY;

      /**
       *
       * @param scroll
       */
      const scrollMove = (scroll) => {

        target.each(function() {

          el = $(this);
          coefficent = el.attr('data-parallax');
          offsetTop = el.offset().top - scroll;
          transY = offsetTop * -coefficent / delta;

          TweenMax.to(el, speed, {

            y: transY,
            ease: Power3.easeOut,

          });

        });

      };

      $wrap.on('scroll', function() {

        scrollMove(DATA.scrollTop);

      });


    };
    parallaxScroll();


    /**
     * navigation interaction
     */
    const navAction = () => {

      const $openTrigger = $('#js-nav-trigger')
      const activeClassName = 'is-open'
      const $targets = $('.js-nav-target', '#wrap')
      const $closeTrigger = $('.js-nav-close')

      $openTrigger.on('click', (e) => {

        e.preventDefault()

        $(e.currentTarget).addClass(activeClassName)
        $targets.addClass(activeClassName)

      })

      $closeTrigger.on('click', (e) => {

        e.preventDefault()

        $openTrigger.removeClass(activeClassName)
        $targets.removeClass(activeClassName)

      })

    };

    /**
     *
     * @param target {String} 対象の要素
     */
    class heroImages {

      constructor(target) {

        this.$hero = $(target);
        this.addClassName = 'hero__img--sm';
        this.currentNumber = 0;
        this.totalNum = 86;

        this.init();

      }

      init() {

        this.getAllElement();
        this.loadEvent();

        setInterval(() => {

          this.selectElement()

        }, 2000)

      }

      /**
       * デバイスによって取得する要素を変更する
       */
      getAllElement() {

        if (DATA.isSp) {

          this.$images = this.$hero.find('.js-sp');

        } else {

          this.$images = this.$hero.find('img');

        }

      }

      selectElement() {

        const $el = this.$images.eq(this.getNum(this.$images.length));

        this.outAnim($el)

      }

      outAnim($el) {

        TweenMax.to($el.parent(), .35, {
          scale: 0,
          ease: Back.easeIn.config(1.3),
          onComplete: () => {

            this.setSrc($el);

          }
        })

      }

      inAnim($target) {

        TweenMax.to($($target).parent(), .5, {
          scale: 1,
          ease: Power2.easeOut
          ,
        })

      }

      /**
       * 画像の src を変更する
       * @param target {Element}
       */
      setSrc(target) {

        const src = target.attr('src');
        let number = 0
        do {

          number = this.getNum(this.totalNum + 1);

        } while (this.currentNumber === number && number === 0)

        this.currentNumber = number
        const path = src
          .replace(/item\d*/g, `item${this.currentNumber}`);

        target.attr('src', path);

      }

      /**
       * 画像を load した時のイベント
       */
      loadEvent() {

        this.$images.on('load', (e) => {

          this.checkSize(e.currentTarget);
          this.inAnim(e.currentTarget);

        })

      }

      /**
       * 画像のサイズをチェックして class を付与する
       * @param target
       */
      checkSize(target) {

        const $el = $(target);
        const height = $el.height();
        const width = $el.width();

        if (width < height) {

          $el.addClass(this.addClassName);

        } else {

          $el.removeClass(this.addClassName);

        }

      }

      /**
       * ランダムな数字を返す
       * @param max {number}
       * @returns {number}
       */
      getNum(max) {

        return Math.trunc(Math.random() * max)

      }

      // 87

    }


    /////////////////////////////////
    //
    // load event
    //
    /////////////////////////////////
    $(window).on('load', () => {

      if ($('#js-map').length) {

        gMap();

      }


      heroSlide();
      navAction();
      //
      carouselInit();
      actionSpHeader($('.js-navi-trigger'), $('.js-navi'));
      new heroImages('#hero');
      setTimeout(loaded, 1000);
      console.log('ok');

    });


  };
  main();

})(jQuery);
