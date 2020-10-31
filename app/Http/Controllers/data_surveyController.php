<?php

namespace App\Http\Controllers;

use App\Models\data_survey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

use function GuzzleHttp\Promise\all;

class data_surveyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function pengolahan($get=1)
    {
        $sum=0;
        $count=0;
        $sum_o=0;
        $count_o=0;
        $var_p_all=0;
        $stdev_p_all=0;
        //ambil data dari database
        $data_mentah = data_survey::all()->keyBy("id");
        $data_urut=data_survey::all()->sortBy("data")->keyBy("id");
        //menjumlahkan keseluruhan data dan menghitung berapa jumlah data
        foreach($data_mentah as $data_){
            $sum+=$data_['data'];
            $count++;
        }
        //mean = sum/count, ini untuk semua data termasuk outlier
        $mean_all=$sum/$count;
        //menghitung variasi populasi seluruh data termasuk outlier
        foreach($data_mentah as $data_){
            $var_p_all+=pow($data_['data']-$mean_all,2)/$count;
        }
        //menghitung standart deviasi populasi seluruh data termasuk outlier
        $stdev_p_all=sqrt($var_p_all);
        foreach($data_mentah as $key => $data_){
            $data_mentah[$key]['z_score']=number_format(($data_['data']-$mean_all)/$stdev_p_all,9);//menghitung nilai z-score
            if ($data_mentah[$key]['z_score']>3||$data_mentah[$key]['z_score']<-3) {//jika nilai z-score lebih dari 3 atau kurang dari -3 maka data tersebut adalah outlier
                unset($data_urut[$key]);//menghapus data dari tabel data yang akan digunakan
            }else{
                $count_o++;//menghitung banyak data yang tidak outlier
                $sum_o+=$data_['data'];//menghitung jumlah data yang tidak outlier
            }
        }
        $data_urut_array=[];//array sementara untuk menghitung median, karena data yang diambil dari database berbentuk object maka saya membuat sebuah array baru
        $data_urut_only=[];//array untuk menyimpan data saja, tanpa menyimpan nomor datanya. Digunakan untuk menentukan modus
        foreach ($data_urut as $key => $data_) {
            $d=[
                'id'=>$data_['id'],
                'data'=>$data_['data']
            ];
            array_push($data_urut_array,(object)$d);//memasukkan object kedalam array
            array_push($data_urut_only,$data_['data']);
        }

        //menghitung median
        if ($count_o%2==0) {//jika jumlah data genap maka menggunakan rumus ini
            $median=($data_urut_array[ceil($count_o/2)-1]->data+$data_urut_array[ceil($count_o/2)]->data)/2;

        } else {//jika ganjil maka menggunakan rumus ini
            $median=$data_urut_array[ceil($count_o/2)-1]->data;
        }
        //Quartil 1
        if ((ceil($count_o/2))%2==0) {
            $quartil1=($data_urut_array[ceil($count_o/4)-1]->data+$data_urut_array[ceil($count_o/4)]->data)/2;
        }else{
            $quartil1=$data_urut_array[ceil($count_o/4)-1]->data;
        }
        //Quartil 3
        if ((ceil($count_o/2))%2==0) {
            $quartil3=($data_urut_array[ceil($count_o/4*3)-1]->data+$data_urut_array[ceil($count_o/4*3)]->data)/2;
        }else{
            $quartil3=$data_urut_array[ceil($count_o/4*3)-1]->data;
        }
        //menghitung modus
        $values_temp = array_count_values($data_urut_only);
        $modus=array_search(max($values_temp),$values_temp);
        //menghitung Varian
        $var_s=0;
        $var_p=0;
        $mean=$sum_o/$count_o;
        foreach ($data_urut as $key => $value) {
            $var_p+=pow($value['data']-$mean,2)/$count_o;
            $var_s+=pow($value['data']-$mean,2)/($count_o-1);
        }
        //Menghitung Standar Deviasi
        $stdev_p=sqrt($var_p);
        $stdev_s=sqrt($var_s);
        //mengumpulkan data data diatas untuk dikirim ke view.
        $data=[
            'table_mentah'=>$data_mentah,
            'table_urut'=>$data_urut,
            'mean' => number_format($mean,2),//mean dengan rumus sum/count, ini merupakan mean tanpa data outlier
            'median'=>$median,
            'modus'=>$modus,
            'quartil1'=>$quartil1,
            'quartil3'=>$quartil3,
            'var_p'=>number_format($var_p,2),
            'var_s'=>number_format($var_s,2),
            'std_p'=>number_format($stdev_p,2),
            'std_s'=>number_format($stdev_s,2)
        ];
        if($get==1){
            return view('content.pengolahan')->with($data);
        }elseif($get=="data_only"){
            return $data_urut_only;
        }elseif ($get=="count") {
            return $count_o;
        }
    }
    public function penyajian(){
        $data_urut=$this->pengolahan("data_only");
        $count=$this->pengolahan("count");
        $range=max($data_urut)-min($data_urut);
        $banyak_kelas=ceil(3.3*log($count,10)+1);
        $range_kelas=ceil($range/$banyak_kelas);
        $data_penyajian=[];
        $frek_k=0;
        for ($i=0; $i < $banyak_kelas; $i++) {
            $temp=[];
            $min_val=$i*$range_kelas+min($data_urut);
            $max_val=($i+1)*$range_kelas+min($data_urut)-1;
            foreach ($data_urut as $key => $value) {
                if ($value>=$min_val && $value<=$max_val) {
                    array_push($temp,$value);
                }
            }
            $frek_k+=count($temp);
            $data_penyajian[$i]['frek']=count($temp);
            $data_penyajian[$i]['data']=$temp;
            $data_penyajian[$i]['min']=$min_val;
            $data_penyajian[$i]['max']=$max_val;
            $data_penyajian[$i]['frek_k']=$frek_k;
            $data_penyajian[$i]['frek_r']=number_format(count($temp)/$count*100,2);
            $data_penyajian[$i]['frek_rk']=number_format($frek_k/$count*100,2);
        }
        $data=[
            'data_penyajian'=>$data_penyajian
        ];
        return view('content.penyajian')->with($data);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'data'=>"required"
        ]);
        data_survey::create($request->all());
        return Redirect::to('pengolahan')->with('success','Data berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\data_survey  $data_survey
     * @return \Illuminate\Http\Response
     */
    public function show(data_survey $data_survey)
    {

    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\data_survey  $data_survey
     * @return \Illuminate\Http\Response
     */
    public function edit(data_survey $data_survey)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\data_survey  $data_survey
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, data_survey $data_survey)
    {
        $data=data_survey::where('id',$request->id)
        ->update([
            'data'=>$request->data
        ]);
        return Redirect::to('pengolahan')->with('success','Data berhasil di edit');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\data_survey  $data_survey
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,data_survey $data_survey)
    {

    }
    public function delete($id){
        data_survey::destroy($id);
        print_r($id);
        return Redirect::to('pengolahan')->with('success','Data berhasil di delete');
    }
}
