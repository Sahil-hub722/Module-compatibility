/**
 * Initialization widget for slider
 *
 * @method resizeBanner
 */
define([
    'jquery',
    'uikit!slideshow',
    'moment'
], function($, UIkit, moment) {
    "use strict";

    $.widget('mage.awRbslider', {
        options: {
            autoplay: true,
            pauseTimeBetweenTransitions: 3000,
            slideTransitionSpeed: 500,
            isStopAnimationMouseOnBanner: true,
            animation: 'fade',
            isRandomOrderImage: false,
            sliderListSelector: '.uk-slideshow',
            sliderItemSelector: '.aw-rbslider-item'
        },

        /**
         * Initialize widget
         */
        _create: function () {
            var self = this,
                slideshow;

            /*if (self.options.isRandomOrderImage) {
                self._randomSort();
            }*/

            UIkit.on('init.uk.component', function(e, name, data) {
                if (name == 'slideshow') {
                    self.loadSlides(data);
                }
            });
            slideshow = UIkit.slideshow(this.element, {
                autoplay: this.options.autoplay,
                autoplayInterval: this.options.pauseTimeBetweenTransitions,
                duration: this.options.slideTransitionSpeed,
                pauseOnHover: this.options.isStopAnimationMouseOnBanner,
                animation: this.options.animation
            });
            // Rewrite slideshow resize method
            slideshow.resize = function () {
                self.resizeBanner(this);
            };
            // Disable stop animation, if click on slide navigaton or dot navigation
            this.element.on('click.uk.slideshow', '[data-uk-slideshow-item]', function(e) {
                if (slideshow.options.autoplay) {
                    slideshow.start();
                }
            });
            // Slideshow paused, if mouse cursor on slide navigaton or dot navigation
            this.element.on({
                mouseenter: function() {
                    if (slideshow.options.pauseOnHover) {
                        slideshow.hovering = true;
                    }
                },
                mouseleave: function() {
                    slideshow.hovering = false;
                }
            }, '.uk-dotnav, .uk-slidenav');

            if (this.options.bannerSchedule.length > 0) {
                if (!this.timeValidator([this.options.bannerSchedule[0]])) {
                    this.delayedUpdate(this.options.bannerSchedule[0]);
                }
                if (this.timeValidator(this.options.bannerSchedule)) {
                    this.sendRequest(this.options.bannerId);
                }
            }
        },

        /**
         * Recalculate the width and height of the banner
         */
        resizeBanner: function(slideshow) {
            var mainContent = this.element.closest('#maincontent, .page-wrapper'),
                width,
                height = slideshow.options.height;

            // Recalculate width
            if (slideshow.slides.length) {
                width = $(slideshow.slides[0]).find('img.aw-rbslider__img').prop('naturalWidth');
            }
            if (mainContent.length) {
                if (mainContent.width() < width) {
                    width = mainContent.width();
                }
                this.element.css('width', width);
            } else if ($('.page-wrapper').length) {
                // AW RBSlider compatibility
                var containerWidth = Math.floor($('.page-wrapper').width() * 0.7);

                if (containerWidth < width) {
                    width = containerWidth;
                }
                this.element.css('width', width);
            }
            // Recalculate height
            if (slideshow.options.height === 'auto' && slideshow.slides.length) {
                slideshow.slides.css('height', '');
                height = $(slideshow.slides[0]).height();
                slideshow.container.css('height', height);
                slideshow.slides.css('height', height);
                slideshow.slides.css('position', 'absolute');
            }
        },

        /**
         * Lazy load slides
         */
        loadSlides: function(slideshow) {
            var slideImg;

            slideshow.slides.each(function (index, slideElem) {
                if (index) {
                    slideImg = $(slideElem).find('img.aw-rbslider__img');
                    if (slideImg.length && slideImg.attr('data-src')) {
                        slideImg.on('load', function () {
                            $(this).addClass('is-loaded');
                        });
                        slideImg.prop('src', slideImg.attr('data-src'));
                        slideImg.attr('data-src', null);
                    }
                }
            });
        },

        /**
         * Compare current time with schedule
         *
         * @param schedule
         */
        timeValidator: function(schedule) {
            var currentDate = moment().utc().format('YYYY-MM-DD HH:mm'),
                isValid = false;

            schedule.forEach(function (element) {
                if (currentDate === moment(element).format('YYYY-MM-DD HH:mm')) {
                    isValid = true;
                }
            });

            return isValid;
        },

        /**
         * Send request for clean banner cache
         *
         * @param bannerId
         */
        sendRequest: function(bannerId) {
            var self = this;

            $.ajax({
                url: self.options.cacheCleanUrl,
                type: 'post',
                async: true,
                dataType: 'json',
                data: {
                    'bannerId': bannerId
                }
            });
        },

        /**
         * Calculate time before send ajax request
         *
         * @param futureTime
         */
        delayedUpdate: function (futureTime) {
            var currentDate = moment().utc().format('YYYY-MM-DD HH:mm'),
                interval = {},
                self = this;

            interval = moment(moment(futureTime,"YYYY-MM-DD HH:mm").diff(moment(currentDate,"YYYY-MM-DD HH:mm")));
            if (interval.valueOf() > 0) {
                setTimeout(function () {
                    self.sendRequest(self.options.bannerId)
                }, interval.valueOf());
            }
        },

        /**
         * Random sort
         * @private
         */
        _randomSort: function () {
            var sliderListSelector = this.options.sliderListSelector,
                sliderItemSelector = this.options.sliderItemSelector;

            $(this.element).find(sliderListSelector)
                .html($(this.element).find(sliderListSelector + ' ' + sliderItemSelector).sort(function(){
                    return Math.random()-0.5;
                }));
        }
    });

    return $.mage.awRbslider;
});
