<?php
namespace WMY\OnePiece\Tools\Controllers;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

use Storage;
use Carbon\Carbon;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExcelController extends Controller
{
    public function excelGroupSplitShow(){
        return view('one-piece::excel.group_split', [
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

    public function excelGroupSplit(Request $request){
        $fileNameField = 'file_name';
        if(!$request->hasFile($fileNameField)){
            dd('请上传xlsx文件');
        }
        $fileExtension = $request->$fileNameField->extension();
        if(strtolower($fileExtension) != 'xlsx'){
        //     dd("必须是xlsx文件: {$fileExtension}");
            $fileExtension = 'xlsx';
        }
        $fileOriginalName = $request->$fileNameField->getClientOriginalName();
        $storageDisk = 'public';
        $fileSubPath = implode('/', [
            'excel',
            Carbon::now()->format('Ymd'),
            md5(microtime() . $fileOriginalName . $this->numberRandom(6)),
        ]);
        $filePath = $request->$fileNameField->storeAs($fileSubPath, $fileOriginalName, $storageDisk);

        $filePath = Storage::disk($storageDisk)->path($filePath);
        $activeSheetName = $request->input('sheet_name', '');
        $groupBy = $request->input('group_by', '');

        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filePath);
        $spreadsheet->setActiveSheetIndexByName($activeSheetName);
        $activeSheet = $spreadsheet->getActiveSheet();
        $activeSheetArray = $activeSheet->toArray();
        $header = array_first($activeSheetArray);
        $groupByIndex = 0;
        foreach($header as $index=>$headerItem){
            if($headerItem == $groupBy){
                $groupByIndex = $index;
                break;
            }
        }
        $data = [];
        foreach($activeSheetArray as $dataIndex=>$dataItem){
            if($dataIndex != 0){
                $groupName = trim($dataItem[$groupByIndex]);
                if($groupName){
                    $data[$groupName] = array_get($data, $groupName, []);
                    $data[$groupName][] = $dataItem;
                }
            }
        }

        foreach($data as $groupName=>$groupItems){
            foreach(explode("\n", $request->group_join) as $groupJoinItem){
                $groupJoinItem = trim($groupJoinItem);
                $groupJoinItemArray = explode(',', $groupJoinItem);
                if(in_array($groupName, $groupJoinItemArray)){
                    $data[$groupJoinItem] = array_get($data, $groupJoinItem, []);
                    foreach($groupItems as $groupItem){
                        $data[$groupJoinItem][] = $groupItem;
                    }
                    unset($data[$groupName]);
                }
            }
        }

        // $cells = $activeSheet->getCellCollection()->getCoordinates();
        // $data = [];
        // foreach($cells as $cell){
        //     $data[$cell] = $activeSheet->getCell($cell)->getValue();
        // }

        $sourceFilename = $this->getFileNameWithoutExtension($filePath);
        foreach($data as $groupName=>$dataList){
            $newSpreadsheet = new Spreadsheet();
            $newActiveSheet = $newSpreadsheet->getActiveSheet();
            $newActiveSheet->setTitle($activeSheetName);
            $currentLine = 1;
            foreach($header as $headerIndex=>$headerValue){
                $newActiveSheet->setCellValueByColumnAndRow($headerIndex + 1, $currentLine, $headerValue);
            }
            foreach($dataList as $dataItem){
                $currentLine++;
                foreach($dataItem as $dataIndex=>$dataValue){
                    $newActiveSheet->setCellValueExplicitByColumnAndRow($dataIndex + 1, $currentLine, $dataValue, DataType::TYPE_STRING);
                }
            }
            $fileName = "{$sourceFilename}_{$groupName}";
            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($newSpreadsheet, 'Xlsx');
            $writer->save("{$fileName}.{$fileExtension}");
        }

        // $zipCommand = implode(' ', [
        //     'zip -q -r',
        //     Storage::disk($storageDisk)->path($fileSubPath)  . '.zip',
        //     Storage::disk($storageDisk)->path($fileSubPath),
        // ]);
        // dd($zipCommand);
        // dd(exec($zipCommand));

        $zip = new \ZipArchive();
        if($zip->open(Storage::disk($storageDisk)->path($fileSubPath) . '.zip', \ZipArchive::CREATE) === TRUE){
            $this->addFileToZip(Storage::disk($storageDisk)->path($fileSubPath), $zip); //调用方法，对要打包的根目录进行操作，并将ZipArchive的对象传递给方法
            $zip->close(); //关闭处理的zip文件
            return redirect(Storage::disk($storageDisk)->url($fileSubPath) . '.zip');
        }else{
            dd('失败');
        }
    }

    protected function getFileNameWithoutExtension($fileName){
        $fileNameArray = explode('.', $fileName);
        unset($fileNameArray[count($fileNameArray) - 1]);
        return implode('.', $fileNameArray);
    }

    protected function addFileToZip($path, $zip){
        $handler = opendir($path); //打开当前文件夹由$path指定。
        while(($filename = readdir($handler)) !== false){
            if($filename != "." && $filename != ".."){//文件夹文件名字为'.'和‘..’，不要对他们进行操作
                if(is_dir($path."/".$filename)){// 如果读取的某个对象是文件夹，则递归
                    $this->addFileToZip("{$path}/{$filename}", $zip);
                }else{ //将文件加入zip对象
                    // $zip->addFile("{$path}/{$filename}");
                    $zip->addFile("{$path}/{$filename}", $filename);
                }
            }
        }
        @closedir($path);
    }

    protected function numberRandom($length = 6, $options = [
        'except' => [4],
    ])
    {
        $pool = '';
        $exceptNumbers = array_get($options, 'except', []);
        for($i = 0; $i < 10; $i++){
            if(!in_array($i, $exceptNumbers)){
                $pool .= $i;
            }
        }
        return substr(str_shuffle(str_repeat($pool, $length)), 0, $length);
    }
}