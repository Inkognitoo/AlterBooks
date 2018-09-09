let DatatablesSearchOptionsColumnSearch = function() {

    $.fn.dataTable.Api.register('column().title()', function() {
        return $(this.header()).text().trim();
    });

    let initTable1 = function() {

        // begin first table
        let table = $('#users_table').DataTable({
            responsive: true,

            //== Pagination settings
            dom: `<'row'<'col-sm-12'tr>>
            <'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dataTables_pager'lp>>`,
            // read more: https://datatables.net/examples/basic_init/dom.html

            lengthMenu: [5, 10, 20, 50],

            pageLength: 10,

            language: {
                lengthMenu: 'Показать _MENU_',
                info: 'Показать с _START_ по _END_ из _TOTAL_',
                infoEmpty: "Найдено 0",
                infoFiltered: "(отфильтрованно из _MAX_)",
            },

            searchDelay: 500,
            processing: true,
            serverSide: true,
            ajax: {
                url: '/api/v1/users',
                type: 'POST',
                headers: {
                    'Authorization': 'Bearer ' + document.getElementsByName('api_token')[0].content,
                },
            },
            columns: [
                {
                    data: 'id',
                    title: 'ID'
                },
                {
                    data: 'email',
                    title: 'Email'
                },
                {
                    data: 'name',
                    title: 'Имя'
                },
                {
                    data: 'surname',
                    title: 'Фамилия'
                },
                {
                    data: 'patronymic',
                    title: 'Отчество',
                },
                {
                    data: 'nickname',
                    title: 'Никнейм',
                },
                {
                    data: 'gender',
                    title: 'Гендер',
                },
                {
                    data: 'is_admin',
                    title: 'Админ',
                },
                {
                    data: 'is_trashed',
                    title: 'Состояние',
                },
                {
                    data: 'created_at',
                    title: 'Дата регистрации',
                },
                {
                    data: 'Actions',
                    title: 'Действия',
                }
            ],
            initComplete: function() {
                let rowFilter = $('<tr class="filter"></tr>').appendTo($(table.table().header()));

                this.api().columns().every(function() {
                    let column = this;
                    let input;

                    switch (column.title()) {
                        case 'ID':
                        case 'Email':
                        case 'Имя':
                        case 'Фамилия':
                        case 'Отчество':
                        case 'Никнейм':
                            input = $(`<input type="text" class="form-control form-control-sm form-filter m-input" data-col-index="` + column.index() + `"/>`);
                            break;
                        case 'Гендер':
                            let genders = {
                                m: {'title': 'Мужской'},
                                f: {'title': 'Женский'},
                                n: {'title': 'Не определён'},
                            };
                            input = $(`<select class="form-control form-control-sm form-filter m-input" title="Select" data-col-index="` + column.index() + `">
										<option value="">Выберите</option></select>`);
                            for(let i in genders) {
                                $(input).append('<option value="' + i + '">' + genders[i].title + '</option>');
                            }
                            break;
                        case 'Админ':
                            let status = {
                                'true': {'title': 'Да'},
                                'false': {'title': 'Нет'},
                            };
                            input = $(`<select class="form-control form-control-sm form-filter m-input" title="Select" data-col-index="` + column.index() + `">
										<option value="">Выберите</option></select>`);
                            for(let i in status) {
                                $(input).append('<option value="' + i + '">' + status[i].title + '</option>');
                            }
                            break;
                        case 'Состояние':
                            let state = {
                                'true': {'title': 'Удалёно'},
                                'false': {'title': 'На площадке'},
                            };
                            input = $(`<select class="form-control form-control-sm form-filter m-input" title="Select" data-col-index="` + column.index() + `">
										<option value="">Выберите</option></select>`);
                            for(let i in state) {
                                $(input).append('<option value="' + i + '">' + state[i].title + '</option>');
                            }
                            break;
                        case 'Дата регистрации':
                            input = $(`
                                <div class="input-group date">
                                    <input type="text" class="form-control form-control-sm m-input" readonly placeholder="От" id="created_at_datepicker_1"
                                     data-col-index="` + column.index() + `"/>
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="la la-calendar-o glyphicon-th"></i></span>
                                    </div>
                                </div>
                                <div class="input-group date">
                                    <input type="text" class="form-control form-control-sm m-input" readonly placeholder="До" id="created_at_datepicker_2"
                                     data-col-index="` + column.index() + `"/>
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="la la-calendar-o glyphicon-th"></i></span>
                                    </div>
                                </div>`);
                            break;
                        case 'Действия':
                            let search = $(`<button class="btn btn-brand m-btn btn-sm m-btn--icon">
							  <span>
							    <i class="la la-search"></i>
							    <span>Поиск</span>
							  </span>
							</button>`);

                            let reset = $(`<button class="btn btn-secondary m-btn btn-sm m-btn--icon">
							  <span>
							    <i class="la la-close"></i>
							    <span>Сброс</span>
							  </span>
							</button>`);

                            $('<th>').append(search).append(reset).appendTo(rowFilter);

                            $(search).on('click', function(e) {
                                e.preventDefault();
                                var params = {};
                                $(rowFilter).find('.m-input').each(function() {
                                    var i = $(this).data('col-index');
                                    if (params[i]) {
                                        params[i] += '|' + $(this).val();
                                    }
                                    else {
                                        params[i] = $(this).val();
                                    }
                                });
                                $.each(params, function(i, val) {
                                    // apply search params to datatable
                                    table.column(i).search(val ? val : '', false, false);
                                });
                                table.table().draw();
                            });

                            $(reset).on('click', function(e) {
                                e.preventDefault();
                                $(rowFilter).find('.m-input').each(function(i) {
                                    $(this).val('');
                                    table.column($(this).data('col-index')).search('', false, false);
                                });
                                table.table().draw();
                            });
                            break;
                    }

                    $(input).appendTo($('<th>').appendTo(rowFilter));
                });

                $('#created_at_datepicker_1,#created_at_datepicker_2').datepicker();
            },
            columnDefs: [
                {
                    targets: -1,
                    title: 'Действия',
                    width: '120px',
                    orderable: false,
                    render: function(data, type, full, meta) {
                        return `
                            <a href="${full.show_url}" class="m-portlet__nav-link btn m-btn m-btn--hover-brand m-btn--icon m-btn--icon-only m-btn--pill" title="View">
                              <i class="la la-eye"></i>
                            </a>
                            <a href="${full.edit_url}" class="m-portlet__nav-link btn m-btn m-btn--hover-brand m-btn--icon m-btn--icon-only m-btn--pill" title="Edit">
                              <i class="la la-edit"></i>
                            </a>`;
                    },
                },
                {
                    targets: 6,
                    width: '80px',
                    render: function(data, type, full, meta) {
                        let gender = 'Мужской';
                        switch (data){
                            case 'm':
                                gender = 'Мужской';
                                break;
                            case 'f':
                                gender = 'Женский';
                                break;
                            case 'n':
                                gender = 'Не определён';
                                break;
                        }
                        return gender;
                    },
                },
                {
                    targets: 7,
                    width: '80px',
                    render: function(data, type, full, meta) {
                        if (data) {
                            return 'Да';
                        } else {
                            return 'Нет';
                        }
                    },
                },
                {
                    targets: 8,
                    width: '80px',
                    render: function(data, type, full, meta) {
                        if (data) {
                            return 'Удалёно';
                        } else {
                            return 'На площадке';
                        }
                    },
                },
                {
                    targets: 9,
                    width: '120px',
                    render: function(data, type, full, meta) {
                        let date_time = new Date(`${data.date} ${data.timezone}`);

                        return `${date_time.getDay()}/${('0' + date_time.getMonth()).slice(-2)}/${date_time.getFullYear()} ${date_time.getHours()}:${date_time.getMinutes()}`;
                    },
                },
            ],

        });

    };

    return {

        //main function to initiate the module
        init: function() {
            initTable1();
        },

    };

}();

jQuery(document).ready(function() {
    DatatablesSearchOptionsColumnSearch.init();
});