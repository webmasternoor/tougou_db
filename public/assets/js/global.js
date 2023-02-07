function initDataTable(
    table,
    showColumn,
    exportColumns,
    pageLength = 20,
    ordering = false,
    buttonName = "",
    modal = true,
    modalIdOrUrl = "",
    buttonDisable = false
) {
    let button;
    let attr;

    if (modal) {
        attr = {
            "data-bs-toggle": "modal",
            "data-bs-target": modalIdOrUrl,
            disabled: buttonDisable,
        };
    } else {
        attr = {
            onclick: "location.href='" + modalIdOrUrl + "'",
            disabled: buttonDisable,
        };
    }

    if (buttonName != "") {
        button = {
            text: buttonName,
            className: "btn btn-primary mb-3 mb-md-0",
            attr: attr,
        };
    } else {
        button = [];
    }

    $(table).DataTable({
        dom: '<"row mx-2"<"col-md-2"<"me-3"l>><"col-md-10"<"dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-end flex-md-row flex-column mb-3 mb-md-0"fB>>>t<"row mx-2"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
        columns: showColumn,
        language: {
            url: lang_url,
        },
        buttons: [{
                extend: "collection",
                className: "btn btn-secondary dropdown-toggle mx-3",
                text: '<i class="bx bx-upload me-2"></i>' + export_lang,
                buttons: [{
                        extend: "print",
                        charset: "UTF-8",
                        text: '<i class="bx bx-printer me-2" ></i>' + print_lang,
                        className: "dropdown-item",
                        exportOptions: {
                            columns: exportColumns,
                        },
                        customize: function(e) {
                            $(e.document.body)
                                .css("color", config.colors.headingColor)
                                .css("border-color", config.colors.borderColor)
                                .css("background-color", config.colors.body),
                                $(e.document.body)
                                .find("table")
                                .addClass("compact")
                                .css("color", "inherit")
                                .css("border-color", "inherit")
                                .css("background-color", "inherit");
                        },
                    },
                    {
                        extend: "csv",
                        text: '<i class="bx bx-file me-2" ></i>CSV',
                        className: "dropdown-item",
                        charset: "UTF-8",
                        bom: true,
                        exportOptions: {
                            columns: exportColumns,
                        },
                        modifier: {
                            page: "all",
                        },
                    },
                ],
            },
            button,
        ],
        aLengthMenu: [
            [10, 20, 50, 100, 200, -1],
            [10, 20, 50, 100, 200, "All"],
        ],
        pageLength: pageLength,
        ordering: ordering,
    });
}
let dataTable2;
let reInit = 0;

function initServerSideDataTable(
    url,
    exportUrl,
    table,
    showColumn,
    exportColumns,
    pageLength = 20,
    buttonName = "",
    modal = true,
    modalIdOrUrl = "",
    buttonDisable = false
) {
    let button;
    let attr;

    if (modal) {
        attr = {
            "data-bs-toggle": "modal",
            "data-bs-target": modalIdOrUrl,
            disabled: buttonDisable,
        };
    } else {
        attr = {
            onclick: "location.href='" + modalIdOrUrl + "'",
            disabled: buttonDisable,
        };
    }

    if (buttonName != "") {
        button = {
            text: buttonName,
            className: "btn btn-primary mb-3 mb-md-0",
            attr: attr,
        };
    } else {
        button = [];
    }
    dataTable2 = $(table).DataTable({
        dom: '<"row mx-2"<"col-md-2"<"me-3"l>><"col-md-10"<"dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-end flex-md-row flex-column mb-3 mb-md-0"fB>>>t<"row mx-2"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
        columns: showColumn,
        processing: true,
        serverSide: true,
        language: {
            url: lang_url,
        },
        ajax: url,
        buttons: [{
                extend: "collection",
                className: "btn btn-secondary dropdown-toggle mx-3",
                text: '<i class="bx bx-upload me-2"></i>' + export_lang,
                buttons: [{
                        extend: "print",
                        charset: "UTF-8",
                        text: '<i class="bx bx-printer me-2" ></i>' + print_lang,
                        className: "dropdown-item",
                        exportOptions: {
                            columns: exportColumns,
                        },
                        customize: function(e) {
                            $(e.document.body)
                                .css("color", config.colors.headingColor)
                                .css("border-color", config.colors.borderColor)
                                .css("background-color", config.colors.body),
                                $(e.document.body)
                                .find("table")
                                .addClass("compact")
                                .css("color", "inherit")
                                .css("border-color", "inherit")
                                .css("background-color", "inherit");
                        },
                    },
                    {
                        extend: "csv",
                        text: '<i class="bx bx-file me-2" ></i>CSV',
                        className: "dropdown-item",
                        // charset: "UTF-8",
                        // bom: true,
                        // exportOptions: {
                        //     columns: exportColumns,
                        // },
                        // modifier: {
                        //     page: "all",
                        // },
                        action: function(e, dt, node, config) {
                            window.open(exportUrl, "_blank");
                        },
                    },
                ],
            },
            button,
        ],
        aLengthMenu: [
            [10, 20, 50, 100, 200, -1],
            [10, 20, 50, 100, 200, "All"],
        ],
        pageLength: pageLength,
        rowReorder: true,
        columnDefs: [
            { orderable: true, className: "reorder", targets: 0 },
            { orderable: false, targets: "_all" },
        ],
        order: [
            [0, "desc"]
        ],
    });
}

function serverSideSearch(url, value) {
    if (reInit == 1) {
        dataTable2.ajax.url(url).load();
        reInit = 0;
    }
    dataTable2.search(value).draw();
}

function reInitServerSideDataTable(url) {
    reInit = 1;
    dataTable2.ajax.url(url).load();
}

function deleteData(url) {
    const csrf = $('meta[name="csrf-token"]').attr("content");
    $.confirm({
        title: "Are you sure?",
        content: "" +
            '<form action="' +
            url +
            '" method="POST">' +
            "<p>Please confirm before delete.</p>" +
            '<input type="hidden" name="_method" value="DELETE">' +
            '<input type="hidden" name="_token" value="' +
            csrf +
            '" >' +
            "</form>",
        type: "red",
        typeAnimated: true,
        buttons: {
            formSubmit: {
                text: "Delete",
                btnClass: "btn-red",
                action: function() {
                    this.$content.find("form").submit();
                },
            },
            cancel: function() {
                //close
            },
        },
    });
}

function changeStatus(url, action) {
    const csrf = $('meta[name="csrf-token"]').attr("content");
    $.confirm({
        title: "Are you sure?",
        content: "" +
            '<form action="' +
            url +
            '" method="POST">' +
            "<p>Do you still want to continue?</p>" +
            '<input type="hidden" name="action" value="' +
            action +
            '">' +
            '<input type="hidden" name="_token" value="' +
            csrf +
            '" >' +
            "</form>",
        type: "red",
        typeAnimated: true,
        buttons: {
            formSubmit: {
                text: "Yes",
                btnClass: "btn-red",
                action: function() {
                    this.$content.find("form").submit();
                },
            },
            cancel: function() {
                //close
            },
        },
    });
}