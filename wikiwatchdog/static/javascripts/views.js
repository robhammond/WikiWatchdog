var HomeView = Backbone.View.extend({
  template: _.template($('#home-template').html()),
  el: "#content",

  render: function () {
    $(this.el).html(this.template());
    $("#search-text").focus();
    return this;
  },

  events: {
    "submit #search-form": "search"
  },

  search: function () {
    var toSearch = $("#search-text").val()
      , lang = $("#search-lang").val();

    window.router.navigate("search/" + lang + "/" + toSearch, {trigger: true});
    return this
  }
});


var SearchView = Backbone.View.extend({
  template: _.template($('#search-template').html()),
  el: "#content",

  render: function (lang, toSearch, data) {
    var json_data = data;
    json_data.toSearch = toSearch;
    json_data.lang = lang;

    $(this.el).html(this.template(json_data));

    window.diffView.clear();
    return this;
  },

  events: {
    "submit #search-form": "search"
  },

  search: function () {
    var toSearch = $("#search-text").val()
      , lang = $("#search-lang").val();

    window.router.navigate("search/" + lang + "/" + toSearch, {trigger: true});
    return this
  }

});


var DiffView = Backbone.View.extend({
  template: _.template($('#diff-template').html()),
  templateClear: _.template($('#diff-clear-template').html()),

  render: function (data) {
    $("#diff-area").html(this.template(data));
    $("[rel=tooltip]").tooltip();
    return this;
  },

  clear: function () {
    $("#diff-area").html(this.templateClear());
    return this;
  }
});

window.homeView = new HomeView();
window.searchView = new SearchView();
window.diffView = new DiffView();