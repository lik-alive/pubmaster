$(document).ready(function () {
  var ID_Article = searchParams.get("id");

  var pdfFM = new FileManager(
    FileManagerOptions.Upload,
    FileManagerOptions.Closeable,
    FileManagerOptions.OnlyPdf,
  );
  pdfFM.embedObject($("#pdffile"));

  //Init file
  {
    if (ID_Article !== "") {
      $.get(
        ADMIN_URL,
        { action: "files_get_article_pdf_json", id: ID_Article },
        function (response) {
          var data = JSON.parse(response).data;
          if (data !== null) pdfFM.addFiles([data]);
        },
      );
    }
  }

  //Clear form data
  function clearfields() {
    $("#createForm")
      .find('input[type="text"], input[type="number"], select, textarea')
      .each(function () {
        $(this).val("");
      });
    $("#createForm")
      .find('input[type="checkbox"]')
      .each(function () {
        $(this).prop("checked", false);
      });
    $("#authors tbody tr").each(function () {
      $(this).remove();
    });
    $("#authors").hide();
  }

  //Recognize reference data on server side
  $("#recognize").on("click", function () {
    $.post(
      ADMIN_URL,
      { action: "articles_at_recognize_json", str: $("#text")[0].value },
      function (response) {
        var data = response.data;

        clearfields();

        $("#ATitle").val(stopShouting(data.Article.Title));
        $("#ATitleAlt").val(stopShouting(data.Article.TitleAlt));
        data.Authors.forEach(a =>
          addAuthor({
            surname: a.Surname,
            initials: a.Initials,
            seqno: a.SeqNo,
          }),
        );
        $("#APageFrom").val(data.Article.PageFrom);
        $("#APageTo").val(data.Article.PageTo);
        $("#ADOI").val(data.Article.DOI);

        setJournal({
          title: data.Journal.Title,
          city: data.Journal.City,
          publisher: data.Journal.Publisher,
          indexing: data.Journal.Indexing,
        });
        setIssue({
          year: data.Issue.Year,
          vol: data.Issue.VolumeNo,
          no: data.Issue.IssueNo,
        });
        LoadPopularAuthors();
      },
      "json",
    );
  });

  // Auto-recognize on startup
  if ($("#text").val().length) {
    $("#recognize").click();
  }

  //Capitalize only the first letter
  function stopShouting(str) {
    if (str === undefined) return null;

    if (str.toUpperCase() === str) {
      str = str.toLowerCase();
      str = str.charAt(0).toUpperCase() + str.slice(1);
    }
    return str;
  }

  //Capitalize only the first letter in titles
  $("#ATitle, #ATitleAlt").bind("input propertychange", function () {
    $(this).val(stopShouting($(this).val()));
  });

  //Load top authors on title change
  $("#ATitle").bind("input propertychange", function () {
    LoadPopularAuthors();
  });

  //Format keywords
  $("#AKeywords").bind("input propertychange", function () {
    var val = $(this).val();
    val = val.replace(/^ /g, "");
    val = val.replace(/\./g, "");
    val = val.replace(/[,;]/g, "\n");
    val = val.replace(/\n /g, "\n");
    $(this).val(val);
  });

  //Load most frequent authors
  var langRu = true;

  function LoadPopularAuthors() {
    if (
      $("#ATitle").val() === "" ||
      strIsMostlyRus($("#ATitle").val()) === langRu
    )
      return;

    langRu = strIsMostlyRus($("#ATitle").val());

    $.get(
      ADMIN_URL,
      { action: "authors_list_top_json", lang: langRu ? "ru" : "en" },
      function (response) {
        var data = JSON.parse(response).data;

        $("#authorPop").empty();
        for (var i = 0; i < data.length; i++) {
          var name = data[i].FullName;
          var surname = data[i].Surname;
          var initials = data[i].Initials;
          var btn = $(
            "<button type='button' data-surname='" +
              surname +
              "' data-initials='" +
              initials +
              "'  class='btn btn-outline-info mt-1 mr-1 addauthor'>" +
              name +
              "</button>",
          );
          $("#authorPop").append(btn);
        }
      },
    );
  }

  $("body").on("click", ".addauthor", function () {
    addAuthor($(this).data());
  });

  //Find author by name
  $("#authorSearch").autocomplete({
    minLength: 1,
    source: function (request, response) {
      $.get(
        ADMIN_URL,
        { action: "authors_find_json", kw: request.term },
        function (jsondata) {
          var authors = [];
          var data = jsondata.data;
          for (var i = 0; i < data.length; i++) {
            var surname = data[i].Surname;
            var initials = data[i].Initials;
            var name = data[i].FullName;
            authors.push({ surname: surname, initials: initials, label: name });
          }
          authors.push({ label: "+ Добавить нового автора" });

          response(authors);
        },
        "json",
      );
    },
    select: function (event, ui) {
      event.preventDefault();
      if (ui.item.surname === undefined) {
        addAuthor({ surname: $("#authorSearch").val() });
        $("#authors tbody input").last().focus();
      } else addAuthor(ui.item);
      $("#authorSearch").val("");
    },
  });

  //Add author input fields
  function addAuthor(item) {
    if ($("#authors tbody tr").length === 0) $("#authors").show();

    if (item.seqno === undefined)
      item.seqno = $("#authors tbody").children().length + 1;
    if (item.initials === undefined) item.initials = "";

    $("#authors tbody").append(
      "<tr style='height:35px'>" +
        "<td><i class='zmdi zmdi-close-circle zmdi-action' style='vertical-align:middle;font-size:1.3em'></i></td>" +
        "<td class='text-center'>" +
        item.seqno +
        "</td>" +
        "<td><input type='text' name='Surname' value='" +
        item.surname +
        "' required/></td>" +
        "<td><input type='text' name='Initials' value='" +
        item.initials +
        "' required/></td>" +
        "</tr>",
    );
  }

  //Remove author from the list
  $("#authors").on("click", ".zmdi-action", function () {
    var tr = $(this).closest("tr");
    var no = tr.children().eq(1).html();

    tr.remove();
    if ($("#authors tbody tr").length === 0) $("#authors").hide();

    $("#authors tbody tr").each(function () {
      var curno = $(this).children().eq(1).html();
      if (curno > no)
        $(this)
          .children()
          .eq(1)
          .html(curno - 1);
    });
  });

  //Find journal by title
  $("#journalSearch").autocomplete({
    minLength: 1,
    source: function (request, response) {
      $.get(
        ADMIN_URL,
        { action: "articles_journals_find_json", kw: request.term },
        function (jsondata) {
          var journals = [];
          var data = jsondata.data;
          for (var i = 0; i < data.length; i++) {
            var title = jsondata.data[i].Title;
            var city = jsondata.data[i].City;
            var publisher = jsondata.data[i].Publisher;
            var indexing = jsondata.data[i].Indexing;
            var id = jsondata.data[i].ID_Journal;
            journals.push({
              title: title,
              city: city,
              publisher: publisher,
              indexing: indexing,
              id: id,
              label: title,
            });
          }
          if (data.length == 0) journals.push({ label: "Объект не найден" });

          response(journals);
        },
        "json",
      );
    },
    select: function (event, ui) {
      event.preventDefault();
      setJournal(ui.item);
      $("#journalSearch").val("");
      clearIssue();
    },
  });

  //Set journal data
  function setJournal(item) {
    $("#JTitle").val(item.title);
    $("#JCity").val(item.city);
    $("#JPublisher").val(item.publisher);
    $("#JIndexingRSCI").prop("checked", item.indexing & 1);
    $("#JIndexingVAK").prop("checked", item.indexing & 2);
    $("#JIndexingScopus").prop("checked", item.indexing & 4);
    $("#JIndexingWoS").prop("checked", item.indexing & 8);
  }

  //Autoselect VAK for WoS and Scopus
  $("#JIndexingScopus, #JIndexingWoS").on("change", function () {
    if (this.checked) $("#JIndexingVAK").prop("checked", true);
  });

  //Find issue by year or volumeNo or issueNo and by journal title
  $("#issueSearch").autocomplete({
    minLength: 1,
    source: function (request, response) {
      $.get(
        ADMIN_URL,
        {
          action: "articles_issues_find_json",
          kw: request.term,
          jt: $("#JTitle").val(),
        },
        function (jsondata) {
          var issues = [];
          var data = jsondata.data;
          for (var i = 0; i < data.length; i++) {
            var year = jsondata.data[i].Year;
            var vol = jsondata.data[i].VolumeNo;
            var no = jsondata.data[i].IssueNo;

            var label = "";
            if (year !== null) label += "Год: " + year + " ";
            if (vol !== null) label += "Том: " + vol + " ";
            if (no !== null) label += "Номер: " + no + " ";

            issues.push({ year: year, vol: vol, no: no, label: label });
          }
          if (data.length == 0) issues.push({ label: "Объект не найден" });

          response(issues);
        },
        "json",
      );
    },
    select: function (event, ui) {
      event.preventDefault();
      setIssue(ui.item);
      $("#issueSearch").val("");
    },
  });

  //Clear issue data
  function clearIssue() {
    $("#IYear").val("");
    $("#IVolumeNo").val("");
    $("#IIssueNo").val("");
  }

  //Set issue data
  function setIssue(item) {
    $("#IYear").val(item.year);
    $("#IVolumeNo").val(item.vol);
    $("#IIssueNo").val(item.no);
  }

  //Find conference by title
  $("#conferenceSearch").autocomplete({
    minLength: 1,
    source: function (request, response) {
      $.get(
        ADMIN_URL,
        { action: "articles_conferences_find_json", kw: request.term },
        function (jsondata) {
          var conferences = [];
          var data = jsondata.data;
          for (var i = 0; i < data.length; i++) {
            var title = jsondata.data[i].Title;
            var country = jsondata.data[i].Country;
            var city = jsondata.data[i].City;
            var dateFrom = jsondata.data[i].DateFrom;
            var dateTo = jsondata.data[i].DateTo;
            var id = jsondata.data[i].ID_Conference;
            conferences.push({
              title: title,
              country: country,
              city: city,
              dateFrom: dateFrom,
              dateTo: dateTo,
              id: id,
              label: dateFrom.substring(0, 4) + ": " + title,
            });
          }
          if (data.length == 0) conferences.push({ label: "Объект не найден" });

          response(conferences);
        },
        "json",
      );
    },
    select: function (event, ui) {
      event.preventDefault();
      setConference(ui.item);
      $("#conferenceSearch").val("");
    },
  });

  //Set conference data
  function setConference(item) {
    $("#CTitle").val(item.title);
    $("#CTitle").trigger("change");
    $("#CCountry").val(item.country);
    $("#CCity").val(item.city);
    $("#CDateFrom").val(item.dateFrom);
    $("#CDateTo").val(item.dateTo);
  }

  //Hide or show conference fields
  $("#AConference").on("change", function () {
    var checked = this.checked;
    $("#conference").toggle(checked);
    $("#conference")
      .find("input:not(#conferenceSearch), textarea")
      .each(function () {
        $(this).prop("required", checked);
      });
  });

  //Autodefine country language (Russia или Россия)
  $("#CTitle").on("change", function () {
    var str = "Russia";
    if (strIsMostlyRus($(this).val())) str = "Россия";

    $('#CCountry option[value="RUS"]').html(str);
  });

  //Create article
  $("#createForm").on("submit", function (e) {
    e.preventDefault();

    if ($("#authors tbody tr").length === 0) {
      AddStatusMsg([2, "Задайте авторов"]);
      return;
    }

    var fd = new FormData($("#createForm")[0]);
    var authors = [];
    var test = [];
    $("#authors tbody tr").each(function () {
      var seqNo = $(this).children().eq(1).html();
      var surname = $(this).children().find("input[name=Surname]").val();
      var initials = $(this).children().find("input[name=Initials]").val();
      initials = initials.replace(". ", ".");
      authors.push({ SeqNo: seqNo, Surname: surname, Initials: initials });
      test.push(surname + "|" + initials);
    });
    if (new Set(test).size !== test.length) {
      AddStatusMsg([2, "Задано два одинаковых автора"]);
      return;
    }
    fd.append("Authors", JSON.stringify(authors));

    if (pdfFM.filesCount > 0) fd.append("pdffile", pdfFM.file);

    fd.append("action", "articles_create_or_update_json");

    // Create article
    if (!ID_Article) {
      let atitle = $("#createForm [name=ATitle]").val();
      // Test title for twins
      $.get(
        ADMIN_URL,
        { action: "articles_test_title_json", Title: atitle },
        function (response) {
          let data = JSON.parse(response);
          let addFlag = true;
          if (data.Similarity > 99) {
            let r = confirm(
              `Есть очень похожая статья с названием "${data.Title}". Точно добавить новую?`,
            );
            addFlag = r;
          }

          if (addFlag) addArticle(fd);
        },
      );
    }
    // Edit article
    else {
      addArticle(fd);
    }
  });

  /**
   * Add article
   * @param {*} */
  function addArticle(fd) {
    $.ajax({
      type: "POST",
      url: ADMIN_URL,
      data: fd,
      contentType: false,
      processData: false,
      success: function (response) {
        var data = JSON.parse(response).data;
        if (data[0] != 1) AddStatusMsg(data);
        else {
          PushStatusMsgInSession(data);
          window.location.href = SITE_URL + "/articles/view/?id=" + data[2];
        }
      },
    });
  }

  //Cancel
  $(".cancel").click(function () {
    window.history.back();
  });
});
