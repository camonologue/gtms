define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'treatise.treatise/index',
                    add_url: 'treatise.treatise/add',
                    edit_url: 'treatise.treatise/edit',
                    del_url: 'treatise.treatise/del',
                    multi_url: 'treatise.treatise/multi',
                    table: 'treatise',
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
                        {field: 'user.username', title: __('Username'), operate: 'LIKE'},
                        {field: 'title', title: __('Title'), operate: 'LIKE'},
                        {field: 'batch', title: __('Batch'), operate: 'LIKE'},
                        {field: 'download', title: __('Preview'), formatter: Controller.api.formatter.thumb, operate: false},
                        {field: 'attachment.filename', title: __('Filename'), sortable: true, formatter: Controller.api.formatter.filename, operate: 'like'},
                        {field: 'attachment.filename', title: __('Download'), sortable: true, formatter: Controller.api.formatter.download, operate: 'like'},
                        {
                            field: 'createtime',
                            title: __('Createtime'),
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

            // 表单点击事件
            // $(document).on('click','.btn-download',function () {
            //     $.ajax({
            //         type : "get",			//以post方法提交数据给服务器
            //         url : "treatise.treatise/downfile",				//提交数据到User
            //         // responseType: "arraybuffer"
            //         // dataType : "text",		//数据类型
            //         // data : {						//传给服务器的数据
            //         //     "name": $("#name").val(),
            //         //     "password":$("#pwd").val()
            //         // },
            //     });
            // });

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
            },
            formatter: {
                thumb: function (value, row, index) {
                    html = '<a href="' + row.download + '" target="_blank"><img src="' + Fast.api.fixurl("ajax/icon") + "?suffix=" + row.download + '" alt="" style="max-height:90px;max-width:120px"></a>';
                    return '<div style="width:120px;margin:0 auto;text-align:center;overflow:hidden;white-space: nowrap;text-overflow: ellipsis;">' + html + '</div>';
                },
                url: function (value, row, index) {
                    return '<a href="' + row.fullurl + '" target="_blank" class="label bg-green">' + row.url + '</a>';
                },
                filename: function (value, row, index) {
                    return '<div style="width:150px;margin:0 auto;text-align:center;overflow:hidden;white-space: nowrap;text-overflow: ellipsis;" target="_blank"><a href="' + row.download + '" title="' + row.attachment.filename + '">' + row.attachment.filename + '</a></div>';
                },
                download: function (value, row, index) {
                    //' + row.download + '
                    return '<div class="btn-download" style="width:150px;margin:0 auto;text-align:center;overflow:hidden;white-space: nowrap;text-overflow: ellipsis;"><a src="javascript:;" href="download?filename=' + row.download + '&name=' + row.attachment.filename + '" title="' + row.attachment.filename + '">点击下载</a></div>';
                },
            }

        }
    };
    return Controller;
});

