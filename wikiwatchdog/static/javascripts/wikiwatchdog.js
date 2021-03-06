$(function () {
  $("#go-to-top").live("click", function (e) {
    $(window).scrollTop($($(this).data('to-top')).offset().top);
    e.preventDefault();
    e.stopPropagation();
    return false;
  });

  $(".api-modal-open").live("click", function (e) {
    $("#api-modal").modal();
    e.preventDefault();
    e.stopPropagation();
    return false;
  });

  $(".edit-item-li").live("click", function () {
    $(this).find(".edit-item").click();
  });

  $(".page-title").live("click", function (e) {
    var $this = $(this)
      , $editList = $this.siblings(".edit-list")
      , page = $this.data("page")
      , pageId = $this.data("page-id")
      , lang = $this.data("lang")
      , opts
      , $tmp;

    if ($editList.is(":visible")) {
      window.diffView.clear();
      $editList.slideUp();

      $this.find(".icon-chevron-up").hide();
      $this.find(".icon-chevron-down").show();

      window.router.navigate("!search/" + lang + "/" + window.toSearch, {trigger: false});
      window.setTitle(lang, window.toSearch);
    }
    else {
      window.router.navigate("!search/" + lang + "/" + encodeURI(window.toSearch) + "/" + pageId, {trigger: false});
      window.setTitle(lang, window.toSearch, pageId);

      window.diffView.render(
        {page: page, pageId: pageId, diff: "", error: false, edit: "", lang: lang}
      );

      // close already open lists
      $tmp = $(".edit-list:visible");
      $tmp.hide(0);
      $tmp = $tmp.siblings(".page-title")
      $tmp.find(".icon-chevron-up").hide();
      $tmp.find(".icon-chevron-down").show();

      $editList.slideDown("normal", function () {
        var $pageList = $(".page-list");
        if ($pageList.css("max-height") !== "none") {  // is not mobile
          if (!window.isVisibleOverflow($this, $pageList, 75)) {
            $pageList.scrollTop($pageList.scrollTop() + $this.position().top);
          }
        }
        else {
          if (!window.isVisible($this, $pageList)) {
            $(window).scrollTop($this.offset().top);
          }
        }
      });

      $this.find(".icon-chevron-down").hide();
      $this.find(".icon-chevron-up").show();

      opts = {
        action: "query",
        prop: "extracts",
        exsentences: 5,
        explaintext: true,
        titles: page,
        format: "json"
      };
      $.getJSON("http://" + lang + ".wikipedia.org/w/api.php?callback=?", opts, function (data) {
        var intro
          , pages = data.query.pages
        for (p in pages) {
          intro = pages[p].extract;
        }
        $("#article-intro").text(intro);
      });
    }

    e.preventDefault();
    e.stopPropagation();
  });

  $(".edit-item").live("click", function (e) {
    var $this = $(this)
      , revId = $this.data("revid")
      , $editList = $this.closest(".edit-list")
      , $pageTitle = $editList.siblings(".page-title")
      , page = $pageTitle.data("page")
      , pageId = $pageTitle.data("page-id")
      , lang = $pageTitle.data("lang")
      , ip = $this.data("ip")
      , domain = $this.data("domain")
      , timestamp = $this.data("timestamp")
      , comment = $this.data("comment")
      , opts = {
        action: "query",
        prop: "revisions",
        revids: revId,
        rvdiffto: "prev",
        format: "json"
      };

    $editList.find(".edit-selected").removeClass("edit-selected");
    $this.closest(".edit-item-li").addClass("edit-selected");

    $.getJSON("http://" + lang + ".wikipedia.org/w/api.php?callback=?", opts, function (data) {
      for (pageId in data.query.pages) {
        var diffData = {}
          , revData = data.query.pages[pageId].revisions[0];
        if (!revData.diff.from || !revData.diff["*"])
          diffData.error = true;
        else
          diffData.error = false;

        diffData.diff = revData.diff["*"];
        diffData.page = page;
        diffData.pageId = pageId;
        diffData.lang = lang;
        diffData.edit = {
          revid: revId,
          ip: ip,
          domain: domain,
          timestamp: timestamp,
          comment: comment,
        }
        window.diffView.render(diffData);
      }

      if (!isVisible($("#diff-area").find("h3").eq(0))) {
        // for mobile scroll to content
        $(window).scrollTop($("#diff-area").offset().top);
      }
    })

    window.router.navigate("!search/" + lang + "/" + window.toSearch + "/" + pageId + "/" + revId, {trigger: false});
    window.setTitle(lang, window.toSearch, pageId, revId);

    e.preventDefault();
    e.stopPropagation();
  });
});

window.findPage = function (pageId, revId) {
  var $page = $("#page-" + pageId);
  if ($page.length !== 0) {
    $page.click();
    if (revId)
      $("#revid-" + revId).click();
  }
  else {
    $(".load-more").click();
    $(".page-list").on("scroll-loaded", function () {
      window.findPage(pageId, revId);
    });
  }
};

var opts = {
  lines: 9, // The number of lines to draw
  length: 30, // The length of each line
  width: 11, // The line thickness
  radius: 31, // The radius of the inner circle
  corners: 1, // Corner roundness (0..1)
  rotate: 0, // The rotation offset
  color: '#000', // #rgb or #rrggbb
  speed: 1, // Rounds per second
  trail: 60, // Afterglow percentage
  shadow: true, // Whether to render a shadow
  hwaccel: false, // Whether to use hardware acceleration
  className: 'spinner', // The CSS class to assign to the spinner
  zIndex: 2e9, // The z-index (defaults to 2000000000)
  top: 'auto', // Top position relative to parent in px
  left: 'auto' // Left position relative to parent in px
};
window.spinner = new Spinner(opts);

window.apiUrl = "http://toolserver.org/~sonet/cgi-bin/watchdog.py?callback=?";
window.apiPageLimit = 200;

window.showLoading = function () {
  $("#loading").fadeIn();
  window.spinner.spin($("#spin")[0]);
};

window.hideLoading = function () {
  $("#loading").fadeOut();
};

window.prettyTimestamp = function (ts) {
  var ts = ts + ""
    , year = ts.slice(0, 4)
    , month = ts.slice(4, 6)
    , day = ts.slice(6, 8)
    , hour = ts.slice(8, 10)
    , minutes = ts.slice(10, 12)
    , seconds = ts.slice(12, 14);
  return year + "-" + month + "-" + day + " at " + hour + ":" + minutes + ":" + seconds;
};

window.prettyTitle = function (title) {
  return title.replace(/_/g, " ");
};

window.isVisibleOverflow = function ($element, $scrollable, off) {
  var off = off || 0
    , docViewTop = 0 //$scrollable.position().top
    , docViewBottom = docViewTop + $scrollable.height()
    , elemTop = $element.position().top
    , elemBottom = elemTop + $element.height() + off;
  return ((elemBottom <= docViewBottom) && (elemTop >= docViewTop));
};

window.isVisible = function ($element) {
  var docViewTop = $(window).scrollTop()
    , docViewBottom = docViewTop + $(window).height()
    , elemTop = $element.offset().top
    , elemBottom = elemTop + $element.height();
  return ((elemBottom <= docViewBottom) && (elemTop >= docViewTop));
};

window.isIP = function (string) {
  return string.match(/[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}/);
};

window.setTitle = function (lang, domain, pageId, revId) {
  var msg;

  if (!lang && !domain && !pageId && !revId) {
    msg = "WikiWatchdog";
  }
  else {
    msg = "WikiWatchdog - Edits";

    if (pageId) {
      msg += ' to "' +
             window.prettyTitle($("#page-" + pageId + ".page-title").data("page")) +
             '"';
    }
    if (domain) {
      msg += " by " + domain;
    }
    if (lang) {
      msg += " on the " + wiki_lang[lang] + " Wikipedia";
    }
  }

  document.title = msg;

  if (window.stMini) {
    $("#sthoverbuttons").remove();
    stMini.initWidget();
  }
}
