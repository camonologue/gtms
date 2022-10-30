define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'faculty_major.faculty/index',
                    add_url: 'faculty_major.faculty/add',
                    edit_url: 'faculty_major.faculty/edit',
                    del_url: 'faculty_major.faculty/del',
                    multi_url: 'faculty_major.faculty/multi',
                }
            });

            var table = $("#table");
            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                columns: [
                    [
                        {field: 'state', checkbox: true,},
                        {field: 'id', title: '学院代码'},
                        {field: 'name', title: '学院名称'},
                        {field: 'detail', title: "学院简介"},
                        {
                            field: 'status',
                            title: '是否启用',
                            formatter: Table.api.formatter.status,
                            searchList: {1: __('Normal'), 0: __('Hidden')}
                        },
                        {
                            field: 'createtime',
                            title: "创建时间",
                            formatter: Table.api.formatter.datetime,
                            operate: 'RANGE',
                            addclass: 'datetimerange',
                            sortable: true
                        },
                        {
                            field: 'updatetime',
                            title: "更新时间",
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
            Form.api.bindevent($("form[role=form]"));
        },
        edit: function () {
            Form.api.bindevent($("form[role=form]"));
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});
