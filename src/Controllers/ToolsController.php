<?php
namespace WMY\OnePiece\Tools\Controllers;
use Illuminate\Routing\Controller;

class ToolsController extends Controller
{
    public function excelGroupSplitShow(){
        return view('wmy.one-piece.tools.excel.group_split', [
            'groupJoin' => implode("\n", [
                '焦作本部,焦作二部,焦作三部,焦作温县(暂停营业)',
                '许昌本部,许昌襄县,许昌长葛(注销),许昌禹州(注销)',
                '洛阳本部,洛阳伊川',
                '周口本部(注销),周口淮阳,周口鹿邑(注销),周口沈丘(注销),周口太康(注销)',
                '南阳本部,南阳内乡',
                '新乡本部,新乡长垣,新乡获嘉',
            ]),
        ]);
    }
}