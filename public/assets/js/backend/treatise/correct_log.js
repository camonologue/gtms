define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'treatise.correct_log/index',
                    add_url: 'treatise.correct_log/add',
                    edit_url: 'treatise.correct_log/edit',
                    del_url: 'treatise.correct_log/del',
                    multi_url: 'treatise.correct_log/multi',
                    table: 'correct_log',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id'), sortable: true},
                        {field: 'treatise.user.username', title: __('Username'), operate: 'LIKE'},
                        {field: 'treatise.title', title: __('Title'), operate: 'LIKE'},
                        {field: 'teacher.username', title: __('TeacherUser'), operate: 'LIKE'},
                        {field: 'correct', title: __('Correct'), operate: 'LIKE'},
                        {field: 'score', title: __('Score'), operate: 'LIKE'},

                        {
                            field: 'creatime',
                            title: __('Creatime'),
                            formatter: Table.api.formatter.datetime,
                            operate: 'RANGE',
                            addclass: 'datetimerange',
                            sortable: true
                        },
                        {
                            field: 'operate',
                            title: __('Operate'),
                            table: table,
                            events: Table.api.events.operate,
                            formatter: Table.api.formatter.operate
                        }
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        add: function () {
            Controller.api.bindevent();
        },
        edit: function () {
            Controller.api.bindevent();
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});