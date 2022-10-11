<?php
namespace app\common\library;

use think\facade\Db;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class Excel
{


 /**
  * 导入
  * @param string $filename
  * @return array|string
  * @throws \PhpOffice\PhpSpreadsheet\Exception
  * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
  */
 public static function importExcel($filename = "",$table = "",$database = ""){
     /**
      * 注意，导入时，需要自行建表，和Excel表格格式相同
      */
     $file[] = $filename;

     try {
         /**
          * 验证文件大小为2M
          * 后缀为Excel表格的后缀
          */
         validate(['file' => 'fileSize:2097152|fileExt:xls,xlsx'])
             ->check($file);
         /**
          * 将文件保存到本地
          */
         $savename = \think\facade\Filesystem::disk('public')->putFile('file', $file[0]);
         /**
          * 获取文件后缀
          */
         $fileExtendName = substr(strrchr($savename, '.'), 1);
         /**
          * xls和xlsx两种格式
          */
         if ($fileExtendName == 'xlsx') {
             $objReader = IOFactory::createReader('Xlsx');
         } else {
             $objReader = IOFactory::createReader('Xls');
         }
         /**
          * 设置文件为只读
          */
         $objReader->setReadDataOnly(TRUE);
         /**
          * 读取文件，thinkPHP默认上传文件，在runtime目录下，可根据实际情况进行更改，在config目录下，filesystem.php中更改
          */
         $objPHPExcel = $objReader->load(public_path() . 'storage/' . $savename);
         /**
          * excel中的第一张纸
          */
         $sheet = $objPHPExcel->getSheet(0);
         /**
          * 获取总行数
          */
         $highestRow = $sheet->getHighestRow();
         /**
          * 获取总列数
          */
         $highestColumn = $sheet->getHighestColumn();
         Coordinate::columnIndexFromString($highestColumn);
         $lines = $highestRow - 1;
         if ($lines <= 0) {
             return "数据为空数组";
         }
         /**
          * 直接取出Excel中的数据
          */
         $data = $objPHPExcel->getActiveSheet()->toArray();
         /**
          * 删除第一行元素，Excel表格的表头
          * 第一行，也可以留着，插入对应字段后，当注释，具体请百度，本尊不再详细讲解
          */
         array_shift($data);
         /**
          * 删除文件
          */
         unlink(public_path() . 'storage/' . $savename);
         /**
          * 输入表名，查找对应字段
          */
         $columns =Db::query("select column_name from information_schema.columns where table_name='$table' and table_schema='$database'");
         /**
          * 将查询的字段放入对应索引数组
          */
         $array = [];
         foreach ($columns as $k=>$v){
             array_push($array,implode(array_values($v)));
         }
         /**
          * 循环数据，也就是二维关联数组，生成对应的键，方便插入
          * 注意：表中字段，一定要和Excel表格的列数一样，不然会报错，也就是对应的字段数据，没有键
          */
         $count_column = count($array);
         $newArray = [];
         $finalArray = [];
         foreach($data as $k=>$v){
             for ($i=1;$i<$count_column;$i++){
                 $newArray[$array[$i]] = $v[$i];
             }
             $finalArray[] = $newArray;
         }
         foreach ($finalArray as $k=>$v){
             Db::table($table)->insert($v);
         }
         /**
          * 循环添加，将数据插入数据库
          * 注意：由于使用了Db查询构造器插入
          * 表内字段，出了主键不能为空且自增以外
          * 其他字段最好不要在不能为空的方框内打对号
          * 不然Excel导入数据有空值的情况下，会报错
          * 若是用模型插入，并且，字段有默认值的情况下，不会出现这种错误
          * 也就是说，Db查询构造器，字段有默认值，不能为空，当你Excel表格存在空值，依旧报错
          */
     } catch (ValidateException $e) {
         return $e->getMessage();
     }
 }

  /**
  * Excel表格导出
  * Excel扩展名为xlsx和xls两种，默认为false
  */
  public static function exportExcel($table = '' , $database = '' , $type = false , $fileName = '新建Excel表格'){
      /**
      * title:封装Excel导出header头
      * 优点:只需要传入参数，也就是表名和数据库名,解决超过26位无限循环问题
      */
      $res =Db::query("SELECT column_comment FROM INFORMATION_SCHEMA.Columns WHERE table_name='$table' AND table_schema='$database'");
      $column_length = count($res);
      $arr = [];
      // 设置excel表的表头
      $start = 'A';
      for ($i = 0;$i < $column_length;$i++){
          array_push($arr,$start.'1');
          $start ++;
      }
      // 读取数据
      $newArr = [];
      foreach ($res as $k=>$v){
          array_push($newArr,implode(array_values($v)));
      }
 
      // 设置头部信息
      $header = [];
      for ($i=0;$i<$column_length;$i++){
          $header["$arr[$i]"] = $newArr[$i];
      }
      /**
       * @data 从数据库查询的数据
       * @method/Db查询构造器或者从模型查询(依赖注入、静态、实例化对象)
       * Db查询构造器查询完成，转化成二维关联数组
       */
      $data = Db::table($table)->select()->toArray();
      foreach ($data as $k=>$v){
          $array = array_values($v);
          $data[$k] = $array;
      }
 
      /**
       * 实例化类
       */
      $preadsheet = new Spreadsheet();
      /**
       * 创建sheet纸张
       */
      $sheet = $preadsheet->getActiveSheet();
      /**
       * 循环header表头数据
       */
      foreach ($header as $k => $v) {
          $sheet->setCellValue($k, $v);
      }
      /**
       * 生成数据
       */
      $sheet->fromArray($data, null, "A2");
      /**
       * 样式设置
       */
      $sheet->getDefaultColumnDimension()->setWidth(12);
      /**
       * 下载与后缀
       */
      if ($type) {
          header("Content-Type:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
          $type = "Xlsx";
          $suffix = "xlsx";
      } else {
          header("Content-Type:application/vnd.ms-excel");
          $type = "Xls";
          $suffix = "xls";
      }
      /**
       * 清除缓存区
       */
      ob_end_clean();
      /**
       * 激活浏览器窗口
       */
      header("Content-Disposition:attachment;filename=$fileName.$suffix");
      /**
       * 缓存控制
       */
      header("Cache-Control:max-age=0");
      /**
       * 调用方法执行下载
       */
      $writer = IOFactory::createWriter($preadsheet, $type);
      /**
       * 数据流
       */
      $writer->save("php://output");
  }
 

}