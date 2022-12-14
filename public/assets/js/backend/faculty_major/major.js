define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'faculty_major.major/index',
                    add_url: 'faculty_major.major/add',
                    edit_url: 'faculty_major.major/edit',
                    del_url: 'faculty_major.major/del',
                    multi_url: 'faculty_major.major/multi',
                }
            });

            var table = $("#table");
            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                columns: [
                    [
                        {field: 'state', checkbox: true,},
                        {field: 'id', title: '专业代码'},
                        {field: 'name', title: '专业名称'},
                        {field: 'detail', title: "专业简介"},
                        {field: 'faculty.name', title: "所属院系"},
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
