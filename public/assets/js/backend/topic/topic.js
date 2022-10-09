define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'topic.topic/index',
                    add_url: 'topic.topic/add',
                    edit_url: 'topic.topic/edit',
                    del_url: 'topic.topic/del',
                    multi_url: 'topic.topic/multi',
                }
            });

            var table = $("#table");
            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                columns: [
                    [
                        {field: 'state', checkbox: true,},
                        {field: 'id', title: 'ID'},
                        {field: 'major_id', title: '专业代码'},
                        {field: 'topic_batch', title: "课题批次"},
                        {field: 'topic_name', title: '课题名称'},
                        {field: 'topic_source', title: "课题来源"},
                        {field: 'topic_cut', title: '课题类型'},
                        {field: 'topic_details', title: '课题介绍'},
                        {field: 'teacher.username', title: "创建人"},
                        // {
                        //     field: 'enable', 
                        //     title: '是否启用',
                        //     searchList: {1: '是', 0: '否'}
                        // },
                        {
                            field: 'enable',
                            title: '是否启用',
                            formatter: Table.api.formatter.status,
                            searchList: {1: '是', 0: '否'}
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
