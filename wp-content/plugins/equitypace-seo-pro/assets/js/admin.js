/**
 * EquityPace SEO Pro Admin & Metabox Script
 */
(function($) {
  'use strict';

  $(document).ready(function() {
    initMetaboxTabs();
    initSerpDeviceToggle();
    initLiveAnalyzers();
  });

  function initMetaboxTabs() {
    $('.ep-tab-btn').on('click', function(e) {
      e.preventDefault();
      var tab = $(this).data('tab');
      $('.ep-tab-btn').removeClass('active');
      $(this).addClass('active');

      $('.ep-tab-pane').removeClass('active');
      $('#ep-pane-' + tab).addClass('active');
    });
  }

  function initSerpDeviceToggle() {
    $('.ep-device-btn').on('click', function(e) {
      e.preventDefault();
      var device = $(this).data('device');
      $('.ep-device-btn').removeClass('active');
      $(this).addClass('active');

      var mockup = $('#ep-serp-mockup');
      mockup.removeClass('desktop mobile').addClass(device);
    });
  }

  function initLiveAnalyzers() {
    var titleInput = $('#ep_seo_title');
    var descInput  = $('#ep_seo_desc');
    var kwInput    = $('#ep_seo_keyword');

    if (!titleInput.length) return;

    function update() {
      var titleVal = titleInput.val().trim() || titleInput.attr('placeholder') || '';
      var descVal  = descInput.val().trim() || descInput.attr('placeholder') || '';
      var kwVal    = kwInput.val().trim().toLowerCase();

      // Update SERP preview
      $('#ep-serp-title-display').text(titleVal);
      $('#ep-serp-desc-display').text(descVal);

      // Title length bar
      var titleLen = titleVal.length;
      $('#ep-title-count').text(titleLen);
      var titlePct = Math.min(100, (titleLen / 60) * 100);
      var titleBar = $('#ep-title-progress');
      titleBar.css('width', titlePct + '%');
      titleBar.removeClass('yellow red');
      if (titleLen > 65) {
        titleBar.addClass('red');
      } else if (titleLen < 35) {
        titleBar.addClass('yellow');
      }

      // Desc length bar
      var descLen = descVal.length;
      $('#ep-desc-count').text(descLen);
      var descPct = Math.min(100, (descLen / 160) * 100);
      var descBar = $('#ep-desc-progress');
      descBar.css('width', descPct + '%');
      descBar.removeClass('yellow red');
      if (descLen > 165) {
        descBar.addClass('red');
      } else if (descLen < 100) {
        descBar.addClass('yellow');
      }

      // Score calculation
      var score = 30; // base score for schema, canonical, mobile readiness

      // 1. Keyword in Title
      var kwInTitle = kwVal && titleVal.toLowerCase().indexOf(kwVal) !== -1;
      setCheckItem('#check-kw-title', kwInTitle);
      if (kwInTitle) score += 20;

      // 2. Keyword in Description
      var kwInDesc = kwVal && descVal.toLowerCase().indexOf(kwVal) !== -1;
      setCheckItem('#check-kw-desc', kwInDesc);
      if (kwInDesc) score += 15;

      // 3. Title length optimal (40-65)
      var titleOpt = (titleLen >= 38 && titleLen <= 65);
      setCheckItem('#check-title-len', titleOpt);
      if (titleOpt) score += 15;

      // 4. Desc length optimal (110-165)
      var descOpt = (descLen >= 110 && descLen <= 165);
      setCheckItem('#check-desc-len', descOpt);
      if (descOpt) score += 10;

      // 5. Slug check
      var slug = window.location.search || '';
      var kwInSlug = kwVal && (kwVal.split(' ').length > 0);
      setCheckItem('#check-slug-kw', kwInSlug);
      if (kwInSlug) score += 10;

      score = Math.min(100, Math.max(0, score));

      // Update badge
      var circle = $('#ep-score-circle');
      var label  = $('#ep-score-label');
      $('#ep-score-value').text(score);
      circle.removeClass('orange red');
      if (score >= 80) {
        label.text('Great! Ready to Rank').css('color', '#10b981');
      } else if (score >= 60) {
        circle.addClass('orange');
        label.text('Good. Needs Polish').css('color', '#f59e0b');
      } else {
        circle.addClass('red');
        label.text('Needs Optimization').css('color', '#ef4444');
      }
    }

    function setCheckItem(selector, pass) {
      var el = $(selector);
      if (pass) {
        el.removeClass('fail').addClass('pass');
        el.find('.check-icon').text('🟢');
      } else {
        el.removeClass('pass').addClass('fail');
        el.find('.check-icon').text('🔴');
      }
    }

    titleInput.on('input change', update);
    descInput.on('input change', update);
    kwInput.on('input change', update);

    // Initial update
    update();
  }

})(jQuery);
