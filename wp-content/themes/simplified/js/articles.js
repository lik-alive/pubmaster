$(document).ready(function () {
  //-----Init page
  {
    var table = $("#articlestable").DataTable();

    //Load search keyword from saved state
    //var skw = table.state.loaded().search.search;
    //$('#searchInput').val(skw.substring(0, skw.indexOf('|')));
    //Clear fast search keyword from saved state
    table.search("");
    table.draw();

    initSearchPanel();
    AddStatusMsg([1, "Загрузка данных..."], false, false);
  }

  //Init search panel from GET-parameters
  function initSearchPanel() {
    //Clear all checkboxes
    $('.search-panel input[type="checkbox"]').each(function () {
      $(this).prop("checked", false);
    });

    if ("authors" in dt_filter) {
      dt_filter.authors.forEach(a => {
        $('#authors input[value="' + a + '"').prop("checked", true);
      });
      orderChecked("authors");
    }

    if ("years" in dt_filter) {
      dt_filter.years.forEach(a => {
        $('#years input[value="' + a + '"').attr("checked", true);
      });
      orderChecked("years");
    }

    if ("indexes" in dt_filter) {
      dt_filter.indexes.forEach(a => {
        $('#indexes input[value="' + a + '"').attr("checked", true);
      });
      orderChecked("indexes");
    }

    if ("keywords" in dt_filter) {
      dt_filter.keywords.forEach(a => {
        $('#keywords input[value="' + a + '"').attr("checked", true);
      });
      orderChecked("keywords");
      $(".search-collapser").trigger("click");
    }
  }

  //History back/forward handler
  window.addEventListener("popstate", function (e) {
    if (e.state === null || e.state.filter === null) dt_filter = {};
    else dt_filter = e.state.filter;

    initSearchPanel();
    table.ajax.reload();
  });

  //On data loaded
  table.on("draw.dt", function () {
    UpdateLastStatusMsg(
      [1, "Найдено записей: " + table.page.info().recordsDisplay],
      false,
    );
  });

  //Fast search
  $("#searchInput").bind("input propertychange", function () {
    var val = this.value;
    var valC = changeKeyboardLayout(val);

    table.search(escapeRegExp(val) + "|" + escapeRegExp(valC), true, false);
    table.draw();
  });

  //*****Actions

  //Add new article
  $("#newarticle").on("click", function () {
    window.location.href = SITE_URL + "/articles/wizard";
  });

  //Enable/disable export and download selected
  $(".mainchecker").change(function () {
    $(".exportsel").prop(
      "disabled",
      !$(this).prop("indeterminate") && !$(this).prop("checked"),
    );
    $(".downloadsel").prop(
      "disabled",
      !$(this).prop("indeterminate") && !$(this).prop("checked"),
    );
  });

  //Export data
  $(".export").click(function () {
    var arr = [];
    if ($(this).hasClass("exportall")) {
      table.data().each(a => arr.push(parseInt(a.ID_Article)));
    } else {
      table.rows().every(function (rowIdx, tableLoop, rowLoop) {
        var input = $(this.node()).find("input");
        if (input.prop("checked")) {
          arr.push(parseInt(this.data().ID_Article));
        }
      });
    }

    RedirectWithData("get", SITE_URL + "/export", "ID_Articles", arr, "_blank");
  });

  //Download files
  $(".download").click(function () {
    var arr = [];
    if ($(this).hasClass("downloadall")) {
      table.data().each(a => arr.push(parseInt(a.ID_Article)));
    } else {
      table.rows().every(function (rowIdx, tableLoop, rowLoop) {
        var input = $(this.node()).find("input");
        if (input.prop("checked")) {
          arr.push(parseInt(this.data().ID_Article));
        }
      });
    }

    RedirectWithData(
      "get",
      SITE_URL + "/download",
      "ID_Articles",
      arr,
      "_blank",
    );
  });

  //*****Search panel

  //Change page state on checkbox of search-item change
  $(".search-item").change(function () {
    var authors = [];
    $("#authors")
      .find(".search-item")
      .each(function () {
        if (this.checked) authors.push(parseInt($(this).val()));
      });

    var years = [];
    $("#years")
      .find(".search-item")
      .each(function () {
        if (this.checked) years.push(parseInt($(this).val()));
      });

    var indexes = [];
    $("#indexes")
      .find(".search-item")
      .each(function () {
        if (this.checked) indexes.push(parseInt($(this).val()));
      });

    var keywords = [];
    $("#keywords")
      .find(".search-item")
      .each(function () {
        if (this.checked) keywords.push($(this).val());
      });

    dt_filter = {};

    if (authors.length > 0) {
      dt_filter.authors = authors;
      orderChecked("authors");
    }
    if (years.length > 0) {
      dt_filter.years = years;
      orderChecked("years");
    }
    if (indexes.length > 0) {
      dt_filter.indexes = indexes;
      orderChecked("indexes");
    }
    if (keywords.length > 0) {
      dt_filter.keywords = keywords;
      orderChecked("keywords");
    }

    var filterstr = ".";
    if (Object.keys(dt_filter).length > 0)
      filterstr = "?filter=" + JSON.stringify(dt_filter);

    window.history.pushState({ filter: dt_filter }, null, filterstr);

    $("#searchInput").val("");
    table.search("");
    table.ajax.reload();
  });

  //Fast search for keywords
  $("#kwsearch").bind("input propertychange", function () {
    var val = $(this).val();
    var valC = changeKeyboardLayout(val);

    $("#keywords .search-item").each(function () {
      var name = $(this).attr("name");

      if (name.includes(val) || name.includes(valC))
        $(this).closest(".cb-container").show();
      else $(this).closest(".cb-container").hide();
    });
  });

  //Arrange checked values
  function orderChecked(idname) {
    $("#" + idname + " .search-item").each(function () {
      if (!this.checked) {
        var items = $(this).closest(".search-items");
        var item = $(this).closest(".cb-container");
        items.append(item);
      }
    });
  }

  //Change arrow for search collapser
  $(".search-collapser").click(function () {
    var div = $(this).next();
    var zmdi = $(this).find(".zmdi");
    if (div.hasClass("show")) {
      zmdi.removeClass("zmdi-chevron-up");
      zmdi.addClass("zmdi-chevron-down");
    } else if (div.hasClass("collapse")) {
      zmdi.removeClass("zmdi-chevron-down");
      zmdi.addClass("zmdi-chevron-up");
    }
  });

  //Mobile view
  $(".search-unroll").click(function () {
    $(".search-panel").addClass("search-fullwidth");
  });

  $(".search-roll").click(function () {
    $(".search-panel").removeClass("search-fullwidth");
  });
});
