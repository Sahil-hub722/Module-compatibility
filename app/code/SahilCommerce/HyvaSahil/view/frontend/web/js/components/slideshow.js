(function () {
    "use strict";

    window.awRbslider = function (element, config) {
        "use strict";
        const supportTouch=(
            ('ontouchstart' in document) ||
            (global.DocumentTouch && document instanceof global.DocumentTouch)  ||
            (global.navigator.msPointerEnabled && global.navigator.msMaxTouchPoints > 0) || //IE 10
            (global.navigator.pointerEnabled && global.navigator.maxTouchPoints > 0) || //IE >=11
            false
        );
        let Animations,
            playerId = 0;

        const slideshow = {
            options: Object.assign(
                {
                    animation: "fade",
                    duration: 500,
                    height: "auto",
                    start: 0,
                    autoplay: false,
                    autoplayInterval: 7000,
                    videoautoplay: true,
                    videomute: true,
                    slices: 15,
                    pauseOnHover: true,
                    kenburns: false,
                    kenburnsanimations: [
                        "uk-animation-middle-left",
                        "uk-animation-top-right",
                        "uk-animation-bottom-left",
                        "uk-animation-top-center",
                        "", // middle-center
                        "uk-animation-bottom-right",
                    ],
                },
                config || {}
            ),

            current: false,
            interval: null,
            hovering: false,
            element: element,

            init() {
                this.container = this.element.classList.contains("uk-slideshow")
                    ? this.element
                    : this.element.querySelector(".uk-slideshow:first");
                this.current = this.options.start;
                this.animating = false;

                this.fixFullscreen =
                    navigator.userAgent.match(/(iPad|iPhone|iPod)/g) &&
                    this.container.classList.contains(
                        "uk-slideshow-fullscreen"
                    ); // viewport unit fix for height:100vh - should be fixed in iOS 8

                if (this.options.kenburns) {
                    this.kbanimduration =
                        this.options.kenburns === true
                            ? "15s"
                            : this.options.kenburns;

                    if (!String(this.kbanimduration).match(/(ms|s)$/)) {
                        this.kbanimduration += "ms";
                    }

                    if (typeof this.options.kenburnsanimations === "string") {
                        this.options.kenburnsanimations =
                            this.options.kenburnsanimations.split(",");
                    }
                }
                this.slides = Array.from(this.container.children);
                this.update();
                Array.from(
                    this.element.querySelectorAll("[data-uk-slideshow-item]")
                ).map((item) => {
                    item.addEventListener("click.uk.slideshow", (event) => {
                        event.preventDefault();

                        const slide = item.getAttribute(
                            "data-uk-slideshow-item"
                        );

                        if (this.current === slide) return;

                        switch (slide) {
                            case "next":
                                this.next();
                                break;
                            case "previous":
                                this.previous();
                                break;
                            default:
                                this.show(parseInt(slideIndex, 10));
                        }

                        this.stop();
                    });
                });
                let resizeCallbackDebounce = Date.now();

                const resizeCallback = () => {
                    if (resizeCallbackDebounce > Date.now()) return;
                    resizeCallbackDebounce = Date.now() + 100;
                    this.resize();
                    if (this.fixFullscreen) {
                        this.container.style.height = window.innerHeight + "px";
                        this.slides.map((slide) => {
                            slide.style.height = window.innerHeight + "px";
                        });
                    }
                    window.addEventListener("resize", resizeCallback);
                    window.addEventListener("load", resizeCallback);
                };

                // chrome image load fix
                setTimeout(() => this.resize(), 80);

                // Set autoplay
                if (this.options.autoplay) {
                    this.start();
                }

                if (
                    this.options.videoautoplay &&
                    this.slides[this.current]._data &&
                    this.slides[this.current]._data["media"]
                ) {
                    this.playmedia(this.slides[this.current]._data["media"]);
                }

                if (this.options.kenburns) {
                    this.applyKenBurns(this.slides[this.current]);
                }
                this.container.addEventListener(
                    "mouseenter",
                    () => this.options.pauseOnHover && (this.hovering = true)
                );
                this.container.addEventListener(
                    "mouseleave",
                    () => this.options.pauseOnHover && (this.hovering = false)
                );

                this.element.addEventListener("swipeRight", () =>
                    this.previous()
                );
                this.element.addEventListener("swipeLeft", () => this.next());
                this.element.addEventListener("display.uk.check", () => {
                    if (this.element.matches(":visible")) {
                        if (this.fixFullscreen) {
                            this.container.style.height =
                                window.innerHeight + "px";
                            this.slides.map((slide) => {
                                slide.style.height = window.innerHeight + "px";
                            });
                        }
                    }
                });
                if (!(this.element._data && this.element._data.observer)) {
                    let observerCallbackDebounce = 0;
                    const observer = new MutationObserver(() => {
                        if (observerCallbackDebounce > Date.now()) return;
                        observerCallbackDebounce = Date.now() + 50;
                        if (
                            Array.from(this.container.children).filter(
                                (child) =>
                                    child.matches(":not([data-slide])").length
                            )
                        ) {
                            this.update = true;
                        }
                        this.element._data = Object.assign(
                            this.element._data || {},
                            { observer }
                        );
                        observer.observe(this.element);
                    });
                }
            },

            update(resize) {
                let canvas,
                    processed = 0;

                if (!this.slides[this.current]) {
                    this.current = 0;
                }

                this.slides.foreach((slide, index) => {
                    if (slide.dataset.processed) {
                        return;
                    }

                    let media = Array.from(slide.children).filter((child) =>
                            child.matches("img,video,iframe")
                        )[0],
                        type = "html";

                    slide._data = Object.assign(slide._data || {}, {
                        media: media,
                        sizer: sizer,
                    });

                    if (media) {
                        let placeholder;

                        type = media.nodeName.toLowerCase();

                        switch (media.nodeName) {
                            case "IMG":
                                let cover = document.createElement("div");
                                cover.classList.add(
                                    "uk-cover-background",
                                    "uk-position-cover"
                                );
                                cover.style.backgroundImage =
                                    "url(" + media.src + ")";

                                if (media.width && media.height) {
                                    placeholder =
                                        document.createElement("canvas");
                                    placeholder.setAttribute(
                                        "width",
                                        media.width
                                    );
                                    placeholder.setAttribute(
                                        "height",
                                        media.height
                                    );

                                    media.outerHTML = placeholder.outerHTML;
                                    media = placeholder;
                                    placeholder = undefined;
                                }

                                media.style.width = "100%";
                                media.style.height = "auto";
                                media.style.opacity = "0";

                                slide.prepend(cover);
                                slide._data = cover;
                                break;

                            case "IFRAME":
                                const src = media.src,
                                    iframeId = "sw-" + ++playerId;
                                media.src = "";
                                media.addEventListener("load", () => {
                                    if (
                                        index !== this.current ||
                                        (index == this.current &&
                                            !this.options.videoautoplay)
                                    ) {
                                        this.pausemedia(media);
                                    }

                                    if (this.options.videomute) {
                                        this.mutemedia(media);

                                        const inv = setInterval(
                                            ((ic) => {
                                                return () => {
                                                    this.mutemedia(media);
                                                    if (++ic >= 4)
                                                        clearInterval(inv);
                                                };
                                            })(0),
                                            250
                                        );
                                    }
                                });
                                media._data = Object.assign(media._data || {}, {
                                    slideshow: this,
                                });
                                media.setAttribute("data-player-id", iframeId);
                                media.setAttribute("src", [
                                    src,
                                    src.indexOf("?") > -1 ? "&" : "?",
                                ]);
                                media.classList.add("uk-position-absolute");

                                // disable pointer events
                                if (!supportTouch)
                                    media.style.pointerEvents = "none";

                                placeholder = true;

                                if (UI.cover) {
                                    UI.cover(media);
                                    media.attr("data-uk-cover", "{}");
                                }

                                break;

                            case "VIDEO":
                                media.addClass(
                                    "uk-cover-object uk-position-absolute"
                                );
                                placeholder = true;

                                if ($this.options.videomute)
                                    $this.mutemedia(media);
                        }

                        if (placeholder) {
                            canvas = UI.$("<canvas></canvas>").attr({
                                width: media[0].width,
                                height: media[0].height,
                            });
                            var img = UI.$(
                                '<img style="width:100%;height:auto;">'
                            ).attr("src", canvas[0].toDataURL());

                            slide.prepend(img);
                            slide.data("sizer", img);
                        }
                    } else {
                        slide.data("sizer", slide);
                    }

                    if ($this.hasKenBurns(slide)) {
                        slide.data("cover").css({
                            "-webkit-animation-duration": $this.kbanimduration,
                            "animation-duration": $this.kbanimduration,
                        });
                    }

                    slide.data("processed", ++processed);
                    slide.attr("data-slide", type);
                });

                if (processed) {
                    this.triggers = this.find("[data-uk-slideshow-item]");

                    // Set start slide
                    this.slides
                        .attr("aria-hidden", "true")
                        .removeClass("uk-active")
                        .eq(this.current)
                        .addClass("uk-active")
                        .attr("aria-hidden", "false");
                    this.triggers
                        .filter(
                            '[data-uk-slideshow-item="' + this.current + '"]'
                        )
                        .addClass("uk-active");
                }

                if (resize && processed) {
                    this.resize();
                }
            },

            resize: function () {
                if (this.container.hasClass("uk-slideshow-fullscreen")) return;

                var height = this.options.height;

                if (this.options.height === "auto") {
                    height = 0;

                    this.slides.css("height", "").each(function () {
                        height = Math.max(height, UI.$(this).height());
                    });
                }

                this.container.css("height", height);
                this.slides.css("height", height);
            },

            show: function (index, direction) {
                if (this.animating || this.current == index) return;

                this.animating = true;

                var $this = this,
                    current = this.slides.eq(this.current),
                    next = this.slides.eq(index),
                    dir = direction ? direction : this.current < index ? 1 : -1,
                    currentmedia = current.data("media"),
                    animation = Animations[this.options.animation]
                        ? this.options.animation
                        : "fade",
                    nextmedia = next.data("media"),
                    finalize = function () {
                        if (!$this.animating) return;

                        if (currentmedia && currentmedia.is("video,iframe")) {
                            $this.pausemedia(currentmedia);
                        }

                        if (nextmedia && nextmedia.is("video,iframe")) {
                            $this.playmedia(nextmedia);
                        }

                        next.addClass("uk-active").attr("aria-hidden", "false");
                        current
                            .removeClass("uk-active")
                            .attr("aria-hidden", "true");

                        $this.animating = false;
                        $this.current = index;

                        UI.Utils.checkDisplay(
                            next,
                            '[class*="uk-animation-"]:not(.uk-cover-background.uk-position-cover)'
                        );

                        $this.trigger("show.uk.slideshow", [
                            next,
                            current,
                            $this,
                        ]);
                    };

                $this.applyKenBurns(next);

                // animation fallback
                if (!UI.support.animation) {
                    animation = "none";
                }

                current = UI.$(current);
                next = UI.$(next);

                $this.trigger("beforeshow.uk.slideshow", [
                    next,
                    current,
                    $this,
                ]);

                Animations[animation]
                    .apply(this, [current, next, dir])
                    .then(finalize);

                $this.triggers.removeClass("uk-active");
                $this.triggers
                    .filter('[data-uk-slideshow-item="' + index + '"]')
                    .addClass("uk-active");
            },

            applyKenBurns: function (slide) {
                if (!this.hasKenBurns(slide)) {
                    return;
                }

                var animations = this.options.kenburnsanimations,
                    index = this.kbindex || 0;

                slide
                    .data("cover")
                    .attr("class", "uk-cover-background uk-position-cover")
                    .width();
                slide
                    .data("cover")
                    .addClass(
                        [
                            "uk-animation-scale",
                            "uk-animation-reverse",
                            animations[index].trim(),
                        ].join(" ")
                    );

                this.kbindex = animations[index + 1] ? index + 1 : 0;
            },

            hasKenBurns: function (slide) {
                return this.options.kenburns && slide.data("cover");
            },

            next: function () {
                this.show(
                    this.slides[this.current + 1] ? this.current + 1 : 0,
                    1
                );
            },

            previous: function () {
                this.show(
                    this.slides[this.current - 1]
                        ? this.current - 1
                        : this.slides.length - 1,
                    -1
                );
            },

            start: function () {
                this.stop();

                var $this = this;

                this.interval = setInterval(function () {
                    if (!$this.hovering) $this.next();
                }, this.options.autoplayInterval);
            },

            stop: function () {
                if (this.interval) clearInterval(this.interval);
            },

            playmedia: function (media) {
                if (!(media && media[0])) return;

                switch (media[0].nodeName) {
                    case "VIDEO":
                        if (!this.options.videomute) {
                            media[0].muted = false;
                        }

                        media[0].play();
                        break;
                    case "IFRAME":
                        if (!this.options.videomute) {
                            media[0].contentWindow.postMessage(
                                '{ "event": "command", "func": "unmute", "method":"setVolume", "value":1}',
                                "*"
                            );
                        }

                        media[0].contentWindow.postMessage(
                            '{ "event": "command", "func": "playVideo", "method":"play"}',
                            "*"
                        );
                        break;
                }
            },

            pausemedia: function (media) {
                switch (media[0].nodeName) {
                    case "VIDEO":
                        media[0].pause();
                        break;
                    case "IFRAME":
                        media[0].contentWindow.postMessage(
                            '{ "event": "command", "func": "pauseVideo", "method":"pause"}',
                            "*"
                        );
                        break;
                }
            },

            mutemedia: function (media) {
                switch (media[0].nodeName) {
                    case "VIDEO":
                        media[0].muted = true;
                        break;
                    case "IFRAME":
                        media[0].contentWindow.postMessage(
                            '{ "event": "command", "func": "mute", "method":"setVolume", "value":0}',
                            "*"
                        );
                        break;
                }
            },
        };
        slideshow.init();
        return slideshow;

        Animations = {
            none: function () {
                var d = UI.$.Deferred();
                d.resolve();
                return d.promise();
            },

            scroll: function (current, next, dir) {
                var d = UI.$.Deferred();

                current.css("animation-duration", this.options.duration + "ms");
                next.css("animation-duration", this.options.duration + "ms");

                next.css("opacity", 1).one(
                    UI.support.animation.end,
                    function () {
                        current
                            .css("opacity", 0)
                            .removeClass(
                                dir == -1
                                    ? "uk-slideshow-scroll-backward-out"
                                    : "uk-slideshow-scroll-forward-out"
                            );
                        next.removeClass(
                            dir == -1
                                ? "uk-slideshow-scroll-backward-in"
                                : "uk-slideshow-scroll-forward-in"
                        );
                        d.resolve();
                    }.bind(this)
                );

                current.addClass(
                    dir == -1
                        ? "uk-slideshow-scroll-backward-out"
                        : "uk-slideshow-scroll-forward-out"
                );
                next.addClass(
                    dir == -1
                        ? "uk-slideshow-scroll-backward-in"
                        : "uk-slideshow-scroll-forward-in"
                );
                next.width(); // force redraw

                return d.promise();
            },

            swipe: function (current, next, dir) {
                var d = UI.$.Deferred();

                current.css("animation-duration", this.options.duration + "ms");
                next.css("animation-duration", this.options.duration + "ms");

                next.css("opacity", 1).one(
                    UI.support.animation.end,
                    function () {
                        current
                            .css("opacity", 0)
                            .removeClass(
                                dir === -1
                                    ? "uk-slideshow-swipe-backward-out"
                                    : "uk-slideshow-swipe-forward-out"
                            );
                        next.removeClass(
                            dir === -1
                                ? "uk-slideshow-swipe-backward-in"
                                : "uk-slideshow-swipe-forward-in"
                        );
                        d.resolve();
                    }.bind(this)
                );

                current.addClass(
                    dir == -1
                        ? "uk-slideshow-swipe-backward-out"
                        : "uk-slideshow-swipe-forward-out"
                );
                next.addClass(
                    dir == -1
                        ? "uk-slideshow-swipe-backward-in"
                        : "uk-slideshow-swipe-forward-in"
                );
                next.width(); // force redraw

                return d.promise();
            },

            scale: function (current, next, dir) {
                var d = UI.$.Deferred();

                current.css("animation-duration", this.options.duration + "ms");
                next.css("animation-duration", this.options.duration + "ms");

                next.css("opacity", 1);

                current.one(
                    UI.support.animation.end,
                    function () {
                        current
                            .css("opacity", 0)
                            .removeClass("uk-slideshow-scale-out");
                        d.resolve();
                    }.bind(this)
                );

                current.addClass("uk-slideshow-scale-out");
                current.width(); // force redraw

                return d.promise();
            },

            fade: function (current, next, dir) {
                var d = UI.$.Deferred();

                current.css("animation-duration", this.options.duration + "ms");
                next.css("animation-duration", this.options.duration + "ms");

                next.css("opacity", 1);

                // for plain text content slides - looks smoother
                if (!(next.data("cover") || next.data("placeholder"))) {
                    next.css("opacity", 1)
                        .one(UI.support.animation.end, function () {
                            next.removeClass("uk-slideshow-fade-in");
                        })
                        .addClass("uk-slideshow-fade-in");
                }

                current.one(
                    UI.support.animation.end,
                    function () {
                        current
                            .css("opacity", 0)
                            .removeClass("uk-slideshow-fade-out");
                        d.resolve();
                    }.bind(this)
                );

                current.addClass("uk-slideshow-fade-out");
                current.width(); // force redraw

                return d.promise();
            },
        };

        UI.slideshow.animations = Animations;

        // Listen for messages from the vimeo player
        window.addEventListener(
            "message",
            function onMessageReceived(e) {
                var data = e.data,
                    iframe;

                if (typeof data == "string") {
                    try {
                        data = JSON.parse(data);
                    } catch (err) {
                        data = {};
                    }
                }

                if (
                    e.origin &&
                    e.origin.indexOf("vimeo") > -1 &&
                    data.event == "ready" &&
                    data.player_id
                ) {
                    iframe = UI.$('[data-player-id="' + data.player_id + '"]');

                    if (iframe.length) {
                        iframe.data("slideshow").mutemedia(iframe);
                    }
                }
            },
            false
        );
    };
})();
