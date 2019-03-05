<html>
    <head>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    </head>
    <body>
        <div class="container">
            <h3 class="text-center">Excel根据列拆分文件功能</h3><br>
            <form class="col-xs-4 col-xs-offset-4" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="form-group">
                    <label for="sheet_name">Sheet名称</label>
                    <input type="text" class="form-control" id="sheet_name" name="sheet_name" value="Sheet2" placeholder="">
                </div>
                <div class="form-group">
                    <label for="group_by">拆分字段</label>
                    <input type="text" class="form-control" id="group_by" name="group_by" value="营业部" placeholder="">
                </div>
                <div class="form-group">
                    <label for="group_join">合并组</label>
                    <textarea class="form-control" id="group_join" name="group_join" rows="10" placeholder="">{{ $groupJoin }}</textarea>
                </div>
                <div class="form-group">
                    <label for="file_name">文件</label>
                    <input type="file" id="file_name" name="file_name">
                    <p class="help-block">请上传xlsx文件</p>
                </div>
                <button type="submit" class="btn btn-success btn-block">提交</button>
                </form>
            </div>
        </div>
    </body>
</html>