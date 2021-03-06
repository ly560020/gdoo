{{$header["js"]}}
<div class="panel no-border" id="{{$header['table']}}-controller">
    @include('headers')
    <div class="list-jqgrid">
        <div id="{{$header['table']}}-grid" class="ag-theme-balham"></div>
    </div>
</div>
<script>
    (function ($) {
        var table = '{{$header["table"]}}';
        var config = gdoo.grids[table];
        var action = config.action;
        var search = config.search;

        // 自定义搜索方法
        search.searchInit = function (e) {
            var self = this;
        }

        var grid = new agGridOptions();

        var gridDiv = document.querySelector("#{{$header['table']}}-grid");
        gridDiv.style.height = getPanelHeight(48);

        grid.remoteDataUrl = '{{url()}}';
        grid.remoteParams = search.advanced.query;
        grid.columnDefs = config.cols;
        grid.onRowDoubleClicked = function (params) {
            if (params.node.rowPinned) {
                return;
            }
            if (params.data == undefined) {
                return;
            }
            if (params.data.id > 0) {
                action.edit(params.data);
            }
        };

        new agGrid.Grid(gridDiv, grid);

        // 读取数据
        grid.remoteData({page: 1});

        // 绑定自定义事件
        var $gridDiv = $(gridDiv);
        $gridDiv.on('click', '[data-toggle="event"]', function () {
            var data = $(this).data();
            if (data.master_id > 0) {
                action[data.action](data);
            }
        });

        config.grid = grid;

    })(jQuery);
</script>
@include('footers')