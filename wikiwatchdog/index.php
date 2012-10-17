<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf8">
    <title>Wiki Watchdog</title>
    <link href="static/stylesheets/bootstrap.min.css" rel="stylesheet">
    <link href="static/stylesheets/bootstrap-responsive.min.css" rel="stylesheet">
    <link href="static/stylesheets/diff.css" rel="stylesheet">
    <link href="static/stylesheets/style.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Federico 'fox' Scrinzi">
  </head>
  <body>

    <?php
      if (isset($_GET["_escaped_fragment_"])) {
        $param = $_GET["_escaped_fragment_"];
        preg_match('/search\/([^\/]+)\/([^\/]+)(\/.*)?/', $param, $matches);
        $content = file_get_contents('http://toolserver.org/~sonet/cgi-bin/watchdog.py?lang='.$matches[1].'&domain='.$matches[2]);
        $json = json_decode($content);
        echo "<ul>";
        foreach ($json->pages as $page) {
          echo "<li>".$page->title."</li>";
        }
        echo "</ul>";
      }
    ?>

    <div class="container-fluid">

      <div id="content">
      </div>

      <hr>

      <footer class="center">
        <p>
          <!--[if lte IE 8]><span style="filter: FlipH; -ms-filter: "FlipH"; display: inline-block;"><![endif]-->
          <span style="-moz-transform: scaleX(-1); -o-transform: scaleX(-1); -webkit-transform: scaleX(-1); transform: scaleX(-1); display: inline-block;">
            &copy;
         </span>
          <!--[if lte IE 8]></span><![endif]-->
          <a href="http://volpino.github.com">Federico "fox" Scrinzi</a> of <a href="http://sonet.fbk.eu">SoNet@FBK</a>. We love but are not affiliated with Wikipedia. WikiWatchdog is <a href="https://github.com/volpino/WikiWatchdog">free software!</a>.
        </p>
        <p>Powered by <a href="http://toolserver.org">Wikimedia Toolserver</a></p>
      </footer>
    </div>

    <div id="loading" class="hide">
      <div class="whole-page">
        <div id="spin">
          <h3 class="libertine center margin-top60">Your query is running... This may take a while</h3>
      </div>
    </div>

    <script type="text/template" id="home-template">
      <div class="row-fluid margin-top60">
        <div class="span2">
        </div>
        <div class="span8">
          <div class="well well-white">
            <h1 class="libertine center">Wiki Watchdog</h1>
            <p>
              Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam viverra dui id ipsum auctor et vulputate risus porta.
              Etiam a nulla sed neque porttitor aliquam. Ut eu leo at erat convallis accumsan. Suspendisse arcu ipsum, egestas vitae vehicula quis, rhoncus id libero.
            </p>

            <form id="search-form" class="form-inline center margin-top40">
              Search edits by
              <input placeholder="a domain name (e.g.: fbk.eu)" id="search-text" type="text" class="input-xlarge nomargin">
              on the
              <select class="input-medium" id="search-lang">
                <% for (lang in wiki_lang) { %>
                  <option value="<%= lang %>" <% if (lang === "en") { %>selected="selected"<% } %>>
                    <%= wiki_lang[lang] %> Wikipedia
                  </option>
                <% } %>
              </select>
              <input id="search-button" type="submit" class="btn nomargin margin-left10" value="Search">
            </form>

            <div id="alert" class="alert alert-error hide">
              <strong>Error!</strong> <span class="alert-msg"></span>
            </div>

            <div id="alert-warning" class="alert hide">
              <strong>We're sorry!</strong> <span class="alert-msg"></span>
            </div>

          </div>
        </div>
      </div>

      <hr>

      <div class="row-fluid">
        <div class="span4">
          <h4 class="libertine">What's Wiki Watchdog?</h4>
          Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam viverra dui id ipsum auctor et vulputate risus porta. Etiam a nulla sed neque porttitor aliquam. Ut eu leo at erat convallis accumsan. Suspendisse arcu ipsum, egestas vitae vehicula quis, rhoncus id libero. Vivamus ullamcorper sapien molestie magna convallis sit amet facilisis magna vulputate. Cras et pulvinar sem. Integer posuere interdum eros ut pharetra. Pellentesque eu ante pulvinar leo volutpat dictum
        </div>

        <div class="span4">
          <h4 class="libertine">Authors</h4>
          Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam viverra dui id ipsum auctor et vulputate risus porta. Etiam a nulla sed neque porttitor aliquam. Ut eu leo at erat convallis accumsan. Suspendisse arcu ipsum, egestas vitae vehicula quis, rhoncus id libero. Vivamus ullamcorper sapien molestie magna convallis sit amet facilisis magna vulputate. Cras et pulvinar sem. Integer posuere interdum eros ut pharetra. Pellentesque eu ante pulvinar leo volutpat dictum.
        </div>

        <div class="span4">
          <h4 class="libertine">Free and Open source API</h4>
          Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam viverra dui id ipsum auctor et vulputate risus porta. Etiam a nulla sed neque porttitor aliquam. Ut eu leo at erat convallis accumsan. Suspendisse arcu ipsum, egestas vitae vehicula quis, rhoncus id libero. Vivamus ullamcorper sapien molestie magna convallis sit amet facilisis magna vulputate. Cras et pulvinar sem. Integer posuere interdum eros ut pharetra. Pellentesque eu ante pulvinar leo volutpat dictum.
        </div>
      </div>
    </script>

    <script type="text/template" id="search-template">
      <h2 class="libertine"><a class="no-style" href="#">Wiki Watchdog</a></h2>

      <form id="search-form" class="form-inline">
        <input id="search-text" type="text" class="nomargin" value="<%= toSearch %>">
        on the
        <select class="input-medium" id="search-lang">
          <% for (l in wiki_lang) { %>
            <option value="<%= l %>" <% if (l === lang) { %>selected="selected"<% } %>>
              <%= wiki_lang[l] %> Wikipedia
            </option>
          <% } %>
        </select>
        <input id="search-button" type="submit" class="btn nomargin margin-left10" value="Search">
      </form>

      <hr>

      <h4>
        Articles edited anonymously by <%= toSearch %> on the
        <%= wiki_lang[lang] %> Wikipedia
      </h4>

      <p>
        <%= pages.length %> articles (<%= total_edits %> edits)
        - Query executed in <%= Math.round(stats.execution_time * 100) / 100 %> sec.
      </p>

      <div class="row-fluid" id="top-anchor">
        <div class="span4">
          <ul class="page-list nav nav-tabs nav-stacked">
          </ul>
        </div>

        <div class="span8 height100" id="diff-area">
        </div>
      </div>
    </script>

    <script type="text/template" id="page-list-template">
      <% for (var i=0; i<pages.length; i++) { %>
        <% var edits = pages[i].edits; %>
        <% var title = pages[i].title; %>
        <li>
          <a class="page-title"
             id="page-<%= pages[i].id %>"
             data-page="<%= title %>"
             data-page-id="<%= pages[i].id %>"
             data-lang="<%= lang %>"
             href="#"
             target="_blank">

            <i class="icon-chevron-down pull-right"></i>
            <i class="icon-chevron-up hide pull-right"></i>

            <%= window.prettyTitle(title) %>
            <p class="small">
              (<%= edits.length %>
              edit<% if (edits.length > 1) { %>s<% } %>)
            </p>
          </a>
          <ul class="edit-list well well-white hide">
            <% for (var j=0; j<edits.length; j++) { %>
              <% var edit = edits[j]; %>
              <li>
                <a class="edit-item"
                   id="revid-<%= edit.id %>"
                   data-revid="<%= edit.id %>"
                   data-timestamp="<%= edit.timestamp %>"
                   data-ip="<%= edit.ip %>"
                   data-domain="<%= edit.domain %>"
                   data-comment="<%= edit.comment %>"ent="<%= edit.comment %>"
                   href="#">
                    <%= window.prettyTimestamp(edit.timestamp) %>
                </a>
                <p class="small">
                  <%= edit.ip %>
                  <% if (edit.domain) { %>
                    (<%= edit.domain %>)
                  <% } %>
                </p>
              </li>
            <% } %>
          </ul>
        </li>
      <% } %>
      <li>
        <span class="load-more btn center visible-phone">Load more...</span>
      </li>
    </script>

    <script type="text/template" id="diff-clear-template">
      <div class="block" style="height: 350px;">
        <div class="centered">
          <h5>
            Please, select an article from the list
          </h5>
        </div>
      </div>
    </script>

    <script type="text/template" id="diff-template">
      <h3>
        <%= prettyTitle(page) %>

        <a class="visible-phone font13 black" id="go-to-top" href="#top-anchor" data-to-top="#page-<%= pageId %>">Back to edit list ↑</a>

        <span class="wikilinks hidden-phone pull-right">
          <a href="http://<%= lang %>.wikipedia.org/wiki/<%= page %>" target="_blank" rel="tooltip" title="Read on Wikipedia" class="libertine">W</a>
          ~
          <a href="http://manypedia.com/#|<%= lang %>|<%= page %>" target="_blank" rel="tooltip" title="Compare on Manypedia" class="libertine">M</a>
          ~
          <a href="http://sonetlab.fbk.eu/wikitrip/#|<%= lang %>|<%= page %>" target="_blank" rel="tooltip" title="History on WikiTrip" class="libertine">T</a>
        </span>
      </h3>

      <hr>

      <% if (error) { %>
        <p>There was an error</p>
      <% } else if (diff === "") { %>
        <p id="article-intro"></p>
        <p id="article-link" class="hide">
          <a class="black bold" href="http://<%= lang %>.wikipedia.org/wiki/<%= page %>" target="_blank">Continue on Wikipedia...</a>
        </p>
      <% } else { %>

        <div class="row-fluid">
          <div class="span8">
            <a class="black" rel="tooltip" title="See edit on Wikipedia" target="_blank"
               href="http://<%= lang %>.wikipedia.org/w/index.php?title=<%= page %>&oldid=<%= edit.revid %>">
              <%= prettyTimestamp(edit.timestamp) %>
              -
              <%= edit.ip %> (<%= edit.domain %>)
            </a>
          </div>
          <div class="span4">
            <ul class="inline-list hidden-phone">
              <li>
                <a href="https://twitter.com/share" class="twitter-share-button" data-text="<%= prettyTitle(page) %> - <%= toSearch %> on @Wikiwatchdog" data-count="none">Tweet</a>
              </li>
              <li>
                <div class="fb-like" data-send="false" data-layout="button_count" data-width="450" data-show-faces="false"></div>
              </li>
            </ul>
          </div>
        </div>
        <% if (edit.comment) { %>
          <p>Edit comment: <em><%= edit.comment %></em></p>
        <% } %>
      <% } %>

      <table class="diff diff-contentalign-left">
        <colgroup><col class="diff-marker">
          <col class="diff-content">
          <col class="diff-marker">
          <col class="diff-content">
        </colgroup><tbody>
        <tbody id="diff-area">
          <%= diff %>
        </tbody>
      </table>
    </script>

    <script src="http://code.jquery.com/jquery-latest.js"></script>
    <script src="static/javascripts/bootstrap.min.js"></script>

    <script src="static/javascripts/underscore-min.js"></script>
    <script src="static/javascripts/backbone-min.js"></script>

    <script src="static/javascripts/spin.min.js"></script>

    <!-- Facebook sdk -->
    <div id="fb-root"></div>
    <script>(function(d, s, id) {
      var js, fjs = d.getElementsByTagName(s)[0];
      if (d.getElementById(id)) return;
      js = d.createElement(s); js.id = id;
      js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=237970542997906";
      fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>

    <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>

    <script>
      var _gaq = _gaq || [];
      _gaq.push(['_setAccount', 'UA-56174-30']);
      _gaq.push(['_trackPageview']);

      (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
      })();
    </script>

    <script src="static/javascripts/wiki_langs.js"></script>

    <script src="static/javascripts/wikiwatchdog.js"></script>

    <script src="static/javascripts/views.js"></script>
    <script src="static/javascripts/routes.js"></script>
  </body>
</html>